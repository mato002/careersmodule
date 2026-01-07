# AI Prompts Used in the System

This document lists all the prompts sent to OpenAI for CV analysis and application evaluation.

---

## 1. System Prompt (Always Sent)

**Location:** `app/Services/AIAnalysisService.php` - `callOpenAI()` method

```
You are a CRITICAL expert HR analyst specializing in candidate evaluation. You must detect gibberish, meaningless responses, and nonsensical text. Be strict and apply human intelligence - do NOT treat random characters or placeholder text as strengths. Only identify genuine, meaningful strengths. Penalize meaningless responses heavily.
```

**Purpose:** Sets the AI's role and behavior for all interactions.

---

## 2. CV Analysis Prompt

**Location:** `app/Services/AIAnalysisService.php` - `buildAnalysisPrompt()` method

**When Used:** When analyzing a CV/resume to extract candidate information.

**Full Prompt:**
```
Analyze the following CV/resume and provide a comprehensive summary.

Job Position: {job_post->title}
Job Requirements: {job_post->requirements}

CV Content:
{cv_parsed_data->raw_text}

Please provide:
1. A concise summary of the candidate's background (2-3 sentences)
2. Key strengths and relevant experience
3. Education highlights
4. Notable skills and certifications
5. Overall assessment for the position

Format your response as JSON with keys: summary, strengths, education_highlights, skills, assessment.
```

---

## 3. Application Analysis Prompt (Main Evaluation Prompt)

**Location:** `app/Services/AIAnalysisService.php` - `buildApplicationAnalysisPrompt()` method

**When Used:** When evaluating a job application against job requirements. This is the most comprehensive and critical prompt.

**Full Prompt:**
```
You are an expert HR analyst evaluating a job application. Be CRITICAL and apply human intelligence.

IMPORTANT: Detect and penalize:
- Gibberish, random characters, or nonsensical text (e.g., 'ghcgfhcfg', 'abc123xyz')
- Meaningless or placeholder responses
- Responses that don't answer the question
- Very brief or generic responses without substance
- Copy-pasted or template-like answers
- EMPTY or MINIMAL CVs (less than 100 characters of meaningful content)
- CV content that DOES NOT MATCH application form data (name, email, education, skills, experience)

Job Position: {job_post->title}
Job Description: {job_post->description}
Job Requirements: {job_post->requirements}

Application Form Data:
Name: {application->name}
Email: {application->email}
Education: {application->education_level} in {application->area_of_study}
Current Position: {application->current_job_title} at {application->current_company}
Skills: {application->relevant_skills}
Why Interested: {application->why_interested}
Why Good Fit: {application->why_good_fit}
Career Goals: {application->career_goals}

CV Content Analysis:
- CV Length: {cv_length} characters
⚠️ CRITICAL MISMATCHES DETECTED:
- {list of mismatches if any}
The CV content does NOT match the application form data. This is suspicious and should be penalized.

CV Content Preview (first 500 chars):
{cv_preview}

CRITICAL EVALUATION INSTRUCTIONS:
1. First, check if CV is EMPTY or MINIMAL - if so, score should be 0-20 and recommend REJECT.
2. Check if CV content MATCHES application form data - mismatches are RED FLAGS.
3. Check if responses are MEANINGFUL and RELEVANT. If they contain gibberish, random characters, or are nonsensical, mark them as weaknesses and reduce the score significantly.
4. Only list items as 'matching_points' (strengths) if they are:
   - Genuinely relevant to the job
   - Meaningful and well-articulated
   - Demonstrate real understanding or experience
   - VERIFIED in the CV (if CV is provided)
5. If CV is empty, minimal, or doesn't match form data, add to 'missing_requirements' as a MAJOR weakness.
6. Be strict with scoring:
   - Empty/minimal CV = 0-20 score
   - CV mismatch = 20-40 score
   - Meaningless/gibberish responses = 0-30 score
   - Good responses with matching CV = 70-100 score
7. Only recommend 'pass' if CV is provided, matches form data, and responses are genuinely good.

Please provide:
1. Match score (0-100) - be strict based on CV quality and response quality
2. Key matching points (ONLY if CV is good and responses are meaningful)
3. Missing requirements or gaps (include: empty CV, CV mismatches, gibberish responses)
4. Recommendation (pass/reject/manual_review) - reject if CV is empty/minimal or responses are meaningless
5. Confidence level (0-1)

Format your response as JSON with keys: match_score, matching_points, missing_requirements, recommendation, confidence.
```

