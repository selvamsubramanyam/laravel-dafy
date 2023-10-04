<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointHistory extends Model
{
    protected $table = 'point_history';
    protected $primaryKey = 'id';
    use SoftDeletes;

    protected $fillable = ['user_id', 'from_id', 'is_credit', 'points', 'slug', 'order_id', 'description', 'is_valid'];

    public function ReferralUser()
    {
        return $this->hasOne('Modules\Users\Entities\User', 'id', 'from_id');
    }

    public function orderData()
    {
        return $this->hasOne('Modules\Order\Entities\Order', 'id', 'order_id');
    }
}