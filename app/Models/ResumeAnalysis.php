<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeAnalysis extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'resume_id',
        'score',
        'summary',
        'strengths',
        'weaknesses',
        'missing_skills',
        'analysis',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
