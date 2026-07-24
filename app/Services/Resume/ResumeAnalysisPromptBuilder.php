<?php

namespace App\Services\Resume;

use App\Models\Resume;

class ResumeAnalysisPromptBuilder
{
    public function build(Resume $resume): Prompt
    {
        return new Prompt(
            system: $this->system(),
            user: implode("\n\n", [
                $this->task(),
                $this->output(),
                $this->resume($resume),
            ]),
        );
    }

    private function system(): string
    {
        return <<<PROMPT
        You are an experienced technical recruiter and ATS evaluator.

        Analyze resumes objectively.

        Do not invent information.

        Base your analysis only on the information provided in the resume.
        PROMPT;
    }

    private function task(): string
    {
        return <<<PROMPT
Analyze this resume and evaluate:

- Overall quality
- Technical skills
- Work experience
- Projects
- Education
- ATS compatibility

Provide constructive feedback.
PROMPT;
    }

    private function output(): string
    {
        return <<<PROMPT
Return ONLY valid JSON.

Do not include markdown.

Return exactly this structure:

{
    "score": number,
    "summary": string,
    "strengths": [],
    "weaknesses": [],
    "missing_skills": [],
    "recommended_roles": []
}
PROMPT;
    }

    private function resume(Resume $resume): string
    {
        return <<<PROMPT
Resume:

{$resume->extracted_text}
PROMPT;
    }
}
