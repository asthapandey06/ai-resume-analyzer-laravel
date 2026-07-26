<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['resume_id', 'score', 'summary', 'strengths', 'weaknesses', 'missing_skills', 'analysis', 'recommended_roles'])]
class ResumeAnalysis extends Model
{
    protected $casts = [
        'strengths' => 'array',
        'weaknesses' => 'array',
        'missing_skills' => 'array',
        'recommended_roles' => 'array',
        'analysis' => 'array',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
