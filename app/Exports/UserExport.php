<?php
  
namespace App\Exports;
  
use Modules\Users\Entities\User;
use Modules\Users\Entities\Role;
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




  
class UserExport extends DefaultValueBinder implements FromCollection,WithMapping,ShouldAutoSize,WithHeadings
{

    public function __construct($role_id) 
    {
      
       $this->role_id = $role_id;
       $role = Role::where('id',$role_id)->first(['name']);
       $this->role_name =   $role->name;

    }

    public function map($user): array
    { 

        $data = [];
        $attrs ='';
        
        if(!empty($user->categories))
        {
            foreach($user->categories as $user_cat)
            {
                    
                $attrs.= $user_cat->name.',';
                    
            }

            $attrs= rtrim($attrs, ", ");
        }

        $state = '';
        $city  = '';

        if(!empty($user->userAddresses))
        {
            foreach($user->userAddresses as $user_addr)
            {
                   
                if(!is_null($user_addr))
                {
                    if(!is_null($user_addr->state))
                    {
                        $state.= $user_addr->state->name.',';
                    }
                        $city.= $user_addr->city;
                }
                    
            }

            $state= rtrim($state, ", ");
            $city= rtrim($city, ", ");
        }

        if(strtolower($this->role_name) == 'seller')
        {
            $data= [
            
                $user->name ?? '',
                $user->business_name  ?? '',
                $user->image ? asset('storage/app/'.$user->image) : '',
                $user->email ?? '',
                $user->business_email ?? '',
                $user->mobile ?? '',
                $city,
                $state,
                $attrs,
                $user->is_active == 1 ? 'Active' : 'InActive',
                
            
            ];
        }

        if(strtolower($this->role_name) == 'customer')
        {
            $data= [
            
                $user->name ?? '',
                $user->image ? asset('storage/app/'.$user->image) : '',
                $user->email ?? '',
                $user->mobile ?? '',
                $city,
                $state,
                $user->is_active == 1 ? 'Active' : 'InActive',
                
            
            ];
        }

        return $data;
    }

    public function collection()
    {
       
        if(Auth::guard('admin')->check())
        {
            $role_id =  $this->role_id;
            
            if(strtolower($this->role_name) == 'seller')
            {
                return $user = User::whereNotNull('mobile')->with('categories','userAddresses.state','roles')->whereHas('roles', function ($query) use($role_id) {
                    $query->where("user_role.role_id", "=", $role_id); //seller
                    })->orderby('id','desc')->get();
            }
            if(strtolower($this->role_name) == 'customer')
            {
                return $users = User::whereNotNull('mobile')->with('userAddresses.state')->whereHas('roles', function ($query) use($role_id){
                    $query->where("role_id", "=", $role_id); //customer
                    })->orderBy('id','desc')->get();
            }
        }
       
    
    }

    public function headings(): array
    {
        $data = [];

        if(strtolower($this->role_name) == 'seller')
        {
            $data = [
                'Name',
                'Business Name',
                'Image',
                'Email',
                'Business E-mail',
                'Mobile',
                'City',
                'State',
                'Categories',
                'Status',
            
                
            ];
        }

        if(strtolower($this->role_name) == 'customer')
        {

            $data = [
                'Name',
                'Image',
                'Email',
                'Mobile',
                'City',
                'State',
                'Status',
            
                
            ];
        }

        return $data;

    }

  
}