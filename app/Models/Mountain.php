<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mountain extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'elevation_m', 'province', 'lat', 'lng', 'status'];
    
    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}
