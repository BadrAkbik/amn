<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
