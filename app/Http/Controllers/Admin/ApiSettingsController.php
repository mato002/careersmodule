<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApiSettingsController extends Controller
{
    public function edit()
    {
        // Get configured APIs from config/services.php
        $configuredApis = $this->getConfiguredApis();
        
        // Get database stored settings (for custom APIs)
        $dbSettings = ApiSetting::query()->latest()->first() ?? new ApiSetting();

        return view('admin.api.edit', compact('configuredApis', 'dbSettings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_name' => ['nullable', 'string', 'max:255'],
            'api_key' => ['nullable', 'string'],
            'api_secret' => ['nullable', 'string'],
            'api_endpoint' => ['nullable', 'url', 'max:500'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $settings = ApiSetting::query()->latest()->first() ?? new ApiSetting();
        $settings->fill($validated);
        $settings->save();

        return redirect()
            ->route('admin.api.edit')
            ->with('status', 'API settings updated successfully.');
    }

    /**
     * Get all configured APIs from config/services.php
     */
    protected function getConfiguredApis(): array
    {
        $apis = [];

        // BulkSMS Configuration
        $bulksmsConfig = config('services.bulksms', []);
        $apis['bulksms'] = [
            'name' => 'BulkSMS (SMS Service)',
            'type' => 'SMS',
            'status' => $this->getApiStatus($bulksmsConfig, ['api_key', 'client_id']),
            'config' => [
                'API URL' => $bulksmsConfig['api_url'] ?? 'Not set',
                'API Key' => $this->maskValue($bulksmsConfig['api_key'] ?? null),
                'Client ID' => $bulksmsConfig['client_id'] ?? 'Not set',
                'Sender ID' => $bulksmsConfig['sender_id'] ?? 'Not set',
            ],
            'env_vars' => [
                'BULKSMS_API_URL',
                'BULKSMS_API_KEY',
                'BULKSMS_CLIENT_ID',
                'BULKSMS_SENDER_ID',
            ],
        ];

        // UltraSMS/WhatsApp Configuration
        $ultrasmsConfig = config('services.ultrasms', []);
        $apis['ultrasms'] = [
            'name' => 'UltraMSG (WhatsApp Service)',
            'type' => 'WhatsApp',
            'status' => $this->getApiStatus($ultrasmsConfig, ['instance_id', 'token']),
            'config' => [
                'API URL' => $ultrasmsConfig['api_url'] ?? 'Not set',
                'Instance ID' => $ultrasmsConfig['instance_id'] ?? 'Not set',
                'Token' => $this->maskValue($ultrasmsConfig['token'] ?? null),
            ],
            'env_vars' => [
                'ULTRASMS_API_URL',
                'ULTRASMS_INSTANCE_ID',
                'ULTRASMS_TOKEN',
            ],
        ];

        // EmailJS Configuration
        $emailjsConfig = config('services.emailjs', []);
        $apis['emailjs'] = [
            'name' => 'EmailJS (Email Service)',
            'type' => 'Email',
            'status' => $this->getApiStatus($emailjsConfig, ['service_id', 'public_key']),
            'config' => [
                'API URL' => $emailjsConfig['api_url'] ?? 'Not set',
                'Service ID' => $emailjsConfig['service_id'] ?? 'Not set',
                'Template ID' => $emailjsConfig['template_id'] ?? 'Not set',
                'Public Key' => $this->maskValue($emailjsConfig['public_key'] ?? null),
                'User ID' => $emailjsConfig['user_id'] ?? 'Not set',
            ],
            'env_vars' => [
                'EMAILJS_API_URL',
                'EMAILJS_SERVICE_ID',
                'EMAILJS_TEMPLATE_ID',
                'EMAILJS_PUBLIC_KEY',
                'EMAILJS_USER_ID',
            ],
        ];

        // Mail Configuration
        $mailConfig = config('mail', []);
        $mailer = $mailConfig['default'] ?? 'log';
        $mailerConfig = $mailConfig['mailers'][$mailer] ?? [];
        
        $apis['mail'] = [
            'name' => 'Laravel Mail (' . ucfirst($mailer) . ')',
            'type' => 'Email',
            'status' => $this->getMailStatus($mailer, $mailerConfig),
            'config' => $this->getMailConfig($mailer, $mailerConfig),
            'env_vars' => $this->getMailEnvVars($mailer),
        ];

        return $apis;
    }

    /**
     * Get API status (configured/not configured)
     */
    protected function getApiStatus(array $config, array $requiredKeys): string
    {
        foreach ($requiredKeys as $key) {
            if (empty($config[$key])) {
                return 'not_configured';
            }
        }
        return 'configured';
    }

    /**
     * Get Mail API status
     */
    protected function getMailStatus(string $mailer, array $config): string
    {
        if ($mailer === 'log' || $mailer === 'array') {
            return 'development';
        }

        if ($mailer === 'smtp') {
            $required = ['host', 'port', 'username', 'password'];
            foreach ($required as $key) {
                if (empty($config[$key])) {
                    return 'not_configured';
                }
            }
        }

        return 'configured';
    }

    /**
     * Get Mail configuration details
     */
    protected function getMailConfig(string $mailer, array $config): array
    {
        $mailConfig = [
            'Mailer' => ucfirst($mailer),
        ];

        if ($mailer === 'smtp') {
            $mailConfig['Host'] = $config['host'] ?? 'Not set';
            $mailConfig['Port'] = $config['port'] ?? 'Not set';
            $mailConfig['Username'] = $config['username'] ?? 'Not set';
            $mailConfig['Password'] = $this->maskValue($config['password'] ?? null);
            $mailConfig['Encryption'] = $config['scheme'] ?? 'Not set';
        } elseif ($mailer === 'mailgun') {
            $mailConfig['Domain'] = config('services.mailgun.domain') ?? 'Not set';
            $mailConfig['Secret'] = $this->maskValue(config('services.mailgun.secret'));
        } elseif ($mailer === 'ses') {
            $mailConfig['Region'] = config('services.ses.region') ?? 'Not set';
            $mailConfig['Key'] = $this->maskValue(config('services.ses.key'));
        }

        return $mailConfig;
    }

    /**
     * Get Mail environment variables
     */
    protected function getMailEnvVars(string $mailer): array
    {
        $vars = ['MAIL_MAILER'];

        if ($mailer === 'smtp') {
            $vars = array_merge($vars, ['MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_ENCRYPTION']);
        } elseif ($mailer === 'mailgun') {
            $vars = array_merge($vars, ['MAILGUN_DOMAIN', 'MAILGUN_SECRET']);
        } elseif ($mailer === 'ses') {
            $vars = array_merge($vars, ['AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY', 'AWS_DEFAULT_REGION']);
        }

        return $vars;
    }

    /**
     * Mask sensitive values for display
     */
    protected function maskValue(?string $value): string
    {
        if (!$value) {
            return 'Not set';
        }

        if (strlen($value) <= 8) {
            return str_repeat('*', strlen($value));
        }

        return substr($value, 0, 4) . str_repeat('*', strlen($value) - 8) . substr($value, -4);
    }
}


