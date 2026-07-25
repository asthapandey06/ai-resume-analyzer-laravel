<?php

return [

    'default' => env('AI_PROVIDER', 'gemini'),

    'providers' => [

        'gemini' => [
            'driver' => 'App\Services\AI\Providers\GeminiProvider',
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-flash-latest'),
            'endpoint' => env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent'),
        ],

        'openai' => [
            'driver' => 'App\Services\AI\Providers\OpenAIProvider',
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4.1'),
            'endpoint' => env('OPENAI_ENDPOINT', 'https://api.openai.com/v1/chat/completions'),
        ],

    ],

];