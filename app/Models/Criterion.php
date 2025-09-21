<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    protected $fillable = ['code', 'name', 'type', 'source', 'data_type', 'unit', 'active', 'is_fuzzy', 'version'];
    
    public function weights()
    {
        return $this->hasMany(CriterionWeight::class);
    }
    
    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class);
    }
    
    public function categoryMaps()
    {
        return $this->hasMany(CategoryMap::class);
    }
    
    public function fuzzyTerms()
    {
        return $this->hasMany(FuzzyTerm::class);
    }
    
    public function fuzzyMapping()
    {
        return $this->hasOne(FuzzyMapping::class);
    }
    
}
