<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientsWithProjects extends Model
{
    use HasFactory;
    protected $table = 'clients_with_projects';

    protected $fillable = [
        'client_id',
        'project_name',
        'project_description',
        'project_start_date',
        'project_end_date',
    ];

    // Relationship to Client model
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
