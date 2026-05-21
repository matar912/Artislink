<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'telephone',
        'est_actif',
        'avatar',
    ];

    protected $appends = [
        'name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'est_actif' => 'boolean',
        ];
    }

    // ── Helpers rôle ──────────────────────────────────────────────────────────
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isArtisan(): bool { return $this->role === 'artisan'; }
    public function isVisiteur(): bool { return $this->role === 'visiteur'; }

    // ── Relations ─────────────────────────────────────────────────────────────
    public function artisan(): HasOne
    {
        return $this->hasOne(Artisan::class);
    }

    public function visiteur(): HasOne
    {
        return $this->hasOne(Visiteur::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────
    public function getNameAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        $initials = urlencode(mb_substr($this->prenom ?? '', 0, 1) . mb_substr($this->nom ?? '', 0, 1));
        return "https://ui-avatars.com/api/?name={$initials}&background=1D9E75&color=fff&size=80";
    }

    public function getDashboardRouteAttribute(): string
    {
        return match ($this->role) {
            'artisan' => route('artisan.tableau-bord'),
            'admin'   => route('admin.tableau-bord'),
            default   => route('visiteur.tableau-bord'),
        };
    }
}
