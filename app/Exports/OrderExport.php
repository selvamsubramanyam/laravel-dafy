<?php
  
namespace App\Exports;
  
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\Status;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Auth;




  
class OrderExport extends DefaultValueBinder implements FromCollection,WithMapping,ShouldAutoSize,WithHeadings
{

    public function __construct($status) 
    {
      
       $this->status = $status;
    }

    public function map($order): array
    { 

        $products = '';
        if(!empty($order->orderProducts))
        {
            foreach($order->orderProducts as $order_product)
            {

                if(!empty($order_product->productData))
                {
                    $products.= $order_product->productData->name.' [Qty : '.$order_product->product_quantity.']';
                }

            }

            $products = rtrim($products, ", ");
        }

        $address = '';
        $order_address = $order->orderAddresses;

        if($order_address) {
          $name = $order_address->name;
          $build_name = $order_address->build_name;
          $area = $order_address->area;
          $pincode = $order_address->pincode;

          $address = 'Name : '.$name.', Building Name : '.$build_name.', Area : '.$area.', Pincode : '.$pincode;
        }

        return [
           
            $order->order_no ?? '',
            $order->user->name  ?? '',
            $order->shop->name ?? '',
            $order->discount ?? '',
            $order->delivery_fee ?? '',
            $order->amount ?? '',
            $order->points ?? '',
            $products,
            $order->created_at ? date('d-m-Y H:i:s', strtotime($order->created_at)) : '',
            $order->orderStatus ?  $order->orderStatus->name : '',
            $address,
           
        ];
    }

    public function collection()
    {
        $order = Order::with(['shop','user','orderStatus','orderProducts.productData'])->orderBy('id', 'desc');

        if(Auth::guard('seller')->check())
        {
            $order = $order->where('shop_id',Auth::guard('seller')->id());
        }

        if($this->status!=null)
        {

          $status_slug = Status::where('slug',$this->status)->first();

          if($this->status=='ordered')
          {
           
              $order->whereHas('orderStatus',function($query){
                  $query->where('slug','=','ordered');
              });

          }
          elseif($this->status=='accepted')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','accepted');
              });

          }
          elseif($this->status=='shipped')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','shipped');
              });

          }
          elseif($this->status=='delivered')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','delivered');
              });
          }
          elseif($this->status=='cancelled')
          {
              $order->whereHas('orderStatus',function($query){
                $query->where('slug','=','cancelled');
              });
          }
          elseif($this->status=='rejected')
          {

            $order->whereHas('orderStatus',function($query){
              $query->where('slug','=','rejected');
            });

          }else{
                    //

          }

        }

        return $order->get();
       
    
    }

    public function headings(): array
    {

       
        return [
            'Order No',
            'User Name',
            'Shop Name',
            'Discount',
            'Delivery Fee',
            'Amount',
            'Points',
            'Products',
            'Order Date',
            'Status',
            'Address'
            
        ];


    }

  

  
}