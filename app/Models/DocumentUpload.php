<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentUpload extends Model
{
    use HasFactory;

    protected $table = 'document_uploads';

    protected $fillable = [
        'user_id',
        'requirement_id',
        'submission_type',      // 'file_upload' or 'gdrive_link'
        'file_path',
        'submission_value',     // for Google Drive link
        'file_name',
        'status',
        'is_reuploaded',
        'reuploaded_at',
        'verification_reason',
        'verification_comment'
    ];

    protected $casts = [
        'reuploaded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }
}