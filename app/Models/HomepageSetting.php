<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{
    protected $fillable = [
        'hero_headline',
        'hero_highlight',
        'hero_image',
        'about_main',
        'about_more',
        'dean_name',
        'dean_title',
        'dean_image',
        'news_rss',
        'apply_address',
        'apply_link',
        'apply_qr',
        'programs',
        'faqs',
        'contact_email',
        'contact_fb',
        'contact_map',
    ];

    protected $casts = [
        'programs' => 'array',
        'faqs' => 'array',
    ];
}
