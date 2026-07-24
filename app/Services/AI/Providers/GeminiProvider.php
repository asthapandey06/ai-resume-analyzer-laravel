<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProvider;
use App\Services\AI\DTO\AIRequest;
use App\Services\AI\DTO\AIResponse;

class GeminiProvider extends BaseAIProvider implements AIProvider
{
    private $providerName = 'gemini';
    /**
     * Get the name of the provider.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->providerName;
    }
    protected function configKey(): string
    {
        return $this->providerName;
    }
    protected function config(): array
    {
        return config("ai.providers.{$this->configKey()}");
    }

    private function endpoint(string $model): string
    {
        return sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
            $model
        );
    }
    private function payload(AIRequest $request): array
    {
        return [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $request->prompt,
                        ],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => $request->temperature,
            ],
        ];
    }

    private function extractContent(array $body): string
    {
        return data_get(
            $body,
            'candidates.0.content.parts.0.text',
            ''
        );
    }
    public function generate(AIRequest $request): AIResponse
    {
        $model = $request->model ?: $this->config()['model'];
        $response = $this->http()
            ->withQueryParameters([
                'key' => $this->config()['api_key'],
            ])
            ->post(
                $this->endpoint($model),
                $this->payload($request),
            );

        $this->validate($response);
        $body = $this->body($response);
        return new AIResponse(
            provider: $this->name(),
            model: $model,
            content: $this->extractContent($body),
            usage: data_get($body, 'usageMetadata', []),
            raw: $body,
        );
    }
}
