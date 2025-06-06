<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'type', 
        'registration',
        'admission',
        'avatar',
    ];

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
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'admission' => 'date',
        'password' => 'hashed',
    ];

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default-avatar.png');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function document()
    {
        return $this->belongsToMany(Document::class);
    }

    public function uploadeddocument()
    {
        return $this->hasMany(Document::class, 'user_upload_id');
    }

    public function position()
    {
        return $this->hasOne(Position::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class);
    }

    public function responsibleSectors()
    {
        return $this->belongsToMany(Sector::class, 'sector_responsible_user');
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'position_user');
    }

    public function responsibleMacros()
    {
        return $this->belongsToMany(\App\Models\Macro::class, 'macro_responsible_user');
    }
    public function loginLogs()
{
    return $this->hasMany(LoginLog::class);
}
}
