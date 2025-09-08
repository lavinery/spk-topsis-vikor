<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentStep extends Model
{
    protected $fillable = ['assessment_id', 'step', 'payload'];
    
    protected $casts = [
        'payload' => 'array',
    ];
    
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
