<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class NotificationCategory extends Model
{
    protected $table = 'notification_categories';
    protected $primaryKey = 'id';

    protected $fillable = ['title', 'slug', 'description', 'image'];
}