<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriterionWeight extends Model
{
    protected $fillable = ['criterion_id', 'weight', 'version'];
    
    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}
