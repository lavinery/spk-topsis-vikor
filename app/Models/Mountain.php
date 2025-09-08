<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mountain extends Model
{
    protected $fillable = ['name', 'elevation_m', 'province', 'lat', 'lng', 'status'];
    
    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}
