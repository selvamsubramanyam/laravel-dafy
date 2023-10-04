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
use Carbon\Carbon;
use Carbon\CarbonPeriod;



  
class VendorSaleExport extends DefaultValueBinder implements FromCollection,WithMapping,ShouldAutoSize,WithHeadings
{

    public function __construct($shop_id,$from_id=null,$to_id=null) 
    {
      
       $this->shop_id = $shop_id;
       $this->from_id = $from_id;
       $this->to_id = $to_id;
    }

    public function map($order): array
    { 

        $total= 0;
        $shop_pay = 0;
        foreach($order->orderProducts as $products)
        {
            if($products->product)
            {
                $total += ($products->tot_price) * ($products->product->commission)/100 ;
            }
        }
        
        $shop_pay = $order->grand_total - $total ;

        return [
           
            $order->shop->name ?? '',
            $order->order_no  ?? '',
            $order->delivery_date ? date('d-m-Y',strtotime($order->delivery_date)) : 'NA',
            $order->discount != 0.0 ? $order->discount : '0.0',
            $order->grand_total != 0.0 ? $order->grand_total : '0.0',
            $total != 0.0 ? $total : '0.0',
            $shop_pay != 0.0 ? $shop_pay : '0.0',
            
           
        ];
    }

    public function collection()
    {
        $status = Status::where(['slug' => 'delivered','is_active' =>1])->first();

       
   
        $order = Order::with(['shop','user','orderStatus','orderProducts.product'])->where(['shop_id' => $this->shop_id ,'is_active' =>1])->where('status_id',$status->id)
                ->whereHas('orderProducts.product', function($query){
                    $query->orWhere('commission','<>',NULL);
                })->orderBy('created_at', 'desc');

                if($this->from_id != null && $this->to_id != null)
                {
                    $order->whereBetween('created_at',[$this->from_id, $this->to_id]);
                }else{
                    $startDate = Carbon::now(); //returns current day
                    $firstDay = $startDate->firstOfMonth();  
                    $yesterday = Carbon::yesterday();

                    $order->whereBetween('created_at',[$firstDay,$yesterday]);

                }

      
        return $order->get();
       
    
    }

    public function headings(): array
    {

       
        return [
            'Shop Name',
            'Order No',
            'Delivery Date',
            'Discount',
            'Grand Total (Rs.)',
            'Dafy Commission (Rs.)',
            'Shop Payment (Rs.)',
           
            
        ];


    }

  

  
}