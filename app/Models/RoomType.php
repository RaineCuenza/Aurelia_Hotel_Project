<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'base_price',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
