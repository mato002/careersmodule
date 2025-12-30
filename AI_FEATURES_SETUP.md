# AI-Enhanced Career Module Setup Guide

This document explains how to set up and use the AI-enhanced features for the Career Module.

## Features Overview

### 1. CV Parser & Data Extraction
- Extracts structured data from CV files (PDF, DOCX, DOC, TXT)
- Handles different layouts and formats
- Extracts: personal info, work experience, education, skills, certifications, languages, projects

### 2. CV Analysis & Summarization
- AI-powered CV analysis using LLM (OpenAI, Anthropic, or local)
- Generates candidate profile summaries
- Identifies key strengths and relevant experience
- Matches skills to job requirements

### 3. AI-Powered Application Processing
- Automatic application review and scoring
- Initial screening with AI recommendations
- Match candidate to job requirements
- Flag top candidates and identify concerns

### 4. Intelligent Sieving System
- Automated pass/fail decisions with confidence scores
- Ranking and scoring of candidates
- Recommendation reasons and explanations
- Blends rule-based and AI-powered evaluation

## Installation & Setup

### 1. Install Required Dependencies

#### For PDF Parsing (Optional but Recommended)
```bash
# Option 1: Install smalot/pdfparser via Composer
composer require smalot/pdfparser

# Option 2: Install poppler-utils (system package)
# Ubuntu/Debian:
sudo apt-get install poppler-utils

# macOS:
brew install poppler
```

#### For DOCX Parsing (Optional but Recommended)
```bash
# Install PhpOffice/PhpWord via Composer
composer require phpoffice/phpword
```

#### For DOC Parsing (Optional)
```bash
# Install antiword or catdoc (system packages)
# Ubuntu/Debian:
sudo apt-get install antiword

# macOS:
brew install antiword
```

### 2. Configure AI Service

Add the following to your `.env` file:

```env
# AI Provider (openai, anthropic, or local)
AI_PROVIDER=openai

# API Key (for OpenAI or Anthropic)
AI_API_KEY=your-api-key-here
# Or use existing:
OPENAI_API_KEY=your-openai-key

# Model to use
AI_MODEL=gpt-4o-mini

# For local LLM (e.g., Ollama)
AI_LOCAL_API_URL=http://localhost:11434/api/generate

# Feature toggles
AI_ENABLE_CV_PARSING=true
AI_ENABLE_AI_ANALYSIS=true
AI_ENABLE_AUTO_SIEVING=true

# Confidence thresholds
AI_MIN_CONFIDENCE_AUTO_PASS=0.85
AI_MIN_CONFIDENCE_AUTO_REJECT=0.80
```

### 3. Run Database Migrations

```bash
php artisan migrate
```

This will create the `cv_parsed_data` table for storing parsed CV information.

### 4. Configure Queue (for Async Processing)

Make sure your queue is configured and running:

```bash
# In .env
QUEUE_CONNECTION=database

# Run queue worker
php artisan queue:work
```

## Usage

### Automatic Processing

When a new job application is submitted:
1. CV is automatically queued for parsing
2. AI analysis is performed on the CV
3. Application is evaluated using AI-enhanced sieving
4. Results are stored in the database

### Manual Processing (Admin)

Admins can manually trigger processing from the job application detail page:

1. **Parse CV Only**: Extracts structured data from CV file
2. **Analyze with AI**: Generates AI summary and analysis
3. **Process CV and AI**: Full processing (parsing + AI analysis)

### Viewing Results

#### CV Parsed Data
- Access via `$application->cvParsedData`
- Contains structured information extracted from CV
- Includes: work experience, education, skills, certifications, etc.

#### AI Analysis
- Stored in `$application->ai_summary` and `$application->ai_details`
- AI sieving decision available via `$application->aiSievingDecision`
- Includes: match score, confidence, strengths, weaknesses, reasoning

## API Integration

### Supported AI Providers

#### OpenAI
```env
AI_PROVIDER=openai
AI_API_KEY=sk-...
AI_MODEL=gpt-4o-mini
```

#### Anthropic (Claude)
```env
AI_PROVIDER=anthropic
AI_API_KEY=sk-ant-...
AI_MODEL=claude-3-haiku-20240307
```

#### Local LLM (Ollama)
```env
AI_PROVIDER=local
AI_LOCAL_API_URL=http://localhost:11434/api/generate
AI_MODEL=llama2
```

## Code Examples

### Parse CV Programmatically

```php
use App\Services\CvParserService;
use App\Models\JobApplication;

$application = JobApplication::find($id);
$cvParser = new CvParserService();
$cvParsedData = $cvParser->parse($application);

// Access parsed data
$workExperience = $cvParsedData->parsed_work_experience;
$education = $cvParsedData->parsed_education;
$skills = $cvParsedData->parsed_skills;
```

### Analyze with AI Programmatically

```php
use App\Services\AIAnalysisService;
use App\Models\JobApplication;

$application = JobApplication::find($id);
$aiAnalysis = new AIAnalysisService();

// Analyze CV
$analysis = $aiAnalysis->analyzeCv($application);

// Generate profile summary
$summary = $aiAnalysis->generateProfileSummary($application);

// Match skills to job
$skillMatch = $aiAnalysis->matchSkillsToJob($application);
```

### Enhanced Sieving

```php
use App\Services\AISievingService;
use App\Models\JobApplication;

$application = JobApplication::find($id);
$sievingService = new AISievingService();
$decision = $sievingService->evaluate($application);

// Access decision details
$score = $decision->ai_score;
$confidence = $decision->ai_confidence;
$recommendation = $decision->ai_decision;
$reasoning = $decision->ai_reasoning;
```

## Troubleshooting

### CV Parsing Issues

1. **No text extracted from PDF**
   - Install `smalot/pdfparser` or `poppler-utils`
   - Check file permissions
   - Verify CV file is not corrupted

2. **DOCX parsing fails**
   - Install `phpoffice/phpword`
   - Check file format compatibility

3. **Low parsing confidence**
   - CV format may be non-standard
   - Consider manual review
   - Parsed data may still be useful even with lower confidence

### AI Analysis Issues

1. **API errors**
   - Verify API key is correct
   - Check API quota/limits
   - Ensure internet connectivity

2. **Timeout errors**
   - Increase timeout in HTTP client
   - Use async processing (queue jobs)
   - Consider using faster models

3. **High costs**
   - Use smaller models (e.g., `gpt-4o-mini` instead of `gpt-4`)
   - Enable caching for repeated analyses
   - Consider local LLM for development

### Queue Processing Issues

1. **Jobs not processing**
   - Ensure queue worker is running: `php artisan queue:work`
   - Check queue connection in `.env`
   - Review failed jobs: `php artisan queue:failed`

2. **Jobs failing**
   - Check logs: `storage/logs/laravel.log`
   - Verify dependencies are installed
   - Ensure database migrations are run

## Best Practices

1. **Use Async Processing**: Always use queue jobs for CV processing to avoid timeouts
2. **Monitor API Usage**: Track API calls and costs
3. **Set Appropriate Thresholds**: Adjust confidence thresholds based on your needs
4. **Review AI Decisions**: Always allow human override for important decisions
5. **Cache Results**: Cache AI analysis results to avoid redundant API calls
6. **Error Handling**: Implement proper error handling and fallbacks

## Configuration Reference

See `config/ai.php` for all available configuration options.

## Support

For issues or questions:
1. Check logs: `storage/logs/laravel.log`
2. Review this documentation
3. Check AI service status and API keys
4. Verify all dependencies are installed


