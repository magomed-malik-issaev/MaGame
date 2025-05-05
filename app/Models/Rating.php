<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'rating'
    ];

    /**
     * Règles de validation pour les notes
     */
    public static $rules = [
        'rating' => 'required|integer|min:1|max:5',
    ];

    /**
     * Récupère l'utilisateur qui a donné cette note
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupère le jeu associé à cette note
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
