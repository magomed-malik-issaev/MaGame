<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'content'
    ];

    /**
     * Récupère l'utilisateur qui a fait ce commentaire
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupère le jeu associé à ce commentaire
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
