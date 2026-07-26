<?php

namespace App\Jobs;

use App\Models\Resume;
use App\Services\Resume\ResumeAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ResumeAnalysisJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(Resume $resume)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ResumeAnalysisService $service): void
    {
        // Extract PDF
        // Call Gemini
        // Parse JSON
        // Save Database
        // Send Email
    }
}
