<?php

namespace App\Services\AI\DTO;

class AIResponse
{
    public function __construct(
        public string $provider,
        public array $model,
        public string $content,
        public string $usage,
        public array $raw = []
    ) {}
}