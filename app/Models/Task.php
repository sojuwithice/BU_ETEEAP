<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'title', 'description', 'type', 'related_id', 'status', 'action_url'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function getActionUrlAttribute()
    {
        switch ($this->type) {
            case 'profile':
                return route('applicant.profile');
            case 'document':
            case 'reupload':
                return route('applicant.documents');
            default:
                return '#';
        }
    }
}