<?php

namespace App\Services\AI\DTO;

final readonly class Prompt
{
    public function __construct(
        public string $system,
        public string $user,
    ) {}
}