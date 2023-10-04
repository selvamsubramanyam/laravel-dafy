<?php

namespace Modules\Media\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class Media extends Authenticatable
{
    protected $table = 'media';
    protected $fillable = ['title', 'description', 'from_date', 'to_date', 'interactive_date', 'video_url', 'poster', 'thumbnail_url', 'share_url', 'is_active'];
}