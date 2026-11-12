<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'balance',
    'discord_id',
    'discord_username',
    'discord_linked_at',
    'telegram_id',
    'telegram_username',
    'telegram_linked_at',
    'minecraft_name',
    'minecraft_uuid',
    'minecraft_linked_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_PLAYER = 'player';
    public const ROLE_MODERATOR = 'moderator';
    public const ROLE_ADMIN = 'admin';

    public const ROLES = [self::ROLE_PLAYER, self::ROLE_MODERATOR, self::ROLE_ADMIN];

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function skin(): HasOne
    {
        return $this->hasOne(Skin::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /** Модератор або адмін: модерація скінів і бани. */
    public function isStaff(): bool
    {
        return in_array($this->role, [self::ROLE_MODERATOR, self::ROLE_ADMIN], true);
    }

    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'integer',
            'discord_linked_at' => 'datetime',
            'telegram_linked_at' => 'datetime',
            'minecraft_linked_at' => 'datetime',
            'banned_at' => 'datetime',
            'telegram_id' => 'integer',
        ];
    }
}