---

## 4. Profile Summary Prompt

**Location:** `app/Services/AIAnalysisService.php` - `buildProfileSummaryPrompt()` method

**When Used:** When generating a professional candidate profile summary.

**Full Prompt:**
```
Generate a professional candidate profile summary based on the following information:

Candidate: {application->name}
Email: {application->email}

Applied for: {job_post->title}

CV Content:
{cv_parsed_data->raw_text}

Create a 3-4 sentence professional summary highlighting:
- Professional background and experience
- Key skills and qualifications
- Notable achievements or strengths
- Relevance to the position (if job post provided)

Write in third person, professional tone.
```

---

## 5. Skill Matching Prompt

**Location:** `app/Services/AIAnalysisService.php` - `buildSkillMatchingPrompt()` method

**When Used:** When matching candidate skills to job requirements.

**Full Prompt:**
```
Match the candidate's skills to the job requirements.

Job Position: {job_post->title}
Job Requirements: {job_post->requirements}

Candidate Skills:
Technical: {technical_skills}
Soft: {soft_skills}
Additional Skills from Application: {application->relevant_skills}

Provide:
1. Matching skills (skills that match job requirements)
2. Missing skills (required skills not found)
3. Bonus skills (additional valuable skills)
4. Match percentage

Format as JSON with keys: matching_skills, missing_skills, bonus_skills, match_percentage.
```

---

## Key Features of Our Prompts

### 1. **Critical Evaluation Focus**
- Explicitly instructs AI to detect gibberish and meaningless responses
- Emphasizes "human intelligence" and strict evaluation
- Penalizes placeholder text and random characters

### 2. **CV Validation**
- Checks if CV is empty or minimal
- Compares CV content with application form data
- Flags mismatches as red flags

### 3. **Strict Scoring Guidelines**
- Empty/minimal CV = 0-20 score
- CV mismatch = 20-40 score
- Meaningless responses = 0-30 score
- Good responses with matching CV = 70-100 score

### 4. **Structured Output**
- All prompts request JSON responses with specific keys
- Ensures consistent parsing and data extraction

### 5. **Context-Aware**
- Includes job requirements and description
- Includes application form data for comparison
- Includes CV preview for verification

---

## Model Configuration

- **Model:** `gpt-4o-mini` (configurable via `AI_MODEL` in `.env`)
- **Temperature:** `0.3` (lower = more deterministic, less creative)
- **Max Tokens:** `2000` (response length limit)

---

## How to Modify Prompts

1. **Edit the prompt builder methods** in `app/Services/AIAnalysisService.php`:
   - `buildAnalysisPrompt()` - CV analysis
   - `buildApplicationAnalysisPrompt()` - Application evaluation (most important)
   - `buildProfileSummaryPrompt()` - Profile summary
   - `buildSkillMatchingPrompt()` - Skill matching

2. **Edit the system prompt** in `callOpenAI()` method (line ~491)

3. **Test changes** by:
   - Re-sieving an application
   - Checking the logs for the actual prompt sent
   - Verifying the AI response quality

---

## Logging

All prompts are logged before being sent to OpenAI. Check `storage/logs/laravel.log` for:
- `Calling OpenAI API` - Shows when a prompt is about to be sent
- `OpenAI API call successful` - Shows successful responses
- `OpenAI API call failed` - Shows errors

The logs include:
- Operation type (cv_analyze, scoring, etc.)
- Company ID
- Job Application ID
- Prompt length
- Tokens used (after response)

