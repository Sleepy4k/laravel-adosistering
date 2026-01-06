<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuid, HasRoles, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'is_active',
        'user_type_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'id' => 'string',
            'name' => 'string',
            'email' => 'string',
            'phone' => 'string',
            'is_active' => 'boolean',
            'user_type_id' => 'string',
            'password' => 'hashed',
        ];
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = url(route('password.reset', ['token' => $token, 'email' => $this->email], false));
        $this->notify(new ResetPassword($this->name, $url));
    }

    /**
     * Get the blocks for the user.
     */
    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    /**
     * Get the user API for the user.
     */
    public function api()
    {
        return $this->hasOne(UserApi::class);
    }

    /**
     * Get the user type that owns the user.
     */
    public function type()
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     * Get the details for the user.
     */
    public function details()
    {
        return $this->hasOne(UserDetail::class);
    }
}
