<?php

namespace Modules\Media\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB, Config;

class ContestVideo extends Authenticatable
{
    protected $table = 'contestant_videos';

    protected $fillable = ['media_id', 'title', 'description', 'video_url', 'poster', 'thumbnail_url', 'share_url', 'votes', 'view_count', 'is_active'];

    public function events()
    {
    	return $this->belongsTo("Modules\Media\Entities\Media","media_id");
    }

}