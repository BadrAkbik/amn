<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function report()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $role_id = Role::firstOrCreate(['name' => 'user'])->id;
            $user->role_id = $role_id;
        });
    }

    public function hasPermission($permission)
    {
        if ($this->role->name === 'owner') {
            return true;
        }
        $permission = $this->role->permissions()->where('name', $permission)->first();
        if ($permission)
            return true;
        else
            return false;
    }
}