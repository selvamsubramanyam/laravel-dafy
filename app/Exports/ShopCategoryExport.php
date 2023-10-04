<?php
  
namespace App\Exports;
  
use Modules\Shop\Entities\Category;
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
  
class ShopCategoryExport extends DefaultValueBinder implements FromCollection,WithMapping,ShouldAutoSize,WithHeadings
{

    public function __construct() 
    {
      
    }

    public function map($category): array
    { 
        return [
            $category->name ?? '',
            $category->icon ? asset('storage/app/'.$category->icon) : '',
            $category->is_active == 1 ? 'Active' : 'InActive',
        ];
    }

    public function collection()
    {
         return $category= Category::get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Image_path',
            'Status', 
        ];
    }

  
}