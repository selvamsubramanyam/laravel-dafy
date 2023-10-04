<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Modules\Product\Entities\Product;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderProduct;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\OrderAddress;
use DateTime;

class OrderPendingCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order_pending:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // \Log::info("Cron is working fine!");
     
        $date = new DateTime;
        $date->modify('-5 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');

        $orders = Order::whereHas('orderStatus', function($q) {
                $q->where(['slug' => 'pending']);
            })->get();
        // \Log::info("Orders : ".$orders);
        foreach ($orders as $key => $order) {
            // \Log::info("Orders : ".$order);

            if($order->created_at < $formatted_date && $order->is_reverted == 0) {
                $order->update(['is_reverted' => 1]);

                $order_products = OrderProduct::where(['order_id' => $order->id])->get();

                foreach ($order_products as $key => $order_product) {

                    $product = Product::where(['id' => $order_product->product_id])->first();

                    $product->update(['stock' => $product->stock + $order_product->product_quantity]);
                }
            }
        }

        // \Log::info("Cron Cummand Run successfully!");
    }
}
