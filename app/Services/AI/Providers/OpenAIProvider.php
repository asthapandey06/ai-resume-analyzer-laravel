<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProvider;
use App\Models\Resume;
use App\Services\AI\DTO\AIResponse;

class OpenAIProvider extends BaseAIProvider implements AIProvider
{

    private const PROVIDER = 'openAI';
    protected function configKey(): string
    {
        return self::PROVIDER;
    }


    public function generate(Resume $resume): AIResponse
    {
        // HTTP request to OpenAI
    }
}