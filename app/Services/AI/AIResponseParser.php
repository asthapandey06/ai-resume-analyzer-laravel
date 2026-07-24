<?php

namespace App\Services\AI;

use App\Services\AI\DTO\AIResponse;
use App\Services\AI\DTO\ResumeAnalysisResult;
use InvalidArgumentException;
use JsonException;

class AIResponseParser
{
    /**
     * @throws JsonException
     */
    public function parse(AIResponse $response): ResumeAnalysisResult
    {
        $data = json_decode(
            $response->content,
            true,
            flags: JSON_THROW_ON_ERROR
        );

        $this->validate($data);

        return new ResumeAnalysisResult(
            score: (int) $data['score'],
            summary: $data['summary'],
            strengths: $data['strengths'],
            weaknesses: $data['weaknesses'],
            missingSkills: $data['missing_skills'],
            recommendedRoles: $data['recommended_roles'],
        );
    }

    private function validate(array $data): void
    {
        $requiredFields = [
            'score',
            'summary',
            'strengths',
            'weaknesses',
            'missing_skills',
            'recommended_roles',
        ];

        foreach ($requiredFields as $field) {
            if (! array_key_exists($field, $data)) {
                throw new InvalidArgumentException(
                    "Missing required field [{$field}] in AI response."
                );
            }
        }
    }
}