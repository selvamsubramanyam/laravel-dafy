<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';

    protected $fillable = ['notification_id', 'from_id', 'to_id', 'order_id', 'shop_id', 'product_id', 'enquiry_id', 'is_view', 'is_sent'];

    public function notificationCategory()
    {
        return $this->hasOne('Modules\Admin\Entities\NotificationCategory', 'id', 'notification_id');
    }

    public function orderData()
    {
        return $this->hasOne('Modules\Order\Entities\Order', 'id', 'order_id');
    }

    public function getShop()
    {
        return $this->hasOne("Modules\Shop\Entities\BusinessShop", "id", "shop_id");
    }

    public function getProduct()
    {
        return $this->hasOne("Modules\Product\Entities\Product", "id", "product_id");
    }

}