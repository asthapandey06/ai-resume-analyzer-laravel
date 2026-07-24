<?php 
namespace App\Services\AI;

use App\Services\AI\Contracts\AIProvider;
use App\Services\AI\Providers\GeminiProvider;
use App\Services\AI\Providers\OpenAIProvider;
use InvalidArgumentException;

class AIManager
{
    public function __construct(
       private iterable $providers
    ) {}

    public function provider(?string $provider = null): AIProvider
    {
        $provider ??= config('ai.default');

        return match ($provider) {
            'gemini' => $this->providers['gemini'],
            'openai' => $this->providers['openai'],

            default => throw new InvalidArgumentException(
                "Unsupported AI provider: {$provider}"
            ),
        };
    }
}