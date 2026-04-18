<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentUpload extends Model
{
    protected $fillable = ['user_id', 'requirement_id', 'file_path', 'status'];

    // Para makuha ang pangalan ng requirement sa table
    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }
}