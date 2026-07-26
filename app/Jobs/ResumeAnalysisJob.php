<?php

namespace App\Jobs;

use App\Models\Resume;
use App\Services\Pdf\PdfExtractionService;
use App\Services\Resume\ResumeAnalysisService;
use App\Services\Resume\ResumeService;
use App\Services\Resume\ResumeStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class ResumeAnalysisJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Resume $resume)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(ResumeService $resumeService): void
    {
        $resumeService->processResume($this->resume);
    }
}
