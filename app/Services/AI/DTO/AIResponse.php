<?php

namespace App\Services\AI\DTO;

class AIResponse
{
    public function __construct(
        public string $provider,
        public string $model,
        public string $content,
        public array $usage,
        public array $raw = []
    ) {}
}