<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuzzyTerm extends Model
{
    protected $fillable = [
        'criterion_id',
        'code',
        'label',
        'shape',
        'params_json'
    ];
    
    protected $casts = [
        'params_json' => 'array'
    ];
    
    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
    
    /**
     * Get the membership function parameters as array
     */
    public function getParamsAttribute(): array
    {
        return is_string($this->params_json) 
            ? json_decode($this->params_json, true) 
            : $this->params_json;
    }
}