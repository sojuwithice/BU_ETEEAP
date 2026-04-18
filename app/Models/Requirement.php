<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    protected $fillable = ['name', 'note'];

    public function userUpload()
    {
        return $this->hasOne(DocumentUpload::class, 'requirement_id');
    }
}
