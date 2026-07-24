<?php

namespace App\Services\AI\Providers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class BaseAIProvider
{
    abstract protected function configKey(): string;

    public function name(): string
    {
        return $this->configKey();
    }

    protected function http(): PendingRequest
    {
        return Http::acceptJson()
            ->timeout(60)
            ->retry(
                times: 3,
                sleepMilliseconds: 500,
            );
    }

    protected function validate(Response $response): void
    {
        $response->throw();
    }
    protected function body(Response $response): array
    {
        return $response->json();
    }
    protected function config(): array
    {
        return config("ai.providers.{$this->configKey()}");
    }
}
