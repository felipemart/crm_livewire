<?php

declare(strict_types = 1);

namespace App\Models;

use App\Notifications\PasswordRecoveryNotification;
use App\Notifications\VerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
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
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new PasswordRecoveryNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail());
    }

    public function permissons(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermission(string $key): void
    {
        $this->permissons()->firstOrCreate(['key' => $key]);
    }

    public function hasPermission(string | array $key): bool
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                if ($this->hasPermission($k)) {
                    return true;
                }
            }

            return false;
        }

        return  $this->permissons()->where('key', '=', $key)->exists();
    }
}
