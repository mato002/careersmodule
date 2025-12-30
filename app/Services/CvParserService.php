<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\CvParsedData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CvParserService
{
    /**
     * Parse CV file and extract structured data
     */
    public function parse(JobApplication $application): ?CvParsedData
    {
        if (!$application->cv_path) {
            Log::warning('No CV path found for application', ['application_id' => $application->id]);
            return null;
        }

        $filePath = storage_path('app/public/' . $application->cv_path);
        
        if (!file_exists($filePath)) {
            Log::error('CV file not found', [
                'application_id' => $application->id,
                'cv_path' => $application->cv_path
            ]);
            return null;
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        try {
            $rawText = $this->extractText($filePath, $extension);
            
            if (empty($rawText)) {
                Log::warning('No text extracted from CV', ['application_id' => $application->id]);
                return null;
            }

            // Parse structured data from raw text
            $parsedData = $this->extractStructuredData($rawText, $application);
            
            // Store parsed data
            $cvParsedData = CvParsedData::updateOrCreate(
                ['job_application_id' => $application->id],
                array_merge($parsedData, [
                    'raw_text' => $rawText,
                    'parser_version' => '1.0',
                    'parsing_confidence' => $this->calculateConfidence($parsedData),
                    'parsed_at' => now(),
                ])
            );

            return $cvParsedData;
        } catch (\Exception $e) {
            Log::error('CV parsing failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Extract text from CV file based on extension
     */
    private function extractText(string $filePath, string $extension): string
    {
        return match($extension) {
            'pdf' => $this->extractFromPdf($filePath),
            'docx' => $this->extractFromDocx($filePath),
            'doc' => $this->extractFromDoc($filePath),
            'txt' => file_get_contents($filePath),
            default => throw new \Exception("Unsupported file format: {$extension}"),
        };
    }

    /**
     * Extract text from PDF
     */
    private function extractFromPdf(string $filePath): string
    {
        // Try using smalot/pdfparser if available
        if (class_exists('\Smalot\PdfParser\Parser')) {
            try {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($filePath);
                return $pdf->getText();
            } catch (\Exception $e) {
                Log::warning('PDF parsing with smalot/pdfparser failed, trying alternative', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Fallback: Use pdftotext command if available (requires poppler-utils)
        if (shell_exec('which pdftotext')) {
            $output = shell_exec("pdftotext -layout \"{$filePath}\" -");
            if ($output) {
                return $output;
            }
        }

        // Last resort: Return empty string (could integrate with external API)
        Log::warning('PDF text extraction not available. Install smalot/pdfparser or poppler-utils.');
        return '';
    }

    /**
     * Extract text from DOCX
     */
    private function extractFromDocx(string $filePath): string
    {
        // Use PhpOffice/PhpWord if available
        if (class_exists('\PhpOffice\PhpWord\IOFactory')) {
            try {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
                return $text;
            } catch (\Exception $e) {
                Log::warning('DOCX parsing with PhpWord failed', ['error' => $e->getMessage()]);
            }
        }

        // Fallback: Try using antiword or catdoc if available
        if (shell_exec('which antiword')) {
            $output = shell_exec("antiword \"{$filePath}\"");
            if ($output) {
                return $output;
            }
        }

        Log::warning('DOCX text extraction not available. Install PhpOffice/PhpWord.');
        return '';
    }

    /**
     * Extract text from DOC (old Word format)
     */
    private function extractFromDoc(string $filePath): string
    {
        // Try antiword or catdoc
        if (shell_exec('which antiword')) {
            $output = shell_exec("antiword \"{$filePath}\"");
            if ($output) {
                return $output;
            }
        }

        if (shell_exec('which catdoc')) {
            $output = shell_exec("catdoc \"{$filePath}\"");
            if ($output) {
                return $output;
            }
        }

        Log::warning('DOC text extraction not available. Install antiword or catdoc.');
        return '';
    }

    /**
     * Extract structured data from raw text
     */
    private function extractStructuredData(string $rawText, JobApplication $application): array
    {
        $data = [
            'parsed_name' => $this->extractName($rawText, $application),
            'parsed_email' => $this->extractEmail($rawText, $application),
            'parsed_phone' => $this->extractPhone($rawText, $application),
            'parsed_address' => $this->extractAddress($rawText),
            'parsed_work_experience' => $this->extractWorkExperience($rawText),
            'parsed_education' => $this->extractEducation($rawText),
            'parsed_skills' => $this->extractSkills($rawText),
            'parsed_certifications' => $this->extractCertifications($rawText),
            'parsed_languages' => $this->extractLanguages($rawText),
            'parsed_projects' => $this->extractProjects($rawText),
        ];

        return $data;
    }

    /**
     * Extract name from text
     */
    private function extractName(string $text, JobApplication $application): ?string
    {
        // Use application name as fallback
        if ($application->name) {
            return $application->name;
        }

        // Try to extract from first line (common CV format)
        $lines = explode("\n", trim($text));
        if (!empty($lines[0]) && strlen($lines[0]) < 100) {
            $firstLine = trim($lines[0]);
            // Check if it looks like a name (contains letters, spaces, maybe hyphens/apostrophes)
            if (preg_match('/^[A-Za-z\s\-\']+$/', $firstLine)) {
                return $firstLine;
            }
        }

        return null;
    }

    /**
     * Extract email from text
     */
    private function extractEmail(string $text, JobApplication $application): ?string
    {
        // Use application email as fallback
        if ($application->email) {
            return $application->email;
        }

        // Extract email pattern
        if (preg_match('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', $text, $matches)) {
            return $matches[0];
        }

        return null;
    }

    /**
     * Extract phone from text
     */
    private function extractPhone(string $text, JobApplication $application): ?string
    {
        // Use application phone as fallback
        if ($application->phone) {
            return $application->phone;
        }

        // Extract phone patterns (various formats)
        $patterns = [
            '/\+?[1-9]\d{1,14}/', // International format
            '/\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/', // US format
            '/\d{10,15}/', // Generic long number
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return $matches[0];
            }
        }

        return null;
    }

    /**
     * Extract address from text
     */
    private function extractAddress(string $text): ?string
    {
        // Look for address patterns (street, city, country, postal code)
        $lines = explode("\n", $text);
        $addressLines = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            // Check if line contains address indicators
            if (preg_match('/\b(street|avenue|road|rd|ave|st|drive|dr|lane|ln|boulevard|blvd|city|state|country|zip|postal|p\.?o\.?\s*box)\b/i', $line)) {
                $addressLines[] = $line;
            } elseif (preg_match('/\d+\s+[A-Za-z\s]+(?:street|avenue|road|rd|ave|st|drive|dr|lane|ln)/i', $line)) {
                $addressLines[] = $line;
            }
        }

        return !empty($addressLines) ? implode(', ', $addressLines) : null;
    }

    /**
     * Extract work experience
     */
    private function extractWorkExperience(string $text): array
    {
        $experiences = [];
        
        // Look for work experience section
        $sections = $this->splitIntoSections($text);
        
        foreach ($sections as $section) {
            if (preg_match('/\b(experience|employment|work history|career|professional experience|employment history)\b/i', $section['title'])) {
                // Extract individual experiences
                $expMatches = preg_split('/\n(?=\d{4}|\w+\s+\d{4}|[A-Z][a-z]+\s+\d{4})/i', $section['content']);
                
                foreach ($expMatches as $exp) {
                    $experience = $this->parseExperienceEntry($exp);
                    if ($experience) {
                        $experiences[] = $experience;
                    }
                }
            }
        }

        return $experiences;
    }

    /**
     * Parse individual experience entry
     */
    private function parseExperienceEntry(string $text): ?array
    {
        $lines = array_filter(array_map('trim', explode("\n", $text)));
        
        if (empty($lines)) {
            return null;
        }

        $experience = [
            'role' => null,
            'company' => null,
            'start_date' => null,
            'end_date' => null,
            'description' => null,
            'location' => null,
        ];

        // First line often contains role and company
        $firstLine = $lines[0];
        if (preg_match('/^(.+?)\s+[-–—]\s+(.+)$/', $firstLine, $matches)) {
            $experience['role'] = trim($matches[1]);
            $experience['company'] = trim($matches[2]);
        } elseif (preg_match('/^(.+?)\s+at\s+(.+)$/i', $firstLine, $matches)) {
            $experience['role'] = trim($matches[1]);
            $experience['company'] = trim($matches[2]);
        } else {
            $experience['role'] = $firstLine;
        }

        // Extract dates (various formats)
        if (preg_match('/(\w+\s+\d{4}|\d{4})\s*[-–—]?\s*(\w+\s+\d{4}|\d{4}|present|current)/i', $text, $dateMatches)) {
            $experience['start_date'] = $dateMatches[1];
            $experience['end_date'] = strtolower($dateMatches[2]) === 'present' || strtolower($dateMatches[2]) === 'current' ? 'Present' : $dateMatches[2];
        } elseif (preg_match('/(\d{4})\s*[-–—]?\s*(\d{4}|present|current)/i', $text, $dateMatches)) {
            $experience['start_date'] = $dateMatches[1];
            $experience['end_date'] = strtolower($dateMatches[2]) === 'present' || strtolower($dateMatches[2]) === 'current' ? 'Present' : $dateMatches[2];
        }

        // Extract location
        if (preg_match('/\b([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*),\s*([A-Z]{2}|[A-Z][a-z]+)\b/', $text, $locationMatches)) {
            $experience['location'] = $locationMatches[0];
        }

        // Description is remaining text
        $descriptionLines = array_slice($lines, 1);
        $experience['description'] = !empty($descriptionLines) ? implode("\n", $descriptionLines) : null;

        return $experience;
    }

    /**
     * Extract education
     */
    private function extractEducation(string $text): array
    {
        $education = [];
        
        $sections = $this->splitIntoSections($text);
        
        foreach ($sections as $section) {
            if (preg_match('/\b(education|academic|qualification|degree)\b/i', $section['title'])) {
                $eduMatches = preg_split('/\n(?=\d{4}|\w+\s+\d{4})/i', $section['content']);
                
                foreach ($eduMatches as $edu) {
                    $eduEntry = $this->parseEducationEntry($edu);
                    if ($eduEntry) {
                        $education[] = $eduEntry;
                    }
                }
            }
        }

        return $education;
    }

    /**
     * Parse individual education entry
     */
    private function parseEducationEntry(string $text): ?array
    {
        $lines = array_filter(array_map('trim', explode("\n", $text)));
        
        if (empty($lines)) {
            return null;
        }

        $education = [
            'institution' => null,
            'degree' => null,
            'field' => null,
            'start_date' => null,
            'end_date' => null,
            'grade' => null,
            'status' => null,
        ];

        // First line often contains degree and field
        $firstLine = $lines[0];
        if (preg_match('/\b(Bachelor|Master|PhD|Doctorate|Diploma|Certificate|Associate)\b/i', $firstLine, $degreeMatches)) {
            $education['degree'] = $degreeMatches[0];
            
            // Extract field
            if (preg_match('/\b(in|of)\s+(.+?)(?:,|\n|$)/i', $firstLine, $fieldMatches)) {
                $education['field'] = trim($fieldMatches[2]);
            }
        }

        // Extract institution (often second line or in parentheses)
        if (isset($lines[1])) {
            $education['institution'] = $lines[1];
        }

        // Extract dates
        if (preg_match('/(\d{4})\s*[-–—]?\s*(\d{4}|present|current)/i', $text, $dateMatches)) {
            $education['start_date'] = $dateMatches[1];
            $education['end_date'] = strtolower($dateMatches[2]) === 'present' || strtolower($dateMatches[2]) === 'current' ? 'Present' : $dateMatches[2];
        }

        // Extract grade/GPA
        if (preg_match('/\b(GPA|CGPA|Grade|GPA:)\s*([0-9.]+)/i', $text, $gradeMatches)) {
            $education['grade'] = $gradeMatches[2];
        }

        return $education;
    }

    /**
     * Extract skills
     */
    private function extractSkills(string $text): array
    {
        $skills = [
            'technical' => [],
            'soft' => [],
        ];

        $sections = $this->splitIntoSections($text);
        
        foreach ($sections as $section) {
            if (preg_match('/\b(skills|technical skills|competencies|proficiencies)\b/i', $section['title'])) {
                $content = $section['content'];
                
                // Common technical skills
                $technicalKeywords = [
                    'programming', 'software', 'language', 'framework', 'database', 'tool',
                    'python', 'java', 'javascript', 'php', 'sql', 'html', 'css', 'react',
                    'laravel', 'node', 'git', 'docker', 'aws', 'linux', 'windows', 'excel',
                    'word', 'powerpoint', 'quickbooks', 'sap', 'oracle', 'mysql', 'postgresql'
                ];
                
                // Common soft skills
                $softKeywords = [
                    'communication', 'leadership', 'teamwork', 'problem solving', 'analytical',
                    'creative', 'time management', 'organization', 'adaptability', 'collaboration'
                ];

                // Extract skills (comma-separated, bullet points, etc.)
                $skillMatches = preg_split('/[,•\-\n]/', $content);
                
                foreach ($skillMatches as $skill) {
                    $skill = trim($skill);
                    if (empty($skill) || strlen($skill) < 2) {
                        continue;
                    }

                    $isTechnical = false;
                    foreach ($technicalKeywords as $keyword) {
                        if (stripos($skill, $keyword) !== false || stripos($text, $keyword) !== false) {
                            $isTechnical = true;
                            break;
                        }
                    }

                    if ($isTechnical) {
                        $skills['technical'][] = $skill;
                    } else {
                        $isSoft = false;
                        foreach ($softKeywords as $keyword) {
                            if (stripos($skill, $keyword) !== false) {
                                $isSoft = true;
                                break;
                            }
                        }
                        if ($isSoft || empty($skills['technical'])) {
                            $skills['soft'][] = $skill;
                        }
                    }
                }
            }
        }

        // Remove duplicates
        $skills['technical'] = array_unique($skills['technical']);
        $skills['soft'] = array_unique($skills['soft']);

        return $skills;
    }

    /**
     * Extract certifications
     */
    private function extractCertifications(string $text): array
    {
        $certifications = [];
        
        $sections = $this->splitIntoSections($text);
        
        foreach ($sections as $section) {
            if (preg_match('/\b(certification|certificate|license|credential)\b/i', $section['title'])) {
                $certMatches = preg_split('/\n(?=[A-Z])/', $section['content']);
                
                foreach ($certMatches as $cert) {
                    $certEntry = $this->parseCertificationEntry($cert);
                    if ($certEntry) {
                        $certifications[] = $certEntry;
                    }
                }
            }
        }

        return $certifications;
    }

    /**
     * Parse individual certification entry
     */
    private function parseCertificationEntry(string $text): ?array
    {
        $text = trim($text);
        if (empty($text)) {
            return null;
        }

        $certification = [
            'name' => $text,
            'issuer' => null,
            'date' => null,
            'expiry' => null,
        ];

        // Extract issuer (often in parentheses or after dash)
        if (preg_match('/\((.+?)\)/', $text, $matches)) {
            $certification['issuer'] = $matches[1];
            $certification['name'] = trim(str_replace('(' . $matches[1] . ')', '', $text));
        }

        // Extract date
        if (preg_match('/(\w+\s+\d{4}|\d{4})/', $text, $dateMatches)) {
            $certification['date'] = $dateMatches[1];
        }

        return $certification;
    }

    /**
     * Extract languages
     */
    private function extractLanguages(string $text): array
    {
        $languages = [];
        
        $sections = $this->splitIntoSections($text);
        
        foreach ($sections as $section) {
            if (preg_match('/\b(language|languages)\b/i', $section['title'])) {
                $langMatches = preg_split('/[,•\-\n]/', $section['content']);
                
                foreach ($langMatches as $lang) {
                    $langEntry = $this->parseLanguageEntry(trim($lang));
                    if ($langEntry) {
                        $languages[] = $langEntry;
                    }
                }
            }
        }

        return $languages;
    }

    /**
     * Parse individual language entry
     */
    private function parseLanguageEntry(string $text): ?array
    {
        $text = trim($text);
        if (empty($text)) {
            return null;
        }

        $language = [
            'language' => $text,
            'proficiency' => null,
        ];

        // Extract proficiency level
        $proficiencyLevels = ['native', 'fluent', 'proficient', 'intermediate', 'basic', 'beginner'];
        foreach ($proficiencyLevels as $level) {
            if (stripos($text, $level) !== false) {
                $language['proficiency'] = ucfirst($level);
                $language['language'] = trim(str_ireplace($level, '', $text));
                break;
            }
        }

        return $language;
    }

    /**
     * Extract projects/portfolio
     */
    private function extractProjects(string $text): array
    {
        $projects = [];
        
        $sections = $this->splitIntoSections($text);
        
        foreach ($sections as $section) {
            if (preg_match('/\b(project|portfolio|publication|publications)\b/i', $section['title'])) {
                $projectMatches = preg_split('/\n(?=[A-Z])/', $section['content']);
                
                foreach ($projectMatches as $project) {
                    $projectEntry = $this->parseProjectEntry($project);
                    if ($projectEntry) {
                        $projects[] = $projectEntry;
                    }
                }
            }
        }

        // Also look for URLs (GitHub, portfolio links, etc.)
        if (preg_match_all('/\b(https?:\/\/[^\s]+)/i', $text, $urlMatches)) {
            foreach ($urlMatches[1] as $url) {
                $projects[] = [
                    'name' => 'Portfolio Link',
                    'description' => null,
                    'url' => $url,
                    'technologies' => [],
                ];
            }
        }

        return $projects;
    }

    /**
     * Parse individual project entry
     */
    private function parseProjectEntry(string $text): ?array
    {
        $lines = array_filter(array_map('trim', explode("\n", $text)));
        
        if (empty($lines)) {
            return null;
        }

        $project = [
            'name' => $lines[0],
            'description' => isset($lines[1]) ? implode("\n", array_slice($lines, 1)) : null,
            'url' => null,
            'technologies' => [],
        ];

        // Extract URL
        if (preg_match('/\b(https?:\/\/[^\s]+)/i', $text, $urlMatches)) {
            $project['url'] = $urlMatches[1];
        }

        return $project;
    }

    /**
     * Split text into sections
     */
    private function splitIntoSections(string $text): array
    {
        $sections = [];
        $lines = explode("\n", $text);
        $currentSection = null;
        $currentContent = [];

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Check if line is a section header (all caps, bold indicators, etc.)
            if (preg_match('/^[A-Z][A-Z\s]{3,}$/', $line) || 
                preg_match('/^[A-Z][a-z]+(?:\s+[A-Z][a-z]+)*$/', $line) && strlen($line) < 50) {
                
                // Save previous section
                if ($currentSection !== null) {
                    $sections[] = [
                        'title' => $currentSection,
                        'content' => implode("\n", $currentContent),
                    ];
                }
                
                // Start new section
                $currentSection = $line;
                $currentContent = [];
            } else {
                $currentContent[] = $line;
            }
        }

        // Save last section
        if ($currentSection !== null) {
            $sections[] = [
                'title' => $currentSection,
                'content' => implode("\n", $currentContent),
            ];
        }

        return $sections;
    }

    /**
     * Calculate parsing confidence
     */
    private function calculateConfidence(array $parsedData): float
    {
        $confidence = 0.0;
        $factors = 0;

        if (!empty($parsedData['parsed_name'])) {
            $confidence += 0.15;
            $factors++;
        }
        if (!empty($parsedData['parsed_email'])) {
            $confidence += 0.15;
            $factors++;
        }
        if (!empty($parsedData['parsed_phone'])) {
            $confidence += 0.10;
            $factors++;
        }
        if (!empty($parsedData['parsed_work_experience']) && count($parsedData['parsed_work_experience']) > 0) {
            $confidence += 0.25;
            $factors++;
        }
        if (!empty($parsedData['parsed_education']) && count($parsedData['parsed_education']) > 0) {
            $confidence += 0.20;
            $factors++;
        }
        if (!empty($parsedData['parsed_skills'])) {
            $confidence += 0.15;
            $factors++;
        }

        // Normalize to 0-1 range
        return min(1.0, $confidence);
    }
}


