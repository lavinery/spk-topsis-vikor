<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAlternative extends Model
{
    protected $fillable = ['assessment_id', 'route_id', 'is_excluded'];
    
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
    
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
