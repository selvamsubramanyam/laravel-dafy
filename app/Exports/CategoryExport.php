<?php
  
namespace App\Exports;
  
use Modules\Category\Entities\BusinessCategory;
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




  
class CategoryExport extends DefaultValueBinder implements FromCollection,WithMapping,ShouldAutoSize,WithHeadings
{

    public function __construct() 
    {
      
       
    }

    public function map($category): array
    { 

        $attrs ='';
        
        if(!empty($category->categoryAttributes))
        {
            foreach($category->categoryAttributes as $cat_attr)
            {
               
                    $attrs.= $cat_attr->field_name.',';
            }

            $attrs= rtrim($attrs, ", ");
        }
      
        return [
           
            $category->name ?? '',
            $category->image ? asset('storage/app/'.$category->image) : '',
            $category->parent_name ? str_replace('>>', '', $category->parent_name) : '--',
            $attrs ?? '',
            $category->is_active == 1 ? 'Active' : 'InActive',
            
           
        ];
    }

    public function collection()
    {
       
         return $category= BusinessCategory::with('categoryAttributes')->get();
    
    }

    public function headings(): array
    {

       
        return [
            'Name',
            'Image_path',
            'Parent Name',
            'Attributes',
            'Status',
           
            
        ];


    }

  
}