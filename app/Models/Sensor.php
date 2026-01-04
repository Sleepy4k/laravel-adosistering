<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory, HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'block_id',
        'humidity',
        'flow_rate',
        'volume',
        'status',
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
            'humidity' => 'float',
            'flow_rate' => 'float',
            'volume' => 'float',
            'status' => 'string',
        ];
    }

    /**
     * Get the sprayer that owns the sensor.
     */
    public function sprayer()
    {
        return $this->belongsTo(Sprayer::class);
    }
}
