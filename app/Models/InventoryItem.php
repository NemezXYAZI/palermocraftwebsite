<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'status',
        'purchased_at',
        'refunded_at',
    ];

    protected $casts = [
        'price' => 'integer',
        'purchased_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
