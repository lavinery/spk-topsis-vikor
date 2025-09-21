<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuzzyMapping extends Model
{
    protected $fillable = [
        'criterion_id',
        'input_min',
        'input_max',
        'default_term_code'
    ];
    
    protected $casts = [
        'input_min' => 'decimal:2',
        'input_max' => 'decimal:2'
    ];
    
    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}