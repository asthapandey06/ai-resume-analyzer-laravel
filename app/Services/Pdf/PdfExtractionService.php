<?php

namespace App\Services;

use App\Models\Resume;
use Smalot\PdfParser\Parser;

class PdfExtractionService
{
    public function extract(Resume $resume): string
    {
        $parser = new Parser();

        $pdf = $parser->parseFile(
            storage_path('app/public/' . $resume->file_path)
        );
        $text = $pdf->getText();
        // Convert to valid UTF-8
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        // Remove invalid UTF-8 bytes
        $text = iconv('UTF-8', 'UTF-8//IGNORE', $text);

        return trim($text);
    }
}
