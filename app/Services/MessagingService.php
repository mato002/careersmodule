<?php

namespace App\Services;

use App\Mail\ContactMessageReplyMail;
use App\Mail\JobApplicationReply;
use App\Mail\LoanApplicationReply;
use App\Models\ContactMessageReply;
use App\Models\JobApplicationMessage;
use App\Models\LoanApplicationMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MessagingService
{
    /**
     * Send a message via the specified channel
     */
    public function send(ContactMessageReply|JobApplicationMessage|LoanApplicationMessage $message): bool
    {
        try {
            switch ($message->channel) {
                case 'email':
                    return $this->sendEmailMessage($message);
                case 'sms':
                    return $this->sendSMSMessage($message);
                case 'whatsapp':
                    return $this->sendWhatsAppMessage($message);
                default:
                    throw new \Exception("Unknown channel: {$message->channel}");
            }
        } catch (\Exception $e) {
            $message->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error("Failed to send {$message->channel} message", [
                'message_id' => $message->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send email message via Laravel Mail (for all message types)
     */
    protected function sendEmailMessage(ContactMessageReply|JobApplicationMessage|LoanApplicationMessage $message): bool
    {
        // Use Laravel Mail for all message types with branded templates
        if ($message instanceof LoanApplicationMessage) {
            Log::info('Sending LoanApplicationMessage via Laravel Mail', [
                'message_id' => $message->id,
            ]);
            return $this->sendLoanApplicationEmailViaLaravelMail($message);
        }

        if ($message instanceof ContactMessageReply) {
            Log::info('Sending ContactMessageReply via Laravel Mail', [
                'message_id' => $message->id,
            ]);
            return $this->sendContactMessageReplyViaLaravelMail($message);
        }

        if ($message instanceof JobApplicationMessage) {
            Log::info('Sending JobApplicationMessage via Laravel Mail', [
                'message_id' => $message->id,
            ]);
            return $this->sendJobApplicationEmailViaLaravelMail($message);
        }

        throw new \Exception('Unknown message type: ' . get_class($message));
    }

    /**
     * Send loan application email via Laravel Mail with branded template
     */
    protected function sendLoanApplicationEmailViaLaravelMail(LoanApplicationMessage $message): bool
    {
        try {
            // Load the loan application relationship if not already loaded
            $message->loadMissing('loanApplication');
            
            if (!$message->loanApplication) {
                throw new \Exception('Loan application not found for message');
            }

            $application = $message->loanApplication;

            Log::info('Sending loan application email via Laravel Mail', [
                'message_id' => $message->id,
                'recipient' => $message->recipient,
                'application_id' => $application->id,
            ]);

            // Send email using Laravel Mail with branded template
            Mail::to($message->recipient)->send(new LoanApplicationReply($application, $message));

            // Update message status
            $message->update([
                'status' => 'sent',
                'metadata' => [
                    'sent_at' => now()->toIso8601String(),
                    'provider' => 'laravel_mail',
                    'mailer' => config('mail.default'),
                ],
            ]);

            Log::info('Loan application email sent successfully via Laravel Mail', [
                'message_id' => $message->id,
                'recipient' => $message->recipient,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Loan application email sending exception (Laravel Mail)', [
                'message_id' => $message->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Send contact message reply via Laravel Mail with branded template
     */
    protected function sendContactMessageReplyViaLaravelMail(ContactMessageReply $reply): bool
    {
        try {
            // Load the contact message relationship if not already loaded
            $reply->loadMissing('contactMessage');
            
            if (!$reply->contactMessage) {
                throw new \Exception('Contact message not found for reply');
            }

            $contactMessage = $reply->contactMessage;

            Log::info('Sending contact message reply via Laravel Mail', [
                'message_id' => $reply->id,
                'recipient' => $reply->recipient,
                'contact_message_id' => $contactMessage->id,
            ]);

            // Send email using Laravel Mail with branded template
            Mail::to($reply->recipient)->send(new ContactMessageReplyMail($contactMessage, $reply));

            // Update message status
            $reply->update([
                'status' => 'sent',
                'metadata' => [
                    'sent_at' => now()->toIso8601String(),
                    'provider' => 'laravel_mail',
                    'mailer' => config('mail.default'),
                ],
            ]);

            Log::info('Contact message reply sent successfully via Laravel Mail', [
                'message_id' => $reply->id,
                'recipient' => $reply->recipient,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Contact message reply sending exception (Laravel Mail)', [
                'message_id' => $reply->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Send job application email via Laravel Mail with branded template
     */
    protected function sendJobApplicationEmailViaLaravelMail(JobApplicationMessage $message): bool
    {
        try {
            // Load the job application relationship if not already loaded
            $message->loadMissing('jobApplication');
            
            if (!$message->jobApplication) {
                throw new \Exception('Job application not found for message');
            }

            $application = $message->jobApplication;

            Log::info('Sending job application email via Laravel Mail', [
                'message_id' => $message->id,
                'recipient' => $message->recipient,
                'application_id' => $application->id,
            ]);

            // Send email using Laravel Mail with branded template
            Mail::to($message->recipient)->send(new JobApplicationReply($application, $message));

            // Update message status
            $message->update([
                'status' => 'sent',
                'metadata' => [
                    'sent_at' => now()->toIso8601String(),
                    'provider' => 'laravel_mail',
                    'mailer' => config('mail.default'),
                ],
            ]);

            Log::info('Job application email sent successfully via Laravel Mail', [
                'message_id' => $message->id,
                'recipient' => $message->recipient,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Job application email sending exception (Laravel Mail)', [
                'message_id' => $message->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Extract name from email address (simple helper)
     */
    protected function extractNameFromEmail(string $email): string
    {
        $parts = explode('@', $email);
        $localPart = $parts[0] ?? '';
        
        // Try to extract name from email (e.g., "john.doe@example.com" -> "John Doe")
        $name = str_replace(['.', '_', '-'], ' ', $localPart);
        $name = ucwords($name);
        
        return $name ?: 'Valued Customer';
    }

    /**
     * Get email subject based on message type
     */
    protected function getEmailSubject(ContactMessageReply|JobApplicationMessage|LoanApplicationMessage $message): string
    {
        if ($message instanceof ContactMessageReply) {
            return 'Re: ' . ($message->contactMessage->subject ?? 'Your Inquiry');
        } elseif ($message instanceof JobApplicationMessage) {
            // Load relationships if not already loaded
            $message->loadMissing(['jobApplication.jobPost']);
            $jobTitle = optional($message->jobApplication)->jobPost->title ?? 'Your Application';
            return 'Re: Your Job Application - ' . $jobTitle;
        } elseif ($message instanceof LoanApplicationMessage) {
            $message->loadMissing('loanApplication');
            $loanType = optional($message->loanApplication)->loan_type ?? 'Loan';
            return 'Re: Your Loan Application - ' . $loanType;
        }
        return 'Message from Fortress Lenders';
    }


    /**
     * Send SMS message (generic method)
     */
    protected function sendSMSMessage(ContactMessageReply|JobApplicationMessage|LoanApplicationMessage $message): bool
    {
        if ($message instanceof ContactMessageReply) {
            return $this->sendSMS($message);
        } elseif ($message instanceof JobApplicationMessage) {
            return $this->sendSMSJobApplication($message);
        } else {
            return $this->sendSMSLoanApplication($message);
        }
    }

    /**
     * Send SMS via BulkSMS CRM API (ContactMessageReply)
     * API Documentation: https://crm.pradytecai.com/api-documentation
     */
    protected function sendSMS(ContactMessageReply $reply): bool
    {
        $apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
        $apiKey = config('services.bulksms.api_key');
        $clientId = config('services.bulksms.client_id');
        $senderId = config('services.bulksms.sender_id', 'FORTRESS');

        if (!$apiKey || !$clientId) {
            $missing = [];
            if (!$apiKey) $missing[] = 'BULKSMS_API_KEY';
            if (!$clientId) $missing[] = 'BULKSMS_CLIENT_ID';
            throw new \Exception('SMS API credentials not configured. Please add to .env: ' . implode(', ', $missing));
        }

        try {
            $phone = $this->formatPhoneNumber($reply->recipient);
            
            Log::info('Sending SMS', [
                'api_url' => $apiUrl,
                'phone' => $phone,
                'sender_id' => $senderId,
                'client_id' => $clientId,
                'message_length' => strlen($reply->message),
            ]);
            
            // BulkSMS CRM API request
            // Based on API documentation: https://crm.pradytecai.com/api-documentation
            // Endpoint format: /api/2/messages/send (API version 2, client_id in payload)
            $endpoint = "{$apiUrl}/2/messages/send";
            
            // Payload according to API documentation
            $payload = [
                'client_id' => (int) $clientId,
                'channel' => 'sms',
                'recipient' => $phone,
                'sender' => $senderId,
                'body' => $reply->message,
            ];
            
            Log::info('SMS API Request', [
                'endpoint' => $endpoint,
                'payload' => $payload,
                'api_key_set' => !empty($apiKey),
            ]);
            
            // API uses X-API-KEY header (not Bearer token)
            $httpClient = Http::timeout(30)->withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);
            
            // Disable SSL verification in local development only
            if (app()->environment('local')) {
                $httpClient = $httpClient->withoutVerifying();
            }
            
            $response = $httpClient->post($endpoint, $payload);
            
            Log::info('SMS API Response', [
                'status_code' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check API response status (API returns { "status": "success/error", ... })
                if (isset($responseData['status']) && $responseData['status'] === 'error') {
                    $errorMessage = $responseData['message'] ?? 'SMS API returned error status';
                    Log::error('SMS API returned error', [
                        'response' => $responseData,
                        'reply_id' => $reply->id,
                    ]);
                    throw new \Exception($errorMessage);
                }
                
                // Success - update reply status
                $reply->update([
                    'status' => 'sent',
                    'metadata' => array_merge($responseData['data'] ?? $responseData ?? [], [
                        'phone' => $phone,
                        'sender_id' => $senderId,
                        'sent_at' => now()->toIso8601String(),
                    ]),
                ]);
                Log::info('SMS sent successfully', [
                    'reply_id' => $reply->id,
                    'message_id' => $responseData['data']['id'] ?? null,
                ]);
                return true;
            } else {
                $statusCode = $response->status();
                $errorBody = $response->json() ?? $response->body();
                $errorMessage = is_array($errorBody) 
                    ? ($errorBody['message'] ?? ($errorBody['error'] ?? json_encode($errorBody)))
                    : ($errorBody ?? "SMS API request failed with status {$statusCode}");
                
                Log::error('SMS API failed', [
                    'status_code' => $statusCode,
                    'error_body' => $errorBody,
                    'reply_id' => $reply->id,
                ]);
                
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'reply_id' => $reply->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Send SMS via BulkSMS CRM API (JobApplicationMessage)
     */
    protected function sendSMSJobApplication(JobApplicationMessage $message): bool
    {
        $apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
        $apiKey = config('services.bulksms.api_key');
        $clientId = config('services.bulksms.client_id');
        $senderId = config('services.bulksms.sender_id', 'FORTRESS');

        if (!$apiKey || !$clientId) {
            $missing = [];
            if (!$apiKey) $missing[] = 'BULKSMS_API_KEY';
            if (!$clientId) $missing[] = 'BULKSMS_CLIENT_ID';
            throw new \Exception('SMS API credentials not configured. Please add to .env: ' . implode(', ', $missing));
        }

        try {
            $phone = $this->formatPhoneNumber($message->recipient);
            
            $endpoint = "{$apiUrl}/2/messages/send";
            
            $payload = [
                'client_id' => (int) $clientId,
                'channel' => 'sms',
                'recipient' => $phone,
                'sender' => $senderId,
                'body' => $message->message,
            ];
            
            $httpClient = Http::timeout(30)->withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);
            
            // Disable SSL verification in local development only
            if (app()->environment('local')) {
                $httpClient = $httpClient->withoutVerifying();
            }
            
            $response = $httpClient->post($endpoint, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['status']) && $responseData['status'] === 'error') {
                    $errorMessage = $responseData['message'] ?? 'SMS API returned error status';
                    throw new \Exception($errorMessage);
                }
                
                $message->update([
                    'status' => 'sent',
                    'metadata' => array_merge($responseData['data'] ?? $responseData ?? [], [
                        'phone' => $phone,
                        'sender_id' => $senderId,
                        'sent_at' => now()->toIso8601String(),
                    ]),
                ]);
                
                return true;
            } else {
                $statusCode = $response->status();
                $errorBody = $response->json() ?? $response->body();
                $errorMessage = is_array($errorBody) 
                    ? ($errorBody['message'] ?? ($errorBody['error'] ?? json_encode($errorBody)))
                    : ($errorBody ?? "SMS API request failed with status {$statusCode}");
                
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Send WhatsApp message (generic method)
     */
    protected function sendWhatsAppMessage(ContactMessageReply|JobApplicationMessage|LoanApplicationMessage $message): bool
    {
        if ($message instanceof ContactMessageReply) {
            return $this->sendWhatsApp($message);
        } elseif ($message instanceof JobApplicationMessage) {
            return $this->sendWhatsAppJobApplication($message);
        } else {
            return $this->sendWhatsAppLoanApplication($message);
        }
    }

    /**
     * Send WhatsApp message via UltraSMS API (ContactMessageReply)
     * API Documentation: Check UltraSMS documentation for endpoint format
     */
    protected function sendWhatsApp(ContactMessageReply $reply): bool
    {
        return $this->sendWhatsAppViaUltraSMS(
            $reply->recipient,
            $reply->message,
            $reply,
            'contact'
        );
    }

    /**
     * Send WhatsApp via UltraSMS API (JobApplicationMessage)
     */
    protected function sendWhatsAppJobApplication(JobApplicationMessage $message): bool
    {
        return $this->sendWhatsAppViaUltraSMS(
            $message->recipient,
            $message->message,
            $message,
            'job_application'
        );
    }

    /**
     * Send SMS via BulkSMS CRM API (LoanApplicationMessage)
     */
    protected function sendSMSLoanApplication(LoanApplicationMessage $message): bool
    {
        $apiUrl = config('services.bulksms.api_url', 'https://crm.pradytecai.com/api');
        $apiKey = config('services.bulksms.api_key');
        $clientId = config('services.bulksms.client_id');
        $senderId = config('services.bulksms.sender_id', 'FORTRESS');

        if (!$apiKey || !$clientId) {
            $missing = [];
            if (!$apiKey) $missing[] = 'BULKSMS_API_KEY';
            if (!$clientId) $missing[] = 'BULKSMS_CLIENT_ID';
            throw new \Exception('SMS API credentials not configured. Please add to .env: ' . implode(', ', $missing));
        }

        try {
            $phone = $this->formatPhoneNumber($message->recipient);
            
            $endpoint = "{$apiUrl}/2/messages/send";
            
            $payload = [
                'client_id' => (int) $clientId,
                'channel' => 'sms',
                'recipient' => $phone,
                'sender' => $senderId,
                'body' => $message->message,
            ];
            
            $httpClient = Http::timeout(30)->withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);
            
            // Disable SSL verification in local development only
            if (app()->environment('local')) {
                $httpClient = $httpClient->withoutVerifying();
            }
            
            $response = $httpClient->post($endpoint, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['status']) && $responseData['status'] === 'error') {
                    $errorMessage = $responseData['message'] ?? 'SMS API returned error status';
                    throw new \Exception($errorMessage);
                }
                
                $message->update([
                    'status' => 'sent',
                    'metadata' => array_merge($responseData['data'] ?? $responseData ?? [], [
                        'phone' => $phone,
                        'sender_id' => $senderId,
                        'sent_at' => now()->toIso8601String(),
                    ]),
                ]);
                
                return true;
            } else {
                $statusCode = $response->status();
                $errorBody = $response->json() ?? $response->body();
                $errorMessage = is_array($errorBody) 
                    ? ($errorBody['message'] ?? ($errorBody['error'] ?? json_encode($errorBody)))
                    : ($errorBody ?? "SMS API request failed with status {$statusCode}");
                
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Send WhatsApp via UltraSMS API (LoanApplicationMessage)
     */
    protected function sendWhatsAppLoanApplication(LoanApplicationMessage $message): bool
    {
        return $this->sendWhatsAppViaUltraSMS(
            $message->recipient,
            $message->message,
            $message,
            'loan_application'
        );
    }

    /**
     * Reusable method to send WhatsApp via UltraMSG API
     * API Documentation: https://docs.ultramsg.com
     * Endpoint format: https://api.ultramsg.com/{instance_id}/messages/chat
     */
    protected function sendWhatsAppViaUltraSMS(string $recipient, string $messageText, $messageModel, string $type = 'contact'): bool
    {
        $apiUrl = config('services.ultrasms.api_url', 'https://api.ultramsg.com');
        $instanceId = config('services.ultrasms.instance_id');
        $token = config('services.ultrasms.token');

        if (!$instanceId || !$token) {
            $missing = [];
            if (!$instanceId) $missing[] = 'ULTRASMS_INSTANCE_ID';
            if (!$token) $missing[] = 'ULTRASMS_TOKEN';
            throw new \Exception('WhatsApp (UltraMSG) API credentials not configured. Please add to .env: ' . implode(', ', $missing));
        }

        try {
            $phone = $this->formatPhoneNumber($recipient);
            
            Log::info('Sending WhatsApp via UltraMSG', [
                'api_url' => $apiUrl,
                'instance_id' => $instanceId,
                'phone' => $phone,
                'type' => $type,
            ]);
            
            // UltraMSG API endpoint format: /{instance_id}/messages/chat
            $endpoint = rtrim($apiUrl, '/') . '/' . $instanceId . '/messages/chat';
            
            // UltraMSG payload format according to documentation
            $payload = [
                'token' => $token,
                'to' => $phone,
                'body' => $messageText,
            ];
            
            $httpClient = Http::timeout(30)->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);
            
            // Disable SSL verification in local development only
            if (app()->environment('local')) {
                $httpClient = $httpClient->withoutVerifying();
            }
            
            Log::info('UltraMSG WhatsApp Request', [
                'endpoint' => $endpoint,
                'payload' => array_merge($payload, ['token' => '***']), // Hide token in logs
            ]);
            
            $response = $httpClient->post($endpoint, $payload);
            
            Log::info('UltraMSG WhatsApp Response', [
                'status_code' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check API response status (UltraMSG format)
                if (isset($responseData['error'])) {
                    $errorMessage = $responseData['error'] ?? 'WhatsApp API returned error';
                    Log::error('UltraMSG WhatsApp API returned error', [
                        'response' => $responseData,
                        'message_id' => $messageModel->id ?? null,
                    ]);
                    throw new \Exception($errorMessage);
                }
                
                // Check for success indicators
                if (isset($responseData['sent']) && $responseData['sent'] === false) {
                    $errorMessage = $responseData['error'] ?? $responseData['message'] ?? 'WhatsApp sending failed';
                    throw new \Exception($errorMessage);
                }
                
                $messageModel->update([
                    'status' => 'sent',
                    'metadata' => array_merge($responseData ?? [], [
                        'phone' => $phone,
                        'sent_at' => now()->toIso8601String(),
                        'provider' => 'ultramsg',
                        'instance_id' => $instanceId,
                    ]),
                ]);
                Log::info('WhatsApp sent successfully via UltraMSG', [
                    'message_id' => $messageModel->id ?? null,
                    'type' => $type,
                    'response' => $responseData,
                ]);
                return true;
            } else {
                $statusCode = $response->status();
                $errorBody = $response->json() ?? $response->body();
                $errorMessage = is_array($errorBody) 
                    ? ($errorBody['error'] ?? ($errorBody['message'] ?? json_encode($errorBody)))
                    : ($errorBody ?? "WhatsApp API request failed with status {$statusCode}");
                
                Log::error('UltraMSG WhatsApp API failed', [
                    'status_code' => $statusCode,
                    'error_body' => $errorBody,
                    'message_id' => $messageModel->id ?? null,
                ]);
                
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp sending exception (UltraMSG)', [
                'message_id' => $messageModel->id ?? null,
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Format phone number to international format
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If it starts with 0, replace with country code
        if (substr($phone, 0, 1) === '0') {
            $phone = '254' . substr($phone, 1);
        }

        // If it doesn't start with country code, add it
        if (substr($phone, 0, 3) !== '254') {
            $phone = '254' . $phone;
        }

        return $phone;
    }
}

