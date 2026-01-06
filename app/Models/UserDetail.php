<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory, HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'familiar_name',
        'gender',
        'date_of_birth',
        'other_phone',
        'occupation',
        'domicile',
        'address',
        'notes',
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
            'familiar_name' => 'string',
            'gender' => 'string',
            'date_of_birth' => 'date',
            'other_phone' => 'string',
            'occupation' => 'string',
            'domicile' => 'string',
            'address' => 'string',
            'notes' => 'string',
        ];
    }

    /**
     * Get the user that owns the detail.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
