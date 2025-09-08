<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentSnapshot extends Model
{
    protected $fillable = [
        'assessment_id',
        'criteria',
        'weights',
        'category_maps',
        'params'
    ];

    protected $casts = [
        'criteria' => 'array',
        'weights' => 'array',
        'category_maps' => 'array',
        'params' => 'array'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}