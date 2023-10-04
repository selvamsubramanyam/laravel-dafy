<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class EnquiryResult extends Model
{
    protected $table = 'enquiry_result';
    protected $primaryKey = 'id';

    protected $fillable = ['enquiry_id', 'message'];
}
