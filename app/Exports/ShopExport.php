<?php
  
namespace App\Exports;
  
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
use Auth;




  
class ShopExport extends DefaultValueBinder implements FromCollection,WithMapping,ShouldAutoSize,WithHeadings
{

    public function __construct() 
    {
      
       
    }

    public function map($shop): array
    { 

        $attrs ='';
        
        if(!empty($shop->shopCategories))
        {
            foreach($shop->shopCategories as $shop_cat)
            {
                    if($shop_cat->categoryData)
                    {
                        $attrs.= $shop_cat->categoryData->name.',';
                    }
            }

            $attrs= rtrim($attrs, ", ");
        }
       
        return [
           
            $shop->sellerInfo->name ?? '',
            $shop->name  ?? '',
            $shop->image ? asset('storage/app/'.$shop->image) : '',
            $shop->location ?? '',
            $shop->email ?? '',
            $attrs,
            $shop->is_active == 1 ? 'Active' : 'InActive',
            
           
        ];
    }

    public function collection()
    {
        if(Auth::guard('seller')->check())
        {
            $shop_id = Auth::guard('seller')->id();
            $shop_list = BusinessShop::find($shop_id);
            return $shop = BusinessShop::where('id',$shop_id)->with(['sellerInfo','shopCategories.categoryData'])->orderBy('id','desc')->get();
        }

        if(Auth::guard('admin')->check())
        {

            return $shop = BusinessShop::with(['sellerInfo','shopCategories.categoryData'])->orderBy('id','desc')->get();
        }
       
    
    }

    public function headings(): array
    {

       
        return [
            'Seller Name',
            'Shop Name',
            'Icon',
            'Location',
            'Email',
            'Categories',
            'Status',
           
            
        ];


    }

  
}