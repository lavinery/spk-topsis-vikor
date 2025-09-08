<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = ['user_id', 'title', 'status', 'n_criteria', 'n_alternatives', 'weights_json', 'filters_json', 'top_k', 'pure_formula'];
    
    protected $casts = ['weights_json' => 'array', 'filters_json' => 'array'];
    
    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class);
    }
    
    public function alternatives()
    {
        return $this->hasMany(AssessmentAlternative::class);
    }
    
    public function steps()
    {
        return $this->hasMany(AssessmentStep::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    public function snapshot()
    {
        return $this->hasOne(AssessmentSnapshot::class);
    }
}
