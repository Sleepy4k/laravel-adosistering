<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprayer extends Model
{
    use HasFactory, HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'block_id',
        'name',
        'is_pump',
        'is_auto_irrigation',
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
            'block_id' => 'string',
            'name' => 'string',
            'is_pump' => 'boolean',
            'is_auto_irrigation' => 'boolean',
        ];
    }

    /**
     * Get the block that owns the sprayer.
     */
    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    /**
     * Get the sensor for the sprayer.
     */
    public function sensor()
    {
        return $this->hasOne(Sensor::class);
    }
}
