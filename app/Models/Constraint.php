<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constraint extends Model
{
    protected $fillable = ['name', 'expr_json', 'action', 'active'];
    
    protected $casts = ['expr_json' => 'array'];
}
