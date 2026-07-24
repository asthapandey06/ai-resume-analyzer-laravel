<?php
namespace App\Services\AI\DTO;

final readonly class ResumeAnalysisResult
{
    public function __construct(
        public int $score,
        public string $summary,
        public array $strengths,
        public array $weaknesses,
        public array $missingSkills,
        public array $recommendedRoles,
    ) {}
}