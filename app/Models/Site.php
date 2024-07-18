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

    public function WatchPermissions()
    {
        return $this->belongsToMany(User::class, 'permission_site_watch', 'site_id', 'user_id')->withTimestamps();
    }

    public function WriteReportsPermissions()
    {
        return $this->belongsToMany(User::class, 'permission_report_write', 'site_id', 'user_id')->withTimestamps();
    }
}
