<?php

namespace App\Services\Resume;

use App\Models\Resume;
use App\Services\Pdf\PdfExtractionService;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Throwable;

enum ResumeStatus: string
{
    case Uploaded = 'uploaded';

    case Analyzing = 'processing';

    case Completed = 'completed';

    case Failed = 'failed';
}

class ResumeService
{
    public function __construct(
        private PdfExtractionService $pdfExtractionService,
        private readonly ResumeAnalysisService $resumeAnalysisService,
    ) {}

    /**
     * Upload a new resume file.
     *
     * @param UploadedFile $file The uploaded resume file.
     * @return Resume The created Resume record.
     */
    public function upload(UploadedFile $file): Resume
    {
        $resume = $this->storeResume($file);

        $this->extractResumeText($resume);

        $this->analyzeResume($resume);

        return $resume->load('analysis');
    }

    /**
     * Store the uploaded resume file and create a Resume record in the database.
     *
     * @param UploadedFile $file The uploaded resume file.
     * @return Resume The created Resume record.
     */
    private function storeResume(UploadedFile $file): Resume
    {
        $path = $file->store('resumes', 'public');

        return Resume::create([
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => basename($path),
            'file_path'     => $path,
            'mime_type'     => $file->getMimeType(),
            'file_size'     => $file->getSize(),
            'status'        => ResumeStatus::Uploaded->value,
        ]);
    }
    /**
     * Extract text from the resume.
     *
     * @param Resume $resume The resume record.
     * @return void
     */
    private function extractResumeText(Resume $resume): void
    {
        $text = $this->pdfExtractionService->extract($resume);
        if (blank($text)) {
            $resume->update([
                'status' => ResumeStatus::Failed->value,
            ]);

            throw new RuntimeException('No text could be extracted from the resume.');
        }
        $resume->update([
            'extracted_text' => $text,
            'status' => ResumeStatus::Analyzing->value,
        ]);
    }

    /**
     * Analyze the resume using AI.
     *
     * @param Resume $resume The resume record.
     * @return void
     */
    private function analyzeResume(Resume $resume): void
    {
        try {

            $this->resumeAnalysisService->analyze($resume);

            $resume->update([
                'status' => ResumeStatus::Completed->value,
            ]);
        } catch (Throwable $e) {

            $resume->update([
                'status' => ResumeStatus::Failed->value,
            ]);

            throw $e;
        }
    }
}
