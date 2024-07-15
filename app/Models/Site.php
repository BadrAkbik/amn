<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function allowedUsers()
    {
        return $this->belongsToMany(User::class, 'permission_site_user', 'site_id', 'user_id')->withTimestamps();
    }
}
