<?php

namespace App\Services\Resume;

use App\Jobs\ResumeAnalysisJob;
use App\Models\Resume;
use App\Services\Pdf\PdfExtractionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Laravel\Pail\Options;
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
        Log::info('Upload started');
        $resume = $this->storeResume($file);
Log::info('Resume stored');
        //call the extractResumeText and analyzeResume methods asynchronously using a job
        ResumeAnalysisJob::dispatch($resume);
Log::info('Job dispatched');
        // $this->extractResumeText($resume);

        // $this->analyzeResume($resume);

        return $resume->load('analysis');
    }

    public function processResume(Resume $resume): void
    {
        logger('Job started', ['resume_id' => $resume->id]);
        $text = $this->pdfExtractionService->extract($resume);

        if (blank($text)) {
            $resume->update([
                'status' => ResumeStatus::Failed->value,
            ]);

            throw new RuntimeException('No text could be extracted.');
        }
 logger('PDF extracted', [
        'length' => strlen($text)
    ]);
        $resume->update([
            'extracted_text' => $text,
            'status' => ResumeStatus::Analyzing->value,
        ]);
logger('Calling AI analysis');
        $this->resumeAnalysisService->analyze($resume);
logger('AI analysis finished');
        $resume->update([
            'status' => ResumeStatus::Completed->value,
        ]);
        logger('Job completed');
    }

    /**
     * Store the uploaded resume file and create a Resume record in the database.
     *
     * @param UploadedFile $file The uploaded resume file.
     * @return Resume The created Resume record.
     */
    private function storeResume(UploadedFile $file): Resume
    {
        $start = microtime(true);
        $path = $file->store('resumes', 'public');
        logger('File store: ' . round((microtime(true) - $start) * 1000, 2) . ' ms');
        $start = microtime(true);
        logger('File size: ' . round($file->getSize() / 1024, 2) . ' KB');
        $resume = Resume::create([
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => basename($path),
            'file_path'     => $path,
            'mime_type'     => $file->getMimeType(),
            'file_size'     => $file->getSize(),
            'status'        => ResumeStatus::Uploaded->value,
        ]);
        logger('DB insert: ' . round((microtime(true) - $start) * 1000, 2) . ' ms');
        return $resume;
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

            $this->resumeAnalysisService->analyze($resume); //instead of this, we will dispatch a job to handle the analysis asynchronously

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

    public function getAll(string $sort, int $limit, int $page, ?array $filters = null): array
    {
        // add total number of resumes and total pages to the response
        $total = Resume::count();
        $totalPages = ceil($total / $limit);

        $resumes = Resume::query()
            ->with('analysis')
            ->orderBy('created_at', $sort)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->get();

        return [
            'total' => $total,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'data' => $resumes,
        ];
    }

    public function delete(Resume $resume): void
    {
        // Delete the file from storage
        if (file_exists(storage_path('app/public/' . $resume->file_path))) {
            unlink(storage_path('app/public/' . $resume->file_path));
        }

        // Delete the resume record from the database
        $resume->delete();
    }
}
