<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Récupère tous les commentaires de l'utilisateur
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Récupère toutes les notes de l'utilisateur
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Récupère la collection de jeux de l'utilisateur
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_collections')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Vérifie si l'utilisateur a déjà noté un jeu spécifique
     */
    public function hasRated(Game $game): bool
    {
        return $this->ratings()->where('game_id', $game->id)->exists();
    }

    /**
     * Récupère la note donnée par l'utilisateur pour un jeu spécifique
     */
    public function getRatingFor(Game $game)
    {
        return $this->ratings()->where('game_id', $game->id)->first();
    }

    /**
     * Vérifie si l'utilisateur est un administrateur
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un utilisateur normal
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
