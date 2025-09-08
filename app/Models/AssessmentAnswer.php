<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAnswer extends Model
{
    protected $fillable = ['assessment_id', 'criterion_id', 'value_raw', 'value_numeric'];
    
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
    
    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}
