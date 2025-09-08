<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['actor_id', 'entity', 'entity_id', 'action', 'before_json', 'after_json'];
    
    protected $casts = ['before_json' => 'array', 'after_json' => 'array'];
    
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
