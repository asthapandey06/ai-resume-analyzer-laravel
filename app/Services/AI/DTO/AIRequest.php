<?php

namespace App\Services\AI\DTO;

use App\Services\Resume\Prompt;

class AIRequest
{
    public function __construct(
        public readonly string $provider,
        public readonly string $model,
        public readonly Prompt $prompt,
        public readonly float $temperature = 0.2,
        public readonly ?int $maxTokens = null,
        public array $metadata = [],
    ) {}
}