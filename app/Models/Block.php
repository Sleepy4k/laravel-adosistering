<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory, HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'code',
        'name',
        'location',
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
            'code' => 'string',
            'name' => 'string',
            'location' => 'string',
        ];
    }

    /**
     * Get the user that owns the block.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the coordinate for the block.
     */
    public function coordinate()
    {
        return $this->hasOne(Coordinate::class);
    }

    /**
     * Get the sprayers for the block.
     */
    public function sprayers()
    {
        return $this->hasMany(Sprayer::class);
    }
}
