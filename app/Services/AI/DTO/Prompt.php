<?php

namespace App\Services\Resume;

final readonly class Prompt
{
    public function __construct(
        public string $system,
        public string $user,
    ) {}
}