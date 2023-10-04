<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Shop\Entities\BusinessShop;
use Modules\Category\Entities\BusinessCategory;
use Modules\Admin\Entities\State;
use App\Http\Requests\SellerStoreRequest;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\SubAdminStoreRequest;
use Modules\Users\Entities\User;
use Modules\Users\Entities\UserAddress;
use Modules\Users\Entities\BusinessSellerCategory;
use Modules\Shop\Entities\BusinessCategoryShop;
use App\Http\Requests\SellerUpdateRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Requests\SubAdminUpdateRequest;
use Modules\Admin\Entities\AdminUser;
use Modules\DeliveryApp\Entities\DriverUser;
use App\Http\Requests\DriverStoreRequest;
use App\Http\Requests\DriverUpdateRequest;
use Modules\Users\Entities\Role;
use App\Exports\UserExport;
use Excel;
use Carbon\Carbon;
use Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Auth;
use Hash;



class UserController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:seller-list', ['only' => ['seller','sellerList']]);
        $this->middleware('permission:seller-create', ['only' => ['sellerAdd','sellerStore']]);
        $this->middleware('permission:seller-edit', ['only' => ['sellerEdit','sellerUpdate']]);
        $this->middleware('permission:seller-delete', ['only' => ['deleteSeller']]);

        $this->middleware('permission:customer-list', ['only' => ['customer','customerList']]);
        $this->middleware('permission:customer-create', ['only' => ['customerAdd','customerStore']]);
        $this->middleware('permission:customer-edit', ['only' => ['customerEdit','customerUpdate']]);
        $this->middleware('permission:customer-delete', ['only' => ['deleteCustomer']]);
    }

    public function additionalUser($role_slug)
    {
        $role = Role::where('slug',$role_slug)->first();

        return view('admin::Admin.subadmin',compact('role'));
    }

    public function additionalUserList(Request $request,$role_slug)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;

        $role = Role::where('slug',$role_slug)->first();

     
        
        $users = AdminUser::where('role',$role->id)->orderBy('id','desc');
          

        if ($search != '') 
        {
            
         
            $users->where('role','=',$role->id)->where(function($query) use($search){
                $query->where('name', 'LIKE', '%'.$search.'%')->orWhere('email','LIKE', '%'.$search.'%')->orWhere('mobile','LIKE', '%'.$search.'%');
            });
            
         }

        $total = $users->count();

        $result['data'] = $users->take($request->length)->skip($request->start)->get();
        
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function additionalUserAdd($role_slug)
    {
        $role = Role::where('slug',$role_slug)->first();
        
        return view('admin::Admin.addSubAdmin',compact('role'));

    }

  


    public function additionalUserStore(SubAdminStoreRequest $request)
    {

        $role = Role::where('id',$request->role_id)->first();

        if($request->hasfile('profile_image'))
        {
            $profileimage = $request->profile_image;
            $imageName = str_replace(' ', '', time()).trim($profileimage->getClientOriginalName());
            $profileimage->move(storage_path('app/admin_user'), $imageName);  
            $url_image = "admin_user/".$imageName; 
        }

        
        AdminUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'status' => $request->status,
            'image' => $url_image ?? NULL,
            'role' => $role->id,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('message', $role->name.' added Successfully.');
    }


    public function additionalUserEdit($id)
    {
        $user = AdminUser::findOrFail($id);

        $role = Role::where('id',$user->role)->first();

        return view('admin::Admin.editSubAdmin', compact('user','role'));
    }


    public function additonalUserUpdate(SubAdminUpdateRequest $request)
    {
        $user = AdminUser::where('id' , $request->id)->first();

        if($user)
        {
            if($request->password != null || $request->password != '')
            {
                $password = Hash::make($request->password);
            }else{
                $password = $user->password;
            }

            if($request->hasfile('profile_image'))
            {
                $profileimage = $request->profile_image;
                $imageName = str_replace(' ', '', time()).trim($profileimage->getClientOriginalName());
                $profileimage->move(storage_path('app/admin_user'), $imageName);  
                $url_image = "admin_user/".$imageName; 
            }else{
                $url_image = $user->image;
            }
    
            AdminUser::whereId($user->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'status' => $request->status,
                'image' => $url_image,
                'password' => $password
            ]);

            $role = Role::where('id',$user->role)->first();
        }
        return redirect()->back()->with('message', ucfirst($role->name).' updated Successfully.');
    }


    public function driver()
    {
        return view('admin::Drivers.driver');
    }

    public function driverList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;

        $users = DriverUser::orderBy('id','desc');
          

        if ($search != '') 
        {
         
     
                $users->where('name', 'LIKE', '%'.$search.'%')->orWhere('email','LIKE', '%'.$search.'%')->orWhere('mobile','LIKE', '%'.$search.'%');
            
            
        }

        $total = $users->count();

        $result['data'] = $users->take($request->length)->skip($request->start)->get();
        
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function driverAdd()
    {
        return view('admin::Drivers.addDriver');
    }


    public function driverStore(DriverStoreRequest $request)
    {
        if($request->hasfile('profile_image'))
        {
            $profileimage = $request->profile_image;
            $imageName = str_replace(' ', '', time()).trim($profileimage->getClientOriginalName());
            $profileimage->move(storage_path('app/driver_user'), $imageName);  
            $url_image = "driver_user/".$imageName; 
        }

        DriverUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country_code' => '+91',
            'alt_country_code' => '+91',
            'alt_mobile' => $request->alt_mobile,
            'is_active' => $request->status,
            'image' => $url_image ?? NULL,
            'location'  => $request->location,
            'area'  =>  $request->area,
            'build_name'   => $request->build_name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city'  => $request->city,
            'address' => $request->address,
            'is_busy' => 0
        ]);

        return redirect()->back()->with('message', 'Driver added Successfully.');
    }

    public function driverEdit($id)
    {
        $driver = DriverUser::findOrFail($id);

        return view('admin::Drivers.editDriver', compact('driver'));
    }
   
  
    public function driverUpdate(DriverUpdateRequest $request)
    {
        $user = DriverUser::where('id' , $request->id)->first();

        if($user)
        {
            
            if($request->hasfile('profile_image'))
            {
                $profileimage = $request->profile_image;
                $imageName = str_replace(' ', '', time()).trim($profileimage->getClientOriginalName());
                $profileimage->move(storage_path('app/driver_user'), $imageName);  
                $url_image = "driver_user/".$imageName; 
            }else{
                $url_image = $user->image;
            }

            DriverUser::whereId($user->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'alt_mobile' => $request->alt_mobile,
                'is_active' => $request->status,
                'image' => $url_image,
                'location'  => $request->location,
                'area'  =>  $request->area,
                'build_name'   => $request->build_name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'city'  => $request->city,
                'address' => $request->address,
            ]);
        }

        return redirect()->back()->with('message', 'Driver updated Successfully.');
    }


    public function seller()
    {
        return view('admin::Seller.seller');
    }

    public function sellerList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;

        $role = Role::where('name','seller')->first();
        
        $users = User::whereNotNull('mobile')->with('categories','userAddresses.state','roles')->orderBy('id','desc');
        
       
            $users->whereHas('roles', function ($query) use($role) {
                $query->where("user_role.role_id", "=", $role->id); //seller
                
            });
        
   
        

        if ($search != '') 
        {
            
            $users = $users->whereHas('roles', function ($query) use($role){
                $query->where("user_role.role_id", "=", $role->id); //seller
                
                });
            $users->where('name', 'LIKE', '%'.$search.'%')->orWhere('business_name','LIKE', '%'.$search.'%')->orWhere('business_email','LIKE', '%'.$search.'%')->orWhere('email','LIKE', '%'.$search.'%')->whereHas('roles', function ($query) use($role){
                $query->where("user_role.role_id", "=", $role->id); //seller
                
                })
            
          
            // ->orWhere('mobile','LIKE', '%'.$search.'%')
            // ->orWhere('business_email','LIKE', '%'.$search.'%')->orWhere('email','LIKE', '%'.$search.'%')
            ->orWhereHas('userAddresses', function($q) use($search){
                $q->where('city', 'LIKE', '%'.$search.'%');
            })->orWhereHas('userAddresses.state', function ($que) use ($search) {
                $que->where('name',  'LIKE', '%'.$search.'%');
            
            })->orWhereHas('categories', function($query) use ($search){//interested in
                $query->where('name', 'LIKE', '%'.$search.'%');
            })->where('role_id',$role->id);
        
           
        }

        $total = $users->count();

        $result['data'] = $users->take($request->length)->skip($request->start)->get();
        
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function sellerAdd()
    {
        $states = State::where('country_id',1)->where('is_active',1)->get();

        $categories = BusinessCategory::where(['parent_id' => 0])->orderBy('name')->get();
        
        return view('admin::Seller.addSeller',compact('categories','states'));
    }

    public function sellerStore(SellerStoreRequest $request)
    {

        if($request->status == 0)
            $is_active = 0;
        else
            $is_active = 1;


            if($request->hasfile('profile_image'))
            {
                $profileimage = $request->profile_image;
                $imageName = str_replace(' ', '', time()).trim($profileimage->getClientOriginalName());
                $profileimage->move(storage_path('app/sellers'), $imageName);  
                $url_image = "sellers/".$imageName; 
            }

            if($request->hasfile('business_image'))
            {
                $businessimage = $request->business_image;
                $imageName = str_replace(' ', '', time()).trim($businessimage->getClientOriginalName());
                $businessimage->move(storage_path('app/sellers'), $imageName);  
                $url_business_image = "sellers/".$imageName; 
            }

            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $user_code = '';

            for ($i = 0; $i < 10; $i++) {
                $user_code .= $characters[rand(0, $charactersLength - 1)];
            }

        $user =  User::create([
                'name' => $request->name,
                'business_name' => $request->business_name,
                'email' => $request->email,
                'business_email' => $request->business_email,
                'cc'   => +91,
                'mobile' => $request->mobile,
                'steps' => 3,
                'role_id' => 2,
                'otp_status' => 1,
                'is_active' => $is_active,
                'is_vendor' => 1,
                'image' => $url_image ?? NULL,
                'business_image' => $url_business_image ?? NULL,
                'wallet' => $request->wallet ?? 0,
                'user_code' => $user_code,
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude,
            ]);

            $user_address = new UserAddress([
                'address_type'  => 1,
                'street'        => $request->street,
                'area'          => $request->area,
                'city'          => $request->city,
                'state_id'      => $request->state_id,
                'pincode'       => $request->pincode,
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude,
                'address_for'   => 'business',

            ]);
            
            $user->userAddresses()->saveMany([$user_address]);

            $user->roles()->syncWithoutDetaching(2);//seller = 2

            if ($request->has('categories')) {

                $params = $request->categories;
                $categories = BusinessCategory::find($params);
                $user->categories()->sync($categories);
            }

            if($user)
            {
                $qrCode = new QrCode($user->id.'-'.$user->mobile);
                            $qrCode->setSize(300);
                            $qrCode->setMargin(10); 
                            $qrCode->setEncoding('UTF-8');
                            $qrCode->setWriterByName('png');
                            $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
                            $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
                            $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
                            $qrCode->setLogoSize(150, 200);
                            $qrCode->setValidateResult(false);		
                            $qrCode->setRoundBlockSize(true);
                            $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
                            header('Content-Type: '.$qrCode->getContentType());

                            $output_file = 'qr-codes/img-' . time() . '.png';
                            $qrCode->writeFile(storage_path('app/'.$output_file));
                    
                    User::whereId($user->id)->update([
                                'qr_code' => $output_file
                            ]);
            }

            return redirect()->back()->with('message', 'Seller added Successfully.');
    }

    public function sellerEdit($id)
    {
        $user = User::findOrFail($id);
        
        $userAddress = UserAddress::where('user_id',$user->id)->where('address_type',1)->first();

        $categories = BusinessCategory::where(['parent_id' => 0])->orderBy('name')->get();

        $categoryIds = array();
        
        foreach($user->categories as $cat)
        {
            $categoryIds[] = $cat->id;
        }        
        
        $states = State::where('is_active',1)->where('country_id',1)->orderBy('name')->get();

        return view('admin::Seller.editSeller', compact('user','categories','states','categoryIds','userAddress'));
    }

    public function sellerUpdate(SellerUpdateRequest $request)
    {

        $user = User::where('id' , $request->id)->first();

        if($user)
        {

            $categories = array();

            if($request->status == 0)
            $is_active = 0;
            else
            $is_active = 1;

            if($request->hasfile('profile_image'))
            {
                $profileimage = $request->profile_image;
                $imageName = str_replace(' ', '', time()).trim($profileimage->getClientOriginalName());
                $profileimage->move(storage_path('app/sellers'), $imageName);  
                $url_image = "sellers/".$imageName; 
            }

            if($request->hasfile('business_image'))
            {
                $businessimage = $request->business_image;
                $imageName = str_replace(' ', '', time()).trim($businessimage->getClientOriginalName());
                $businessimage->move(storage_path('app/sellers'), $imageName);  
                $url_business_image = "sellers/".$imageName; 
            }
            

            $user->update([
                'name' => $request->name,
                'business_name' => $request->business_name,
                'email' => $request->email,
                'business_email' => $request->business_email,
                'mobile' => $request->mobile,
                'is_active' => $is_active,
                'image' => $url_image ?? $user->image,
                'business_image' => $url_business_image ?? $user->business_image,
                'wallet' => $request->wallet ?? 0,
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude
            ]);

            //$user_address = UserAddress::whereId($user->id)->where('address_type',1)->first();
            $user_address = [
                                
                                'street'        => $request->street,
                                'area'          => $request->area,
                                'city'          => $request->city,
                                'state_id'      => $request->state_id,
                                'pincode'       => $request->pincode,
                                'latitude'      => $request->latitude,
                                'longitude'     => $request->longitude,
                                
                            ];

            $user->userAddresses()->where('user_id', $user->id)->where('address_type',1)->update($user_address);

            $shop = BusinessShop::where(['seller_id' => $user->id])->first();

            if($shop) {
                $shop->update(['latitude' => $request->latitude, 'longitude' => $request->longitude]);
            }

            $remove_cat = array();
            $prev_categories = BusinessSellerCategory::where(['user_id' => $user->id])->get();
            
            // dd($prev_categories);
            if ($request->has('categories')) {

                $params = $request->categories;
                $categories = BusinessCategory::find($params);
                // $user->categories()->sync($categories);

                if(count($prev_categories) > 0) {
                    
                    foreach ($prev_categories as $key => $prev_category) {
                        
                        $check_id = $prev_category->main_category_id;
                        // dump($prev_category->name);
                        if(!in_array($check_id, $params)) {
                            $remove_cat[] = $check_id;
                        }
                    }
                }
                // dump($shop->id);
                // dd($remove_cat);
                if(count($remove_cat) > 0 && $shop) {
                    $test = BusinessCategoryShop::where(['shop_id' => $shop->id])->whereIn('main_category_id', $remove_cat)->delete();
                    // dd($test);
                }
                // dd('ss');

                $user->categories()->sync($categories);

                // $shopCategories = BusinessCategoryShop::where(['shop_id' => $shop->id])->whereIn()->get();
            }

            if($request->mobile != $user->mobile)
            {
                $qrCode = new QrCode($user->id.'-'.$user->mobile);
                            $qrCode->setSize(300);
                            $qrCode->setMargin(10); 
                            $qrCode->setEncoding('UTF-8');
                            $qrCode->setWriterByName('png');
                            $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
                            $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
                            $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
                            $qrCode->setLogoSize(150, 200);
                            $qrCode->setValidateResult(false);		
                            $qrCode->setRoundBlockSize(true);
                            $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
                            header('Content-Type: '.$qrCode->getContentType());
 
                            $output_file = 'qr-codes/img-' . time() . '.png';
                            $qrCode->writeFile(storage_path('app/'.$output_file));
                    
                 $user->update([
                                'qr_code' => $output_file
                            ]);
            
 
             }
            
            return back()->with('message', 'Seller Updated Successfully.');

        }else{
            return back()->with('message', 'Seller not found');
        }

    }
   
   public function deleteSeller($id)
   {
        //
   }


   public function customer()
   {
       return view('admin::Customer.customer');
   }

   public function customerList(Request $request)
   {
       $search   = $request->search['value'];
       $sort     = $request->order;
       $column   = $sort[0]['column'];
       $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
       

       $role = Role::where('name','customer')->first();

       $users = User::whereNotNull('mobile')->with('userAddresses.state')->orderBy('id','desc');
       
      
           $users->whereHas('roles', function ($query) use($role){
               $query->where("role_id", "=", $role->id); //customer
               
           });
       

       if ($search != '') 
       {

            $users->whereHas('roles', function ($query) use($role){
            $query->where("user_role.role_id", "=", $role->id); //customer
            
            })->where(function($query) use($search){
                $query->where('name', 'LIKE', '%'.$search.'%')->orWhere('mobile','LIKE', '%'.$search.'%')->orWhere('email','LIKE', '%'.$search.'%')
           ->orWhere('wallet','LIKE', '%'.$search.'%');
           $query->orWhereHas('userAddresses', function($q) use($search){
                $q->where('city', 'LIKE', '%'.$search.'%');
            });
            $query->orWhereHas('userAddresses.state', function ($que) use ($search) {
                $que->where('name',  'LIKE', '%'.$search.'%');
            });
        });
         
       }

       $total = $users->count();

       $result['data'] = $users->take($request->length)->skip($request->start)->get();
       
       $result['recordsTotal'] = $total;
       $result['recordsFiltered'] =  $total;

       echo json_encode($result);
   }


   public function customerAdd()
   {
    
       return view('admin::Customer.addCustomer');
   }


   public function customerStore(CustomerStoreRequest $request)
   {


                if($request->status == 0)
                $is_active = 0;
                else
                $is_active = 1;


                if($request->hasfile('profile_image'))
                {
                    $profileimage = $request->profile_image;
                    $imageName = str_replace(' ', '', time()).trim($profileimage->getClientOriginalName());
                    $profileimage->move(storage_path('app/customers'), $imageName);  
                    $url_image = "customers/".$imageName; 
                }

                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $user_code = '';

                for ($i = 0; $i < 10; $i++) {
                    $user_code .= $characters[rand(0, $charactersLength - 1)];
                }

               

                 $user =  User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'cc'   => +91,
                    'mobile' => $request->mobile,
                    'role_id' => 3,
                    'is_active' => $is_active,
                    'image' => $url_image ?? NULL,
                    'wallet' => $request->wallet ?? 0,
                    'user_code' => $user_code
                ]);

               
                if($user)
                {
                    $qrCode = new QrCode($user->id.'-'.$user->mobile);
                                $qrCode->setSize(300);
                                $qrCode->setMargin(10); 
                                $qrCode->setEncoding('UTF-8');
                                $qrCode->setWriterByName('png');
                                $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
                                $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
                                $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
                                $qrCode->setLogoSize(150, 200);
                                $qrCode->setValidateResult(false);		
                                $qrCode->setRoundBlockSize(true);
                                $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
                                header('Content-Type: '.$qrCode->getContentType());

                                $output_file = 'qr-codes/img-' . time() . '.png';
                                $qrCode->writeFile(storage_path('app/'.$output_file));
                        
                        User::whereId($user->id)->update([
                                    'qr_code' => $output_file
                                ]);
                }

                 $user->roles()->syncWithoutDetaching(3);//customer = 2

               

                return redirect()->back()->with('message', 'Customer added Successfully.');

   }


   public function customerEdit($id)
   {
       $user = User::findOrFail($id);
       
       return view('admin::Customer.editCustomer', compact('user'));
   }


   public function customerUpdate(CustomerUpdateRequest $request)
   {

       $user = User::where('id' , $request->id)->first();



       if($user)
       {

           if($request->status == 0)
           $is_active = 0;
           else
           $is_active = 1;

           if($request->hasfile('profile_image'))
           {
               $profileimage = $request->profile_image;
               $imageName = str_replace(' ', '', time()).trim($profileimage->getClientOriginalName());
               $profileimage->move(storage_path('app/customers'), $imageName);  
               $url_image = "customers/".$imageName; 
           }


            $user->update([
               'name' => $request->name,
               'email' => $request->email,
               'mobile' => $request->mobile,
               'is_active' => $is_active,
               'image' => $url_image ?? $user->image,
               'wallet' => $request->wallet ?? 0
           ]);

           if($request->mobile != $user->mobile)
           {
               $qrCode = new QrCode($user->id.'-'.$user->mobile);
                           $qrCode->setSize(300);
                           $qrCode->setMargin(10); 
                           $qrCode->setEncoding('UTF-8');
                           $qrCode->setWriterByName('png');
                           $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
                           $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
                           $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
                           $qrCode->setLogoSize(150, 200);
                           $qrCode->setValidateResult(false);		
                           $qrCode->setRoundBlockSize(true);
                           $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
                           header('Content-Type: '.$qrCode->getContentType());

                           $output_file = 'qr-codes/img-' . time() . '.png';
                           $qrCode->writeFile(storage_path('app/'.$output_file));
                   
                $user->update([
                               'qr_code' => $output_file
                           ]);
           

            }

           return back()->with('message', 'Customer Updated Successfully.');

       }else{
           return back()->with('message', 'Customer not found');
       }

   }


   public function exportUser($user_type)
   {
        if(Auth::guard('admin')->check())
        {
            $role = Role::where('name',$user_type)->first();

            if($role)
            {
            
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                return Excel::download(new UserExport($role->id), $role->name.'_list_'.Carbon::today()->toDateString().'.xlsx');
        

            }else{
                return redirect()->back();
            }
        }else{
            return redirect()->back();

        }
   }
}
