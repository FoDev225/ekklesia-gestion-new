<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRole extends Model
{
    protected $fillable = ['name', 'slug'];
 
    public function assignments()
    {
        return $this->hasMany(ServiceAssignment::class);
    }
 
    public function acteurs()
    {
        return $this->hasMany(ActeurCulte::class);
    }
}
