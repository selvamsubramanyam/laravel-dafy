<?php
  
namespace App\Exports;
  
use Modules\Product\Entities\ProductUntracked;
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




  
class UntrackedProductExport extends DefaultValueBinder implements FromCollection,WithMapping,ShouldAutoSize,WithHeadings
{

    public function __construct($shop_id) 
    {
        $this->shop_id = $shop_id;
       
    }

    public function map($product): array
    { 
       
        return [
           
            $product->sku ?? '',
            $product->brand ?? '',
            $product->name ?? '',
            $product->vendor_price ?? '',
            $product->price ?? '',
            $product->description ?? '',
            $product->stock ?? '',
            $product->category ?? '',
            $product->thump_image ?? '',
            $product->unit_measurement ?? '',
            $product->measurement_value ?? '',
            $product->product_type ?? '',
            $product->configurable_variations ?? '',
            $product->additional_attributes	?? '',
            $product->commission	?? '',
            $product->untrackImages->get(0)->image ?? '',
            $product->untrackImages->get(1)->image ?? '',
            $product->untrackImages->get(2)->image ?? '',
            $product->untrackImages->get(3)->image ?? '',
            $product->untrackImages->get(4)->image ?? '',
         
           
        ];
    }

    public function collection()
    {
       
         return $product= ProductUntracked::where('seller_id',$this->shop_id)->with('untrackImages')->get();
    
    }

    public function headings(): array
    {

       
        return [
            'sku',
            'brand',
            'name',
            'vendor_price',
            'price',
            'description',
            'stock',
            'category',
            'thump_image',
            'unit_measurement',
            'measurement_unit',
            'product_type',
            'configurable_variations',
            'additional_attributes',
            'Commission',
            'image1',
            'image2',
            'image3',
            'image4',
            'image5',
           
            
        ];


    }

  
}