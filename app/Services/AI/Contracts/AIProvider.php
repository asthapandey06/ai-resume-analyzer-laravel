<?php
namespace App\Services\AI\Contracts;

use App\Services\AI\DTO\AIRequest;
use App\Services\AI\DTO\AIResponse;

interface AIProvider
{
    public function name(): string;
    public function generate(AIRequest $request): AIResponse;
}