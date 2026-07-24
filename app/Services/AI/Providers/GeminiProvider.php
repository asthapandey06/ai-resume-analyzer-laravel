<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProvider;
use App\Services\AI\DTO\AIRequest;
use App\Services\AI\DTO\AIResponse;

class GeminiProvider extends BaseAIProvider implements AIProvider
{
    private const PROVIDER = 'gemini';
    protected function configKey(): string
    {
        return self::PROVIDER;
    }

    private function endpoint(string $model): string
    {
        return sprintf(
            $this->config()['endpoint'],
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
                            'text' =>implode("\n\n", [
                            $request->prompt->system,
                            $request->prompt->user,
                        ]),
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
        try {
            $config = $this->config();
            $model = $request->model ?: $config['model'];
            $response = $this->http()
                ->withQueryParameters([
                    'key' => $config['api_key'],
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
        } catch (\Exception $e) {
            throw new \RuntimeException(
                "Failed to generate AI response for {$this->name()}: " . $e->getMessage(), 0, $e
            );
        }
    }
}
