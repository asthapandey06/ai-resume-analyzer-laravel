<?php

namespace App\Services\Resume;

use App\Models\Resume;
use App\Models\ResumeAnalysis;
use App\Services\AI\AIManager;
use App\Services\AI\AIResponseParser;
use App\Services\AI\DTO\AIRequest;
use App\Services\AI\DTO\AIResponse;
use App\Services\AI\DTO\ResumeAnalysisResult;

class ResumeAnalysisService
{
    public function __construct(
        private readonly AIManager $aiManager,
        private readonly ResumeAnalysisPromptBuilder $promptBuilder,
        private readonly AIResponseParser $responseParser,
    ) {}

    public function analyze(Resume $resume): ResumeAnalysis
    {
        $request = $this->createRequest($resume);

        $response = $this->generateResponse($request);

        $result = $this->responseParser->parse($response);

        return $this->storeAnalysis($resume, $result);
    }

    private function createRequest(Resume $resume): AIRequest
    {
        $provider = $this->aiManager->provider();

        $config = config("ai.providers.{$provider->name()}");

        $prompt = $this->promptBuilder->build($resume);

        return new AIRequest(
            provider: $provider->name(),
            model: $config['model'],
            prompt: $prompt,
            temperature: 0.2,
        );
    }

    private function generateResponse(AIRequest $request): AIResponse
    {
        return $this->aiManager
            ->provider($request->provider)
            ->generate($request);
    }

    private function storeAnalysis(
        Resume $resume,
        ResumeAnalysisResult $result
    ): ResumeAnalysis {
        return $resume->analysis()->create([
            'score' => $result->score,
            'summary' => $result->summary,
            'strengths' => $result->strengths,
            'weaknesses' => $result->weaknesses,
            'missing_skills' => $result->missingSkills,
            'recommended_roles' => $result->recommendedRoles,
        ]);
    }
}
