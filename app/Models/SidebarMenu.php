<?php

namespace App\Models;

use App\Concerns\HasUuid;
use App\Concerns\Loggable;
use App\Concerns\MakeCacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidebarMenu extends Model
{
    use HasFactory, HasUuid, Loggable, MakeCacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'label',
        'icon',
        'route',
        'active',
        'permissions',
        'is_bottom',
        'danger',
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
            'label' => 'string',
            'icon' => 'string',
            'route' => 'string',
            'active' => 'string',
            'permissions' => 'array',
            'is_bottom' => 'boolean',
            'danger' => 'boolean',
            'password' => 'hashed',
        ];
    }
}
