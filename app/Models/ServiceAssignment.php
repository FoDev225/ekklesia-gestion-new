<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAssignment extends Model
{
    protected $fillable = ['service_id', 'believer_id', 'service_role_id', 'worship_group_id', 'is_backup'];
 
    protected $casts = ['is_backup' => 'boolean'];
 
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
 
    public function believer()
    {
        return $this->belongsTo(Believer::class);
    }
 
    public function role()
    {
        return $this->belongsTo(ServiceRole::class, 'service_role_id');
    }
 
    public function worshipGroup()
    {
        return $this->belongsTo(WorshipGroup::class);
    }
}
