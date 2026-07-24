<?php

return [

    'default' => env('AI_PROVIDER', 'gemini'),

    'providers' => [

        'gemini' => [
            'driver' => 'App\Services\AI\Providers\GeminiProvider',
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
        ],

        'openai' => [
            'driver' => 'App\Services\AI\Providers\OpenAIProvider',
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4.1'),
        ],

    ],

];