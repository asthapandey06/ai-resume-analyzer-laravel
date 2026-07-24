<?php

namespace App\Services\Resume;

use App\Models\Resume;
use Illuminate\Http\UploadedFile;
use App\Services\PdfExtractionService;

class ResumeService
{
    public function __construct(
        private PdfExtractionService $pdfExtractionService
    ) {}
    public function upload(UploadedFile $file): Resume
    {
        $path = $file->store('resumes', 'public');

        $resume = Resume::create([
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => basename($path),
            'file_path'     => $path,
            'mime_type'     => $file->getMimeType(),
            'file_size'     => $file->getSize(),
            'status'        => 'uploaded',
        ]);

        $text = $this->pdfExtractionService->extract($resume);

        $updated = $resume->update([
            'extracted_text' => $text,
        ]);
        if (! $updated) {
            throw new \RuntimeException('Failed to update extracted text.');
        }

        return $resume->fresh();
    }
}
