<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmojiReaction extends Model
{
    use HasFactory;
    protected $table = 'emoji_reactions';
    protected $fillable = ['emp_id', 'first_name', 'last_name', 'emoji_reaction'];
    public function emoji_reactions()
    {
        return $this->hasMany(EmojiReaction::class, 'emp_id', 'emp_id');
    }
}
