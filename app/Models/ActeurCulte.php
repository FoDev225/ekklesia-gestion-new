<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActeurCulte extends Model
{
    protected $fillable = ['believer_id', 'service_role_id', 'is_active'];
 
    protected $casts = ['is_active' => 'boolean'];
 
    public function believer()
    {
        return $this->belongsTo(Believer::class);
    }
 
    public function role()
    {
        return $this->belongsTo(ServiceRole::class, 'service_role_id');
    }
}
