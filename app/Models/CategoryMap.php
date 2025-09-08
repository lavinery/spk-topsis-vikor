<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryMap extends Model
{
    protected $fillable = ['criterion_id', 'key', 'score', 'label', 'valid_from', 'valid_to'];
    
    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date'
    ];
    
    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}
