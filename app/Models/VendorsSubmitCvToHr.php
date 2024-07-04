<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorsSubmitCvToHr extends Model
{
    use HasFactory;
    public $jsonData = '[{"cv":"52x8a2EdWPGii7YXvmcntHhsIAjWuy2G4YK13Hqv.pdf"},{"cv":"BGDiB9cVIQZIkNff0O9gkeGIjjcHyfK13Fhrwcqz.pdf"}]';

    protected $casts = [

        'cv' => 'array',
    ];
    protected $table = 'vendors_submit_cv_to_hr';
    protected $fillable = ['user_id', 'job_id', 'submited_to', 'cv'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public $data;
    public function job()
    {
        $this->data = json_decode($this->jsonData, true);

        return $this->belongsTo(Job::class, 'job_id', 'job_id');
    }
}
