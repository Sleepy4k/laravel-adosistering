<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrrigationSetting extends Model
{
    use HasFactory, HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'moisture_min',
        'moisture_max',
        'moisture_dry',
        'moisture_normal',
        'moisture_wet',
        'safety_timeout_min',
        'safety_timeout_max',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'user_id' => 'string',
            'moisture_min' => 'integer',
            'moisture_max' => 'integer',
            'moisture_dry' => 'float',
            'moisture_normal' => 'float',
            'moisture_wet' => 'float',
            'safety_timeout_min' => 'integer',
            'safety_timeout_max' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the irrigation setting.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
