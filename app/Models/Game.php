<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_api_id',
        'name',
        'description',
        'background_image',
        'released',
        'platforms',
        'genres',
        'publishers',
        'rating'
    ];

    protected $casts = [
        'platforms' => 'array',
        'genres' => 'array',
        'publishers' => 'array',
        'released' => 'date',
    ];

    /**
     * Récupère tous les commentaires pour ce jeu
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Récupère toutes les notes pour ce jeu
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Récupère les utilisateurs qui ont ce jeu dans leur collection
     */
    public function collectors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'game_collections')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Calcule la note moyenne donnée par les utilisateurs
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?: 0;
    }
}
