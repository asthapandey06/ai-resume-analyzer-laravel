<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProvider;
use App\Services\AI\DTO\AIRequest;
use App\Services\AI\DTO\AIResponse;

class OpenAIProvider extends BaseAIProvider implements AIProvider
{

    private const PROVIDER = 'openAI';
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

    public function generate(AIRequest $request): AIResponse
    {
        // HTTP request to OpenAI
        $response = $this->http()
                ->withQueryParameters([
                    'key' => $this->config()['api_key'],
                ])
                ->post(
                    $this->endpoint($this->config()['model']),
                    $this->payload($request),
                );

            $this->validate($response);
            $body = $this->body($response);
        return new AIResponse(
                provider: $this->name(),
                model: $this->config()['model'],
                content:  $this->extractContent($body),
                usage: data_get($body, 'usageMetadata', []),
                raw: $body,
            );
    }
     private function extractContent(array $body): string
    {
        return data_get(
            $body,
            'candidates.0.content.parts.0.text',
            ''
        );
    }
}