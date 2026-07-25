<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Resume extends Model
{
    protected $fillable = [
        'original_name',
        'stored_name',
        'file_path',
        'mime_type',
        'file_size',
        'status',
        'extracted_text',
    ];

    /**
     * Get the analysis associated with the resume.
     */
    public function analysis(): HasOne
    {
        return $this->hasOne(ResumeAnalysis::class);
    }
}
