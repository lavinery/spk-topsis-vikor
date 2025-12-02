<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    use HasFactory;
    protected $fillable = [
        'mountain_id', 'name', 'distance_km', 'elevation_gain_m', 'slope_deg', 'slope_class',
        'land_cover_key', 'water_sources_score', 'support_facility_score', 'permit_required'
    ];
    
    public function mountain()
    {
        return $this->belongsTo(Mountain::class);
    }
}
