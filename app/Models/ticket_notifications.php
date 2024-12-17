<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticket_notifications extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'message', 'is_read', 'redirect_url', 'notifiable_id', 'notifiable_type'];

    public function notifiable()
    {
        return $this->morphTo();
    }
}
