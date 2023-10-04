<?php
  
namespace App\Exports;
  
use Modules\Product\Entities\Product;
use Modules\Shop\Entities\BusinessShop;
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




  
class ProductStatusExport extends DefaultValueBinder implements FromCollection,WithMapping,ShouldAutoSize,WithHeadings
{

    public function __construct($is_approved) 
    {
        $this->is_appr = $is_approved;
       
    }

    public function map($product): array
    { 
       
        if($product->type == 0)
        {
            $type = 'single';
        }
        else if($product->type == 1)
        {
            $type = 'simple';
        }else{
            $type = 'configurable';
        }

        $categ = '';

        if(!empty($product->categories))
        {
            foreach($product->categories as $category)
            {
                $categ.= $category->name.',';
            }

            $categ= rtrim($categ, ", ");
        }

        $attrs ='';
        
        if(!empty($product->attributes))
        {
            foreach($product->attributes as $cat_attr)
            {
               
                    $attrs.= $cat_attr->pivot->attr_value.',';
            }

            $attrs= rtrim($attrs, ", ");
        }

        $parent = '';

        if(!empty($product->parentProduct))
        {
            $parent = $product->parentProduct->sku;
        }else{
            $parent = $type;
        }

        return [
            
            $product->shop->name ?? '',
            $product->sku ?? '',
            $product->name ?? '',
            $parent,
            $product->brand->name ?? '',
            $product->vendor_price ?? '',
            $product->price ?? '',
            $product->stock ?? '',
            $product->thump_image ?? '',
            $product->commission ?? '',
            $type ?? '',
            $categ,
            $attrs,
            $product->is_approved == 1 ? 'Yes' : 'No',
            $product->is_active == 1 ? 'Active' : 'InActive',
           
           
        ];
    }

    public function collection()
    {
       
        
         return $product= Product::with(['categories','brand','shop','parentProduct','attributes'])->where('is_approved',$this->is_appr)->orderby('id','desc')->get();
    
    }

    public function headings(): array
    {

       
        return [
            
            'Shop Name',
            'Sku',
            'Product Name',
            'Parent Sku',
            'Brand',
            'Vendor Price',
            'Price',
            'Stock',
            'Thump Image',
            'Commission',
            'Product Type',
            'Categories',
            'Attributes',
            'Is Approved',
            'Status',
         
            
        ];


    }

  
}