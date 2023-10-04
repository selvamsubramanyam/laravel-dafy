<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Modules\Users\Entities\User;
use Modules\Users\Entities\UserAddress;
use Modules\Shop\Entities\Category;
use Modules\Shop\Entities\ShopCategory;
use Modules\Category\Entities\BusinessCategory;
use Modules\Product\Entities\ProductRating;
use Modules\Shop\Entities\BusinessCategoryShop;
use Modules\Category\Entities\Banner;
use Modules\Shop\Entities\BusinessShop;
use Modules\Users\Entities\BusinessSellerCategory;
use Modules\Users\Entities\UserDevice;
use Modules\Users\Entities\UserRole;
use Modules\Users\Entities\Otp;
use Modules\Users\Entities\Vote;
use Modules\Users\Entities\Viewer;
use Modules\Users\Entities\Cart;
use Modules\Users\Entities\Wishlist;
use Modules\Users\Entities\PointHistory;
use Modules\Media\Entities\Media;
use Modules\Media\Entities\ContestVideo;
use Modules\Admin\Entities\HomeContent;
use Modules\Admin\Entities\Settings;
use Modules\Admin\Entities\Country;
use Modules\Admin\Entities\Offer;
use Modules\Admin\Entities\Notification;
use Modules\Admin\Entities\NotificationCategory;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\OfferProduct;
use Modules\Product\Entities\ProductCategory;
use Modules\Admin\Entities\State;
use Modules\Admin\Entities\City;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use DateTime;
use DB;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Support\Str;
use Modules\Api\Http\Controllers\BusinessApiController;
use Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use SiteHelper;

class CustomerApiController extends Controller
{
    
    //Paginate Function
    public function paginate($data, $paginate, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $data = $data instanceof Collection ? $data : Collection::make($data);
        return new LengthAwarePaginator($data->forPage($page, $paginate)->values(), $data->count(), $paginate, $page);
    }

    protected $BusinessApiController;
    public function __construct(BusinessApiController $BusinessApiController)
    {
        $this->BusinessApiController = $BusinessApiController;
    }

    //user login when skipped
    public function skipLogin(Request $request)
    {
        $data = array();

        $user = User::create([
                'is_active' => 1
            ]);
        
        $data['user_id'] = $user->id;

        $res = array(
            'errorcode' => 0,
            'data' => $data,
            'message' => "Success!"
        );

        return response()->json($res);
    }
    
    //user login
    public function login(Request $request)
    {
        $rules = array(
            // 'countryCode' => 'required|numeric',
            'phone_number' => 'required|numeric',
            'device_type' => 'required|integer|in:1,2',
            'device_token' => 'required|string',
            'preuser_id' => 'sometimes|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $flag = true;
            $userOtp = rand(1000, 9999);

            if($request->phone_number == '1010101010') {
                $userOtp = 1111;
                $user = User::where(['mobile' => '1010101010'])->first();

                if(!$user) {
                    $user = User::create(['mobile' => '1010101010']);

                    UserRole::create([
                            'user_id' => $user->id,
                            'role_id' => 3
                        ]);
                }   
            } else {
                $user = User::where(['mobile' => $request->phone_number])->first();//'cc' => $request->countryCode, 

                if($user) {
                    
                    if($user->is_active == 0 && $user->name != null) {
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Your account is currently inactive, Please contact admin!"
                        );

                        return response()->json($res);
                    }
                }
                

                if(!$user) {

                    if($request->preuser_id != null) {

                        $user = User::where(['id' => $request->preuser_id, 'mobile' => null])->first();
                        
                        if($user) {

                            $user->update([
                                // 'cc' => $request->countryCode, 
                                'mobile' => $request->phone_number, 
                                // 'latitude' => $preuser->latitude, 
                                // 'longitude' => $preuser->longitude,
                                // 'city_id' => $preuser->city_id,
                                'role_id' => 3,
                                'is_active' => 0
                            ]);

                            $data['preuser_id'] = $user->id;
                        } else {
                            $user = User::create([/*'cc' => $request->countryCode, */'mobile' => $request->phone_number]);
                        }
                    } else {
                        
                        $user = User::create([/*'cc' => $request->countryCode, */'mobile' => $request->phone_number]);
                        
                    }

                    UserRole::create([
                            'user_id' => $user->id,
                            'role_id' => 3
                        ]);
                } else {

                    if($request->preuser_id != null) {
                        $cart_items = Cart::where(['user_id' => $user->id])->get();
                        $preuser_cart_items = Cart::where(['user_id' => $request->preuser_id])->get();

                        if(count($cart_items) > 0) {
                            
                            foreach ($cart_items as $key => $cart_item) {
                                
                                foreach ($preuser_cart_items as $key => $preuser_cart_item) {
                                   
                                   if($cart_item->product_id == $preuser_cart_item->product_id) {
                                        $cart_item->update(['quantity' => $preuser_cart_item->quantity + $preuser_cart_item->quantity]);

                                        $preuser_cart_item->delete();
                                   } else {
                                        $preuser_cart_item->update(['user_id' => $user->id]);
                                   }
                                }
                            }
                        } else {

                            foreach ($preuser_cart_items as $key => $preuser_cart_item) {
                                $preuser_cart_item->update(['user_id' => $user->id]);
                            }
                        }


                    } 
                }
            } 

            $otp = Otp::where(['user_id' => $user->id])->first();

            if($otp) {
                $otp->update(['otp' => $userOtp, 'status' => 0]);
            } else {
                $otp = Otp::create([
                    'user_id' => $user->id,
                    'otp' => $userOtp,
                    'status' => 0
                ]);
            }

            UserDevice::create([
                    'user_id' => $user->id,
                    'device_type' => $request->device_type,
                    'device_id' => $request->device_token,
                    'login_time' => date('Y-m-d H:i:s'),
                ]);

            $name = $user->name;

            if($user->name == null)
                $name = '';

            $data['otp'] = $userOtp;
            $data['user_id'] = $user->id;
            $data['name'] = $name;
            $data['phone_number'] = $request->phone_number;
            $data['email'] = $user->email;

            if(!$user->name)
                $flag = false;

            if($user->business_email == null) {
                $vendor_flag = false;
                $data['isExisting'] = $flag;
            } else {
                $vendor_flag = true;

                if($user->steps == 3)
                    $flag = true;
                else
                    $flag = false;

                $data['isExisting'] = $flag;
            }

            $data['isVendor'] = $vendor_flag;

            $apiKey = urlencode('q59xlcpmmhI-09rjL4xmNPQalKFpxthozVj58xCIA0');
    
            // Message details

            if($request->countryCode == '+91') {
                // $numbers = array($request->phone_number);
                // $sender = urlencode('PIPILI');
                // $message = rawurlencode('Your login otp is '.$userOtp);
             
                // $numbers = implode(',', $numbers);
             
                // Prepare data for POST request
                // $detail = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
                // dd($detail);
                // Send the POST request with cURL
                // $ch = curl_init('https://api.textlocal.in/send/');
                // curl_setopt($ch, CURLOPT_POST, true);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, $detail);
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // $response = curl_exec($ch);
                // curl_close($ch);

                // $sms_content = $userOtp.' is your login OTP for Dafy z+lCth5uam5';
                // $temp_id = '1307161520816203526';
                $sms_content = 'Dear customer, '.$userOtp.' is your login OTP for DAFY. Please do not share this OTP with anyone. Happy shopping.';

                $url1 = 'thesmsbuddy.com/api/v1/sms/send?key=YEpXB7CZtP3q0nA1lQJOC75kG94jSlWd&type=1&to='.$request->phone_number.'&sender=KLDAFY&message='.urlencode($sms_content).'&flash=0&template_id=1307161520816203526';
                $response = '';
                $ch = curl_init();
                curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url1
                ));
                $response = curl_exec($ch);

                curl_close ($ch);
            } 
            else {
                // $account_sid = 'ACc6796cf7f2ea5ab95186b0b2322d252f';
                // $auth_token = '9dd79581931686e4e7f92f8543cbc27e';

                // $twilio_number = '+17784034827';
                // $to = $request->countryCode.$request->phone_number;

                // $client = new Client($account_sid, $auth_token);

                // $client->messages->create($to,
                //     array(
                //         'from' => $twilio_number,
                //         'body' => 'Your login otp is '.$userOtp
                //     )
                // );

            }
            
            // Process your response here
            // echo $response;

            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );
        }

        return response()->json($res);
    }

    //To send otp again to user
    public function resendOtp(Request $request)
    {
        $rules = array(
            // 'countryCode' => 'required|numeric',
            'phone_number' => 'required|numeric',
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $flag = false;

            $user = User::where(['mobile' => $request->phone_number])->first();
            
            if($user) {
                $userid = $user->id;

                if($user->name)
                    $flag = true;
            }else {
                $userid = $request->userid;
            }

            // if($user) {
                // $userOtp = rand(1000, 9999);
                
                // Otp::where(['user_id' => $userid])->update(['otp' => $userOtp, 'cc' => $request->countryCode, 'edit_mobile' => $request->phone_number, 'status' => 0]);

                $userOtp = Otp::where(['user_id' => $userid])->first();

                $data['otp'] = $userOtp->otp;

                $data['isExistingUser'] = $flag;

                $apiKey = urlencode('q59xlcpmmhI-09rjL4xmNPQalKFpxthozVj58xCIA0');
                // Message details

                if($request->countryCode == '+91') {
                    // $temp_id = '1307161520816203526';
                    // $sms_content = $data['otp'].' is your login OTP for Dafy z+lCth5uam5';

                    $sms_content = 'Dear customer, '.$userOtp.' is your login OTP for DAFY. Please do not share this OTP with anyone. Happy shopping.';

                    $url1 = 'thesmsbuddy.com/api/v1/sms/send?key=YEpXB7CZtP3q0nA1lQJOC75kG94jSlWd&type=1&to='.$request->phone_number.'&sender=KLDAFY&message='.urlencode($sms_content).'&flash=0&template_id=1307161520816203526';

                    $response = '';
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url1
                    ));
                    $response = curl_exec($curl);

                    curl_close ($curl);
                } else {
                    // $account_sid = 'ACc6796cf7f2ea5ab95186b0b2322d252f';
                    // $auth_token = '9dd79581931686e4e7f92f8543cbc27e';

                    // $twilio_number = '+17784034827';
                    // $to = $request->countryCode.$request->phone_number;

                    // $client = new Client($account_sid, $auth_token);

                    // $client->messages->create($to,
                    //     array(
                    //         'from' => $twilio_number,
                    //         'body' => 'Your login otp is '.$userOtp
                    //     )
                    // );
                }
                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            // } else {
            //     $res = array(
            //         'errorcode' => 1,
            //         'data' => (object)[],
            //         'message' => "User not exist!"
            //     );
            // }
        }
        return response()->json($res);
    }

    //To verify the updated data
    public function verifyOtp(Request $request)
    {
        $rules = array(
            'userid' => 'required|integer',
            'otp' => 'required|integer|digits:4',
            'preuserid' => 'sometimes|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->userid])->first();

            if($user) {
                $flag = true;
                $otp = Otp::where(['user_id' => $request->userid])->first();

                if($otp) {
                    $is_vendor = false;
                    
                    if($request->otp == $otp->otp) {
                        $steps = $user->steps == null ? 0 : $user->steps;

                        if($user->is_vendor != 0) {
                            if($user->steps == 3)
                                $flag = true;
                            else
                                $flag = false;

                        $is_vendor = true;

                        } else {
                            
                            if($user->name != null)
                                $flag = true;
                            else
                                $flag = false;
                        }
                        
                        

                        // if($otp->edit_mobile == null) {
                        //     $otp->update(['status' => 1]);
                        //     $user->update(['is_active' => 1]);
                        // } else {
                        $otp->update(['status' => 1]);
                        $user->update([
                                // 'cc' => $otp->cc,
                                'is_active' => 1
                            ]);
                        // }

                        $name = $user->name;

                        if($user->name == null)
                            $name = '';

                        $data['isExistingUser'] = $flag;
                        $data['isVendor'] = $is_vendor;
                        $data['steps'] = $steps;
                        $data['userid'] = $user->id;
                        $data['countryCode'] = $user->cc;
                        $data['phoneNumber'] = $user->mobile;
                        $data['name'] = $name;

                        
                        // if($request->preuserid != null) {

                        //     User::where(['user_id' => $request->preuserid])->update(['user_id' => $user->id]);
                            
                        //     User::where(['id' => $request->preuserid])->delete();
                        // }

                        $res = array(
                            'errorcode' => 0,
                            'data' => $data,
                            'message' => "Success!"
                        );

                    } else {
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Please enter the correct otp number!"
                        );
                    }
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Something went wrong!"
                    );
                }

            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

   //User Registration
    public function register(Request $request)
    {
        $rules = array(
            'userid' => 'required|integer',
            'name' => 'required|max:255',
            'email' => 'nullable|email|unique:users,email,NULL,id,deleted_at,NULL',
            'isVendor' => 'required|in:0,1'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data['steps_finished'] = 0;
            $referral_by = null;

            $user = User::where(['id' => $request->userid])->first();
            
            if($user) {

                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $user_code = '';

                for ($i = 0; $i < 10; $i++) {
                    $user_code .= $characters[rand(0, $charactersLength - 1)];
                }

                //Qr code section
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
                //End of Qr code

                if($request->isVendor == 0) {
                    
                    if($user->name == null) {

                        if($request->referral_code != null) {

                            $check_referral = User::where(['user_code' => $request->referral_code, 'is_active' => 1])->first();

                            if($check_referral) {
                                $referral_by = $check_referral->id;
                                
                                $credited_valid_point = Settings::where(['slug' => 'referral_earning_when_registration'])->first();

                                if($credited_valid_point)
                                    $credited_valid_point = $credited_valid_point->value;
                                else
                                    $credited_valid_point = 0;

                                $crediting_valid_point = Settings::where(['slug' => 'referral_earning_when_purchase'])->first();

                                if($crediting_valid_point)
                                    $crediting_valid_point = $crediting_valid_point->value;
                                else
                                    $crediting_valid_point = 0;

                                PointHistory::create([
                                    'user_id' => $request->userid,
                                    'from_id' => $check_referral->id,
                                    'is_credit' => 1,
                                    'points' => $credited_valid_point,
                                    'slug' => 'credited_by_referring_customer'
                                ]);

                                PointHistory::create([
                                    'user_id' => $check_referral->id,
                                    'from_id' => $request->userid,
                                    'is_credit' => 1,
                                    'points' => $crediting_valid_point,
                                    'slug' => 'credited_by_purchase_referred_customer',
                                    'is_valid' => 0
                                ]);

                                $message = array(
                    
                                    'message' => 'Referral code earned.'
                                  );
                
                                  $device_types=UserDevice::where('user_id',$request->userid)->where('device_type',1)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
                
                                  if (!empty($device_types))
                                  SiteHelper::sendAndroidPush($device_types, $message);
                
                                //   $iosdevice=UserDevice::where('user_id',$request->user_id)->where('device_type',2)->where('logout_time','=',NULL)->pluck('device_id')->toArray();
                
                                  // if (!empty($iosdevice)) 
                                  // SiteHelper::sendIosPush($iosdevice, $message);
                                

                                $user->update(['wallet' => $user->wallet + $credited_valid_point]);

                            } else {
                                $res = array(
                                    'errorcode' => 1,
                                    'data' => (object)[],
                                    'message' => "Invalid Referral code!"
                                );

                                return response()->json($res);
                            }
                        }

                        $user->update([
                            'name' => $request->name, 
                            'email' => $request->email, 
                            'user_code' => $user_code,
                            'qr_code' => $output_file,
                            'referral_by' => $referral_by,
                            'is_active' => 1
                        ]);

                        $res = array(
                            'errorcode' => 0,
                            'data' => $data,
                            'message' => "Success!"
                        );
                    }
                    
                    $steps = $user->steps == null ? 0 : $user->steps;

                    $user->update(['name' => $request->name, 'steps' => $steps, 'is_vendor' => $request->isVendor, 'user_code' => $user_code, 'qr_code' => $output_file]);
                    
                    $data['steps_finished'] = $user->steps == null ? 0 : $user->steps;
                    
                    $res = array(
                            'errorcode' => 0,
                            'data' => $data,
                            'message' => "Success!"
                        );
                } else {
                    $steps = $user->steps == null ? 0 : $user->steps;

                    $user->update(['name' => $request->name, 'steps' => $steps, 'is_vendor' => $request->isVendor, 'user_code' => $user_code, 'qr_code' => $output_file]);
                    
                    $data['steps_finished'] = $user->steps == null ? 0 : $user->steps;
                    
                    $res = array(
                            'errorcode' => 0,
                            'data' => $data,
                            'message' => "Success!"
                        );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => $data,
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }

    //switch account to vendor
    public function switchAccountVendor(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id])->first();
            
            if($user) {

                if($user->is_vendor == 1) {
                    $data['isVendor'] = true;
                }
                else {
                    $data['isVendor'] = false;
                }
                
                $data['steps'] = $user->steps;

                $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );            
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }

    //To get Invitation Code
    public function getInvitationCode(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id])->where('user_code', '!=', null)->first();

            if($user) {
                $data = $user->user_code;
                
                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );

            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not registered!"
                );
            }
        }
        return response()->json($res);
    }

    //Add seller details
    public function addBusinessDetails(Request $request)
    {
        $rules = array(
            'business_name' => 'required',
            'business_email' => 'required|email',
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id])->first();
            
            if($user) {
                $steps = $user->steps == 0 ? 1 : $user->steps;
                
                $user->update([
                    'business_name' => $request->business_name,
                    'business_email' => $request->business_email,
                    'steps' => $steps
                ]);

                $seller_category_ids = $request->business_category_id;
                BusinessSellerCategory::where(['user_id' => $request->user_id])->delete();

                foreach ($seller_category_ids as $key => $seller_category_id) {
                    
                    BusinessSellerCategory::create([
                        'user_id' => $request->user_id, 
                        'main_category_id' => $seller_category_id
                    ]);
                }

                UserRole::create([
                    'user_id' => $request->user_id,
                    'role_id' => 2
                ]);

                $res = array(
                    'errorcode' => 0,
                    'data' => (object)[],
                    'message' => "Success!"
                );
                
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }

        return response()->json($res);
    }

    //To get seller details
    public function getBusinessDetails(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $data = array();
            $business_categories = array();

            $user = User::where(['id' => $request->user_id])->where('business_email', '!=', null)->first();
            
            if($user) {
                $data['name'] = $user->business_name;
                $data['email'] = $user->business_email;

                $seller_categories = BusinessSellerCategory::where(['user_id' => $request->user_id])->get();

                foreach ($seller_categories as $key => $seller_category) {
                    $business_categories[] = array(
                                            'business_category_id' => $seller_category->main_category_id,
                                            'business_category_title' => $seller_category->getCategory->name
                                        );
                }

                $data['categories'] = $business_categories;

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
                
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => $data,
                    'message' => "User not exist!"
                );
            }
        }

        return response()->json($res);
    }


    //To get business categoies
    public function getBusinessCategories(Request $request)
    {
        $data = array();
        $business_categories = BusinessCategory::where(['parent_id' => 0, 'is_active' => 1])->withTrashed()->orderBy('order', 'ASC')->get();
        
        foreach ($business_categories as $key => $business_category) {
            $data[] = array(
                        'business_cat_id' => $business_category->id,
                        'business_cat_title' => $business_category->name
                    );
        }

        $res = array(
            'errorcode' => 0,
            'data' => $data,
            'message' => "Success!"
        );

        return response()->json($res);
    }

    //To add business location
    public function addBusinessLocation(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'street_name' => 'required',
            'area' => 'required',
            'city' => 'required',
            'state_id' => 'required|integer',
            'pincode' => 'required|numeric'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $user = User::where(['id' => $request->user_id])->where('business_email', '!=', null)->first();
            
            if($user) {
                $business_address = UserAddress::where(['user_id' => $request->user_id, 'address_type' => 1])->first();

                if($business_address) {
                    $business_address->update([
                        'street' => $request->street_name,
                        'area' => $request->area,
                        'city' => $request->city,
                        'state_id' => $request->state_id,
                        'pincode' => $request->pincode,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude
                    ]);
                } else {
                    UserAddress::create([
                        'user_id' => $request->user_id,
                        'address_type' => 1,
                        'street' => $request->street_name,
                        'area' => $request->area,
                        'city' => $request->city,
                        'state_id' => $request->state_id,
                        'pincode' => $request->pincode,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude
                    ]);

                    $user->update(['steps' => 2]);
                }

                $user->update(['latitude' => $request->latitude, 'longitude' => $request->longitude]);
                
                $res = array(
                    'errorcode' => 0,
                    'data' => (object)[],
                    'message' => "Success!"
                );
                
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }

        return response()->json($res);
    }

    //To update business location
    public function postCurrentLocation(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'latitude' => 'required',
            'longitude' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first(); //'business_email' => null
            
            if($user) {
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $max_delivery_distance = Settings::where(['slug' => 'max_delivery_distance'])->first();

                $city = City::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                ->having('distance', '<', $max_delivery_distance->value)
                // ->where('name', 'LIKE', '%' . $request->search . '%')
                // ->where('id', $shop->shop_id)
                ->where('is_active', 1)
                // ->whereIn('id', $shops)
                // ->orderByRaw("FIELD(type , 'Pre', 'Gen') ASC")
                ->orderBy('distance')
                // ->take(5)
                ->first();
                
                if($city) {
                    $user->update([
                        'latitude' => $latitude,
                        'longitude' => $longitude
                    ]);

                    $res = array(
                        'errorcode' => 0,
                        'data' => (object)[],
                        'message' => "Success!"
                    );
                } else {
                    // $cities = City::where(['is_active' => 1])->get()->pluck('name')->toArray();
                    $cities = array('Ernakulam');
                    
                    if($cities) {
                        $cities = implode(', ', $cities);
                    } else {
                        $cities = (object)[];
                    }

                    $data['available_cities'] = $cities;

                    $res = array(
                        'errorcode' => 1,
                        'data' => $data,
                        'message' => "Service not available!"
                    );
                }
                    
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }

        return response()->json($res);
    }

    //To get business location
    public function getBusinessLocation(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $user = User::where(['id' => $request->user_id])->where('business_email', '!=', null)->first();
            
            if($user) {
                $business_location = UserAddress::where(['user_id' => $request->user_id, 'address_type' => 1])->first();

                if($business_location) {
                    $data['streetname'] = $business_location->street;
                    $data['area'] = $business_location->area;
                    $data['city'] = $business_location->city;
                    $data['state_id'] = $business_location->state_id;
                    $data['state'] = $business_location->getState->name;
                    $data['pincode'] = $business_location->pincode;
                    $data['latitude'] = $business_location->latitude;
                    $data['longitude'] = $business_location->longitude;

                    $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Location not exist!"
                    );
                }
                
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }

        return response()->json($res);
    }

    //To add business image
    public function addBusinessImage(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'image' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $user = User::where(['id' => $request->user_id])->where('business_email', '!=', null)->first();
            
            if($user) {
                $business_name = Str::slug($user->business_name, '_');

                if($request->file('image')){
                    $imageName = time().trim($request->image->getClientOriginalName());
                    $imageName = str_replace(' ', '', $imageName); 
                    $request->image->move(storage_path('app/sellers/'.$business_name.''), $imageName); 
                    $image = 'sellers/'.$business_name.'/'.$imageName;
                }

                $user->update(['steps' => 3, 'business_image' => $image,'is_active' => 1]);

                $res = array(
                    'errorcode' => 0,
                    'data' => (object)[],
                    'message' => "Success!"
                );
                
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }

        return response()->json($res);
    }

    //To get User Home
    public function dashboard(Request $request)
    {
        $rules = array(
            'user_id' => 'sometimes|required|integer'   
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            $data = array();
            $categories = array();
            $banners = array();
            $shops_near_you = array();
            $todays_hot_deal = array();
            $hot_deals = array();
            $new_arrivals = array();

            $latitude = $request->latitude;
            $longitude = $request->longitude;

            // $business_categories = BusinessCategory::where(['parent_id' => 0, 'is_active' => 1])->withTrashed()->get();

            // foreach ($business_categories as $key => $business_category) {
            //     $categories[] = array(
            //         'cat_id' => $business_category->id,
            //         'cat_image' => $business_category->image == null ? null : asset('storage/app').'/'.$business_category->image,
            //         'cat_title' => $business_category->name
            //     );
            // }

            $business_categories = Category::where(['is_active' => 1])->orderBy('order', 'ASC')->get();

            foreach ($business_categories as $key => $business_category) {
                $categories[] = array(
                    'cat_id' => $business_category->id,
                    'cat_image' => $business_category->icon == null ? null : asset('storage/app').'/'.$business_category->icon,
                    'cat_title' => $business_category->name
                );
            }

            $business_banners = Banner::whereDate('valid_from', '<=', Carbon::now())->whereDate('valid_to', '>=', Carbon::now())->where(['is_active' => 1])->where('image', '!=', null)->get();

            foreach ($business_banners as $key => $business_banner) {
                $product_id = 0;
                $shop_id = 0;
                $isShop = false;
                $isProduct = false;
                $is_banner = false;

                if($business_banner->shop_id != null && $business_banner->product_id == null) {

                    $shop = BusinessShop::where(['id' => $business_banner->shop_id, 'is_active' => 1])->first();

                    if($shop) {
                        $isShop = true;
                        $is_banner = true;
                        $isProduct = false;
                        $shop_id = $business_banner->shop_id;
                    }
                    
                }
                else {
                    $isShop = false;
                    $is_banner = true;
                    if($business_banner->product_id != null)
                    {   
                        $isProduct = true;
                        $product_id = $business_banner->product_id;
                    }else{
                        $isShop = false;
                        $isProduct = false;
                    }
                }

                if($is_banner == true) {

                    $banners[] = array(
                        'banner_id' => $business_banner->id,
                        'banner_image' => $business_banner->image == null ? null : asset('storage/app').'/'.$business_banner->image,
                        'isShop' => $isShop,
                        'isProduct' => $isProduct,
                        'product_id' => $product_id,
                        'shop_id' => $shop_id
                    );
                }
                
                
            }

            $distance = Settings::where(['slug' => 'min_distance'])->first();

            if($user) {
                $latitude = $latitude == null ? $user->latitude : $latitude;
                $longitude = $longitude == null ? $user->longitude : $longitude;
            }

            if($latitude != null && $longitude != null) {

                $shop_value = BusinessShop::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                    ->having('distance', '<', $distance->value)
                    // ->where('name', 'LIKE', '%' . $request->search . '%')
                    // ->where('id', $shop->shop_id)
                    ->where('is_active', 1)
                    // ->whereIn('id', $shops)
                    // ->orderByRaw("FIELD(type , 'Pre', 'Gen') ASC")
                    ->orderBy('distance')
                    ->take(5)
                    ->get();

                foreach ($shop_value as $key => $shop) {
                    // $premium = false;
                    $image = null;
                    
                    if(count($shop->images) > 0)
                       $image = $shop->images[0]->image;

                    // if($shop->type == 'Pre')
                    //     $premium = true;

                    $shops_near_you[] = array(
                        'shop_id' => $shop->id,
                        'shop_title' => $shop->name,
                        'shop_image' => $shop->image == null ? null : asset('storage/app').'/'.$shop->image,
                        'categories' => $this->BusinessApiController->getShopCategories($shop->id)
                        // 'shopLocation' => $shop->location,
                        // 'categoryId' => $category->id,
                        // 'premium' => $premium
                    );
                }
            }

            $offers = Offer::whereHas('shop', function($q) {
                $q->where('is_active', 1);
            })->whereDate('valid_from', '<=', Carbon::now())->whereDate('valid_to', '>=', Carbon::now())->where(['status' => 1])->get();

            foreach ($offers as $key => $offer) {

                if($offer->image != null) {

                    $todays_hot_deal[] = array(
                        'offer_id' => $offer->id,
                        'offer_image' => $offer->image == null ? null : asset('storage/app').'/'.$offer->image
                    );
                }
                

                $offer_products = $offer->offerProducts->pluck('product_id')->toArray();
                
                $offer_shop_products = Product::whereHas('shop', function($q) {
                    $q->where('is_active', 1);
                })->whereHas('offerShops', function($q) {
                    $q->where('type', 1);
                    $q->whereHas('offerData', function($query) {
                        $query->whereDate('valid_from', '<=', Carbon::now());
                        $query->whereDate('valid_to', '>', Carbon::now());
                        $query->where('status', 1);
                    });
                })->get()->pluck('id')->toArray();

                if(count($offer_products) > 0 || count($offer_shop_products) > 0)
                    $products = array_merge($offer_products, $offer_shop_products);

                $offer_product_datas = Product::whereHas('shop', function($q) {
                    $q->where('is_active', 1);
                })->whereIn('id', $products)->where('stock', '!=', 0)->where('type', '!=', 2)->where(['is_active' => 1,'is_approved' => 1])->limit(6)->get();

                foreach ($offer_product_datas as $key => $offer_product_data) {

                    if($offer->discount_type == 1) {
                        $new_price = ($offer_product_data->price - ($offer_product_data->price * $offer->discount_value) / 100);
                    } else {
                        $new_price = $offer_product_data->price - $offer->discount_value;
                    }

                    $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $offer_product_data->id])->first();

                    if($wishlist)
                        $isWishlist = true;
                    else
                        $isWishlist = false;

                    if($offer_product_data->shop) {

                        if($offer_product_data->shop->sellerInfo)
                            $business_name = $offer_product_data->shop->name; 
                    } else {
                        $business_name = null;   
                    }
                    if(!$this->whatever($hot_deals,'product_id',$offer_product_data->id))
                    {   
                        $product_data = $this->BusinessApiController->offerPrice($offer_product_data->id);

                        if($product_data['new_price'] != null) {
                            $new_price = number_format($product_data['new_price'], 2);
                            $percent_off = $product_data['percent_off'].'%';
                        } else {
                            $new_price = null;
                            $percent_off = null;
                        }

                        $hot_deals[] = array(
                            'product_id' => $offer_product_data->id,
                            'product_brand' => $offer_product_data->brand->name ?? '',
                            'product_image' => $offer_product_data->thump_image == null ? null : asset('storage/app').'/'.$offer_product_data->thump_image,
                            'product_title' => $offer_product_data->name,
                            'seller_info' => $business_name,
                            'product_description' => strip_tags($offer_product_data->description),
                            'new_price' => $new_price,
                            'old_price' => number_format($offer_product_data->price, 2),
                            'percent_off' => $percent_off,
                            'isWishlist' => $isWishlist,
                            'type' => $product_data['type']
                        ); 
                    }
                }
            }
       
            $date = Carbon::today()->subDays(7);
            $latest_products = Product::whereHas('shop', function($q) {
                $q->where('is_active', 1);
            })->where('type', '!=', 2)->where('stock', '!=', 0)->where(['is_active' => 1,'is_approved' => 1])->orderBy('id', 'desc')->take(20)->get();

            foreach ($latest_products as $key => $latest_product) {
                 $percent_off = null;
                 $new_price = null;

                $product_data = $this->BusinessApiController->offerPrice($latest_product->id);
                
                if($product_data['new_price'] != null) {
                    $new_price = number_format($product_data['new_price'], 2);
                    $percent_off = $product_data['percent_off'].'%';
                } else {
                    $new_price = null;
                    $percent_off = null;
                }

                $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $latest_product->id])->first();

                if($wishlist)
                    $isWishlist = true;
                else
                    $isWishlist = false;

                if($latest_product->shop) {

                    if($latest_product->shop->sellerInfo)
                        $business_name = $latest_product->shop->name; 
                } else {
                    $business_name = null;   
                }

                $exist_parent_category = null;
                $check_category = 0;

                $exist_category = ProductCategory::where(['product_id' => $latest_product->id, 'shop_id' => $latest_product->seller_id])->whereHas('categoryData')->first();

                if($exist_category) {
                    $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();

                    if($exist_sub_category) {
                        $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                        if($exist_parent_category) {
                            $check_category = 1;
                        }
                    }
                }

                if($check_category == 1 && count($new_arrivals) <= 5) {

                    $new_arrivals[] = array(
                        'product_id' => $latest_product->id,
                        'product_brand' => $latest_product->brand->name ?? '',
                        'product_image' => $latest_product->thump_image == null ? null : asset('storage/app').'/'.$latest_product->thump_image,
                        'product_title' => $latest_product->name,
                        'seller_info' => $business_name,
                        'product_description' => strip_tags($latest_product->description),
                        'new_price' => $new_price,
                        'old_price' => number_format($latest_product->price, 2),
                        'percent_off' => $percent_off,
                        'isWishlist' => $isWishlist,
                        'type' => $product_data['type']
                    );
                }
                
            }

            $data['categories'] = $categories;
            $data['banners'] = $banners;
            $data['shops_near_you'] = $shops_near_you;
            $data['todays_hot_deal'] = $todays_hot_deal;
            $data['hot_deals'] = $hot_deals;
            $data['new_arrivals'] = $new_arrivals;

            $home_celebrations = HomeContent::where(['slug' => 'celebrations'])->first();

            if($home_celebrations) {

                if($home_celebrations->is_active == 1)
                    $isAvailable = true;
                else
                    $isAvailable = false;

                $celebrations = array(
                    'isAvailable' => $isAvailable,
                    'main_heading' => $home_celebrations->description,
                    'best_cakes_text' => $home_celebrations->sub_decription1,
                    'special_gifts_text' => $home_celebrations->sub_decription2
                );

                $data['celebrations'] = $celebrations;
            }

            $home_dafy = HomeContent::where(['slug' => 'dafy'])->first();

            if($home_dafy) {

                if($home_dafy->is_active == 1)
                    $isAvailable = true;
                else
                    $isAvailable = false;

                $dafy = array(
                    'isAvailable' => $isAvailable,
                    'main_heading' => $home_dafy->description,
                    'buy_anythingforyou_text' => $home_dafy->sub_decription1,
                    'deliver_anythingforyou_text' => $home_dafy->sub_decription2
                );

                $data['dafy'] = $dafy;
            }

            if($user) {
                $cart_count = Cart::where(['user_id' => $request->user_id])->get()->count();
                $not_array = [8, 9 , 10, 20];
                $notification_count = Notification::where(['to_id' => $request->user_id, 'is_view' => 0])->where('order_id', '!=', null)->whereNotIn('notification_id', $not_array)->whereHas('notificationCategory', function($q) {
                    $q->where('slug', '!=', 'shop_inactive');
                })->get()->count();

                $data['notification_count'] = $notification_count;
                $data['cart_count'] = $cart_count;
            }
            
            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );
        }
        return response()->json($res);
    }

    function whatever($array, $key, $val) {
        foreach ($array as $item)
            if (isset($item[$key]) && $item[$key] == $val)
                return true;
        return false;
    }


    //To set customer location
    public function postLocation(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'city_id' => 'required'  
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id,  'is_active' => 1])->first(); //'business_email' => null

            if($user) {
                
                $user->update([
                        'city_id' => $request->city_id
                    ]);

                $res = array(
                    'errorcode' => 0,
                    'data' => (object)[],
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }

    //To get Media Dashboard
    public function mediaDashboard(Request $request)
    {
        $rules = array(
            'userid' => 'required|integer'   
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->userid, 'is_active' => 1])->first();

            if($user) {
                $data = array();
                $date_array = array();

                $post_dates = Media::where(['is_active' => 1])->orderBy('created_at', 'desc')->get();

                foreach ($post_dates as $key => $date) {
                    $date_array[] = Carbon::parse($date->created_at)->format('Y-m-d');
                }
                $post_dates = array_unique($date_array);
                
                foreach ($post_dates as $key => $post_date) {
                    $categories = array();
                    $po_date = new DateTime($post_date);
                    $date = $po_date->format('d').' '.$po_date->format('M').' '.$po_date->format('Y');

                    $medias = Media::whereDate('created_at', $post_date)->where(['is_active' => 1])->orderBy('created_at', 'desc')->get();

                    foreach ($medias as $key => $media) {
                        $me_date = new DateTime($media->to_date);
                        $media_date = $me_date->format('d').' '.$me_date->format('M').' '.$me_date->format('Y');
                        
                        $categories[] = array(
                            'thumbnailUrl' => $media->thumbnail_url == null ? null : asset('storage/app').'/'.$media->thumbnail_url,
                            'id' => $media->id,
                            'Title' => $media->title,
                            'End_Date' => $media_date,
                            'shareUrl' => $media->share_url
                        );
                    }
                    
                    $data[] = array(
                        'Posted_Date' => $date,
                        'categories' => $categories
                    );
                }
                // $data = array_unique($data,SORT_REGULAR);

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            } else {
               $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                ); 
            }

        }
        return response()->json($res);
    }

    //To get Media Contest Detail
    public function contestDetail(Request $request)
    {
        $rules = array(
            'userid' => 'required|integer',
            'categoryid' => 'required|integer'   
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->userid, 'is_active' => 1])->first();

            if($user) {

                $media = Media::where(['id' => $request->categoryid, 'is_active' => 1])->first();

                if($media) {
                    $contest_videos = ContestVideo::where(['media_id' => $media->id, 'is_active' => 1])->orderBy('votes', 'desc')->get()->take(5);
                    $videos = array();
                    
                    foreach ($contest_videos as $key => $video) {
                        $videos[] = array(
                                'id' => $video->id,
                                'Title' => $video->title,
                                'Vote' => $video->votes,
                                'thumbnailUrl' => $video->thumbnail_url == null ? null : asset('storage/app').'/'.$video->thumbnail_url,
                                // 'view_count' => $video->view_count
                            );
                    }
                    $end_date = new DateTime($media->to_date);
                    $date = $end_date->format('d').' '.$end_date->format('M').' '.$end_date->format('Y');
                    
                    $data = array(
                            'Title' => $media->title,
                            'End_Date' => $date,
                            'End_Time' => $media->to_date,
                            'shareUrl' => $media->share_url,
                            'About' => $media->description,
                            'Video' => $media->video_url,
                            'Contestants_Video' => $videos
                        );

                    $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "No Contest Found!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                ); 
            }

        }
        return response()->json($res);
    }

    //To get User Profile
    public function viewProfile(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                
                $data = array(
                        'name' => $user->name,
                        'wallet_money' => $user->wallet,
                        'qr_code' => $user->qr_code == null ? '' : asset('storage/app').'/'.$user->qr_code,
                        'mobile' => $user->mobile,
                        'email_id' => $user->email == null ? '' : $user->email,
                        'user_code' => $user->user_code
                    );

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To edit User Profile
    public function editProfile(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'email_id' => 'nullable|email|unique:users,email,'.$request->user_id.'NULL,id,deleted_at,NULL'
        );
 
        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {

                $user->update([
                        'name' => $request->name,
                        'email' => $request->email_id
                    ]);

                $res = array(
                    'errorcode' => 0,
                    'data' => (object)[],
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

     //To get User Profile
    public function referralCode(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'referral_code' => 'required'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $referral_code = User::where(['id' => $request->user_id, 'user_code' => $request->referral_code, 'is_active' => 1])->first();

            if($referral_code) {
              

                $res = array(
                    'errorcode' => 0,
                    'data' => (object)[],
                    'message' => "Valid Referral code"
                );
            } else {
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Invalid Referral code"
                );
            }

        }
        return response()->json($res);
    }

    //To logout
    public function logout(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'device_token' => 'required|string'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id])->first();

            if($user) {
                UserDevice::where([/*'device_id' => $request->device_token, */'user_id' => $request->user_id])->update(['logout_time' => date('Y-m-d H:i:s')]);
                
                $res = array(
                    'errorcode' => 0,
                    'data' => (object)[],
                    'message' => "Success!"
                );
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To check app updation
    public function checkUpdate(Request $request)
    {
        $rules = array(
            'version' => 'required',
            'type' => 'required|in:1,2'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $flag = false;
            $force_flag = false;

            if($request->type == 1)
                $value = Settings::where(['slug' => 'android'])->first();
            else
                $value = Settings::where(['slug' => 'ios'])->first();
            
            if($request->version < $value->min_value)
                $force_flag = true;
            elseif($request->version < $value->value)
                $force_flag = false;

            if($request->version >= $value->min_value && $request->version < $value->value)
                $flag = true;
            elseif($request->version < $value->min_value)
                $flag = false;
            
            $data = array(
                    'forceUpdate' => $force_flag,
                    'normalUpdate' => $flag
                );

            $res = array(
                'errorcode' => 0,
                'data' => $data,
                'message' => "Success!"
            );
        }
        return response()->json($res);
    }

    //To get All countrys
    public function getCountrys(Request $request)
    { 
        $countrys = Country::all();

        foreach ($countrys as $key => $value) {
            
            $data[] = array(
                    'name' => $value->name,
                    'country_code' => $value->telephone_code,
                    'flag' => asset('public').'/'.$value->flag_image
                );
        }
        
        $res = array(
            'errorcode' => 0,
            'data' => $data,
            'message' => "Success!"
        );

        return response()->json($res);
    }

   
    public function iosShare(Request $request)
    {
        $apps = array();
        $details[] = array(
                'appID' => 'FC2H7BL22V.com.peepliDemo',
                'paths' => ['*']
            );

        $applinks = array(
                'apps' => $apps,
                'details' => $details
            );

        $data = array(
                'applinks' => $applinks
            );

        return response()->json($data);
    }

    public function shareUrl(Request $request)
    { 
        $agent = new Agent();
        $device = $agent->device();

        if ($device == 'iPhone') {
            $data = 'iPhone';
        }else {
            $data = 'https://play.app.goo.gl/?link=https://play.google.com/store/apps/details?id=app.pipli';
        }
        
        return redirect($data);
    }

    //Aboutus
    public function aboutUs(Request $request)
    {
        $data = "<h1>About Us</h1>
                    <p>Dafy is a multi-vendor E-Commerce platform. We are a unique shopping platform that provides a hassle-free shopping experience from trustworthy local vendors. Our main emphasis is to ensure honest transactions, trustworthy sellers, and quick delivery. The initial focus is to expand within the major areas of Ernakulam and then further develop to the rest of India.</p><p>Dafy aspires to compete with the best E-Commerce giants of India including Flipkart, Amazon, Dunzo. We are a unique shopping platform that provides a hassle-free shopping experience from trustworthy local vendors thus aspiring to be India's first Virtual Mall.</p><p>Dafy's Vision</p><p>* To bolster and promote the local vendors, giving them an equal footing with E commerce giants
* To establish a robust and fast pace delivery channel<br>
* To provide ultimate solution for modern economic problem<br>
* To alleviate the confusion caused by unknown and fraudulent sellers and create a transparent interface for the consumers, by referring only the known and popular retailers and sellers from Kochi.<br></p><p>Dafy's Mission</p><p>* To establish a robust and fast-paced delivery channel backed by a highly resourceful support team and to provide ultimate solutions for modern economic problems.<br>
* To help ease the complicated business procedures in the current Kerala B2C market and to help ease the complicated business procedures in the current Kerala B2C market.<br>
* To alleviate the confusion caused by unknown and fraudulent sellers and create a transparent interface for the consumers, by referring only the known and popular retailers and sellers from Kochi.</p>";

        $res = array(
            'errorcode' => 0,
            'data' => $data,
            'message' => "Success!"
        );

        return response()->json($res);
    }

    //Terms and conditions
    public function terms(Request $request)
    {
        $data = Settings::where('slug', 'terms')->first();

        $res = array(
            'errorcode' => 0,
            'data' => $data->description,
            'message' => "Success!"
        );

        return response()->json($res);
    }

    //Help and Support
    public function help(Request $request)
    {   
        $gen = array();
        
        $general = Settings::where(['slug' => 'help_and_support'])->first();

        if($general) {
            $gen = array(
                    'help_description' => $general->description,
                    'contact_number' => $general->contact,
                    'email_id' => $general->value
                );
        }

        $res = array(
            'errorcode' => 0,
            'data' => $gen,
            'message' => "Success!"
        );

        return response()->json($res);
    }

    //To get active states
    public function getStates(Request $request)
    {
        $rules = array(
            'userid' => 'sometimes|required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            // $user = User::where(['id' => $request->userid, 'is_active' => 1])->first();

            // if($user) {
                $data = array();
                
                $states = State::where(['is_active' => 1])->get();

                foreach ($states as $key => $state) {

                    // $city = City::where(['state_id' => $state->id, 'is_active' => 1])->first();

                    // if($city) {
                        
                        $data[] = array(
                                'state_id' => $state->id,
                                'state_name' => $state->name
                            );
                    // }
                }

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            // } else {
            //     $res = array(
            //         'errorcode' => 9,
            //         'data' => (object)[],
            //         'message' => "User not exist!"
            //     );
            // }

        }
        return response()->json($res);
    }

    //To get popular cities list
    public function getCities(Request $request)
    {
        // $rules = array(
        //     'userid' => 'required|integer',
        //     'stateid' => 'required|integer'
        // );

        // $validator = Validator::make(Input::all() , $rules);
        
        // if ($validator->fails()) {
        //     $res = array(
        //         'errorcode' => '3',
        //         'message' => $validator->messages()
        //     );

        // } else {
            // $user = User::where(['id' => $request->userid, 'is_active' => 1])->first();

            // if($user) {
                $data = array();
                
                $cities = City::where([/*'state_id' => $request->stateid, */'is_active' => 1])->get();

                foreach ($cities as $key => $city) {
                    $data[] = array(
                            'id' => $city->id,
                            'city_name' => $city->name,
                            // 'image' => $city->image == null ? null : asset('storage/app').'/'.$city->image,
                            'latitude' => $city->latitude,
                            'longitude' => $city->longitude
                        );
                }

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
            // } else {
            //     $res = array(
            //         'errorcode' => 9,
            //         'data' => (object)[],
            //         'message' => "User not exist!"
            //     );
            // }

        // }
        return response()->json($res);
    }

    //To set user's city
    public function setCity(Request $request)
    {
        $rules = array(
            'userid' => 'required|integer',
            'cityid' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->userid, 'is_active' => 1])->first();

            if($user) {
                $city = City::where(['id' => $request->cityid, 'is_active' => 1])->first();

                if($city) {
                    $user->update(['city_id' => $city->id, 'location' => $city->name, /*'latitude' => null, 'longitude' => null*/]);

                    $res = array(
                        'errorcode' => 0,
                        'data' => (object)[],
                        'message' => "Success!"
                    );
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "City not exist!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To add shop to wishlist
    public function wishlistShop(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'shop_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $shop = BusinessShop::where(['id' => $request->shop_id, 'is_active' => 1])->first();

                if($shop) {

                    $check_wishlist = Wishlist::where(['user_id' => $request->user_id, 'shop_id' => $request->shop_id])->first();

                    if($check_wishlist) {
                        $check_wishlist->delete();
                    } else {
                        Wishlist::create([
                            'user_id' => $request->user_id,
                            'shop_id' => $request->shop_id
                        ]);
                    }

                    $res = array(
                        'errorcode' => 0,
                        'data' => (object)[],
                        'message' => "Success!"
                    );
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Shop not exist!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To add product to wishlist
    public function wishlistProduct(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'product_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $product = Product::where(['id' => $request->product_id, 'is_active' => 1 ,'is_approved' => 1])->first();

                if($product) {

                    $check_wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

                    if($check_wishlist) {
                        $check_wishlist->delete();
                    } else {
                        Wishlist::create([
                            'user_id' => $request->user_id,
                            'product_id' => $request->product_id
                        ]);
                    }

                    $res = array(
                        'errorcode' => 0,
                        'data' => (object)[],
                        'message' => "Success!"
                    );
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Product not exist!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To add product to cart
    public function addToCart(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $product = Product::where(['id' => $request->product_id, 'is_active' => 1,'is_approved' => 1])->where('stock', '>=', $request->quantity)->first();

                if($product) {

                    $check_cart = Cart::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

                    $exist_parent_category = null;
                    $check_category = 0;

                    $exist_category = ProductCategory::where(['product_id' => $product->id, 'shop_id' => $product->seller_id])->whereHas('categoryData')->first();
                    
                    if($exist_category) {
                        $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();
                        
                        if($exist_sub_category) {
                            $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                            if($exist_parent_category) {
                                $check_category = 1;
                            }
                        }
                    }
                    
                    if($check_category == 0) {

                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Unavailable Product!"
                        );

                        return response()->json($res);
                    }

                    if(!$check_cart) {
                        Cart::create([
                            'user_id' => $request->user_id,
                            'product_id' => $request->product_id,
                            'seller_id' => $product->seller_id,
                            'quantity' => $request->quantity
                        ]);

                    } else {
                        $check_cart->update(['quantity' => $check_cart->quantity + 1]);
                    }

                    $res = array(
                            'errorcode' => 0,
                            'data' => (object)[],
                            'message' => "Success!"
                        );
                    
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Out of stock!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To view cart products
    public function myCart(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $products = array();
            $price_details = array();
            $address = (object)[];
            $grand_total = 0;
            $delivery_fee = 0;

            $user = User::where(['id' => $request->user_id])->first();

            if($user) {
                $user_address = UserAddress::where(['user_id' => $request->user_id, 'address_type' => 0, 'default' => 1])->first();

                $user_address = $user_address == null ? UserAddress::where(['user_id' => $request->user_id, 'address_type' => 0, 'is_save' => 1])->first() : $user_address;

                if($user_address) {
                    // $address = preg_replace("/,+/", ",", $user_address->build_name.','.$user_address->area.','.$user_address->landmark.','.$user_address->location);
                    // $address = trim($address, ",");
                    $address = array(
                        'id' => $user_address->id,
                        'name' => $user_address->name,
                        'build_name' => $user_address->build_name,
                        'area' => $user_address->area,
                        'location' => $user_address->location,
                        'landmark' => $user_address->landmark,
                        'latitude' => $user_address->latitude,
                        'longitude' => $user_address->longitude,
                        'mobile' => $user_address->mobile,
                        'pincode' => $user_address->pincode,
                        'type' => $user_address->type
                    );

                    $address_id = $user_address->id;
                }

                $cart = Cart::where(['user_id' => $request->user_id])->whereHas('getProduct')->get();
                $cart_items = count($cart);
                // dd($cart);
                foreach ($cart as $key => $value) {
                    $is_available = true;
                    $is_active = true;

                    $product = Product::where(['id' => $value->product_id])->first();

                    if($product) {

                        $product_data = $this->BusinessApiController->offerPrice($product->id);

                        if($product_data['new_price'] != null)
                            $price = $product_data['new_price'];
                        else
                            $price = $product->price;

                        $subtotal = $price * $value->quantity;
                        $grand_total = $grand_total + $subtotal;

                        $exist_parent_category = null;
                        $check_category = 0;

                        $exist_category = ProductCategory::where(['product_id' => $product->id, 'shop_id' => $product->seller_id])->whereHas('categoryData')->first();

                        if($exist_category) {
                            $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();

                            if($exist_sub_category) {
                                $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                                if($exist_parent_category) {
                                    $check_category = 1;
                                }
                            }
                        }

                        if($product->stock == 0  || $check_category == 0) {
                            $is_available = false;
                        }

                        if($product->is_active == 0) {
                            $is_active = false;
                        }

                        if($product->shop) {
                            $shop_name = $product->shop->name; 
                        } else {
                            $shop_name = null;   
                        }
        
                        // dd($product_data);
                        $products[] = array(
                            'prod_id' => $product->id,
                            'product_name' => $product->name,
                            'product_brand' => $product->brand->name ?? '',
                            'seller_info' => $shop_name,
                            'is_available' => $is_available,
                            'is_active' => $is_active,
                            'product_new_price' => number_format($product_data['new_price'], 2),
                            'product_price' => number_format($product->price, 2),
                            'product_qty' => $value->quantity,
                            'subtotal' => number_format($subtotal, 2),
                            'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image
                        );
                    }
                }

                $delivery_fee_below = Settings::where('slug', 'delivery_charge_below')->first();
                $delivery_fee_between = Settings::where('slug', 'delivery_charge_between')->first();
                $delivery_fee_above = Settings::where('slug', 'delivery_charge_above')->first();

                if($delivery_fee_below->max_order_price > $grand_total) {

                    $delivery_fee = $delivery_fee_below->price;
                } elseif($delivery_fee_between->min_order_price <= $grand_total && $delivery_fee_between->max_order_price > $grand_total) {
                    $delivery_fee = $delivery_fee_between->price;
                } elseif($delivery_fee_above->max_order_price <= $grand_total) {
                    $delivery_fee = $delivery_fee_above->price;
                }

                if(count($products) > 0) {
                    $text1 = 'Price('.$cart_items.' items)';
                    $value1 = 'Rs '.number_format($grand_total, 2);

                    $text2 = 'Delivery Fee';
                    $value2 = $delivery_fee == 0 ? 'Free' : 'Rs '.number_format($delivery_fee, 2);

                    $data1 =  array(
                        'text' => $text1,
                        'value' => $value1
                    );

                    $data2 =  array(
                        'text' => $text2,
                        'value' => $value2
                    );

                    $price_details = array($data1, $data2);
                }

                // $grand_total = number_format($grand_total, 2);
                
                //popup for redeemed point
                $current_points = $user->wallet == null ? 0 : $user->wallet;

                if($current_points > 0) {

                    if($request->product_id != null) {
                        $product = Product::where(['id' => $request->product_id])->first();

                        $product_data = $this->BusinessApiController->offerPrice($product->id);

                        if($product_data['new_price'] != null)
                            $price = $product_data['new_price'];
                        else
                            $price = $product->price;

                        $maximum_redeemed_point = ($price * 80) / 100;

                        if($current_points >= $maximum_redeemed_point)
                            $maximum_redeemed_point = $maximum_redeemed_point;
                        else
                            $maximum_redeemed_point = $current_points;

                    } else {

                        $maximum_redeemed_point = ($grand_total * 80) / 100;

                        if($current_points >= $maximum_redeemed_point)
                            $maximum_redeemed_point = $maximum_redeemed_point;
                        else
                            $maximum_redeemed_point = $current_points;
                        
                    }
                } else {
                    $maximum_redeemed_point = 0;
                }

                // end popup for redeemed point

                $grand_total = $grand_total + $delivery_fee;

                $data['address'] = $address;
                $data['products'] = $products;
                $data['price_details'] = $price_details;
                $data['grand_total'] = number_format($grand_total, 2);
                $data['current_points'] = $current_points;
                $data['maximum_redeemed_point'] = $maximum_redeemed_point;

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
                
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To view cart info
    public function getCartShortInfo(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {

            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $data = array();
                $grand_total = 0;

                $cart = Cart::where(['user_id' => $request->user_id])->whereHas('getProduct')->get();
                $cart_items = count($cart);
                
                foreach ($cart as $key => $value) {

                    $product = Product::where(['id' => $value->product_id])->first();

                    if($product) {

                        $product_data = $this->BusinessApiController->offerPrice($product->id);

                        if($product_data['new_price'] != null)
                            $price = $product_data['new_price'];
                        else
                            $price = $product->price;

                        $subtotal = $price * $value->quantity;
                        $grand_total = $grand_total + $subtotal;
                        // dd($product_data);
                        // $products[] = array(
                        //     'prod_id' => $product->id,
                        //     'product_name' => $product->name,
                        //     'product_new_price' => number_format($product_data['new_price'], 2),
                        //     'product_price' => number_format($product->price, 2),
                        //     'product_qty' => $value->quantity,
                        //     'subtotal' => number_format($subtotal, 2),
                        //     'product_image' => $product->thump_image == null ? null : asset('storage/app').'/'.$product->thump_image,
                        // );
                    }
                }

                $grand_total = number_format($grand_total, 2);

                $data = array(
                    'no_items' => $cart_items,
                    'purchase_amount' => $grand_total
                );

                // $data['address'] = $address;
                // $data['address_id'] = $address_id;
                // $data['products'] = $products;
                // $data['price_details'] = $price_details;
                // $data['grand_total'] = $grand_total;

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
                
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }

        }
        return response()->json($res);
    }

    //To update cart quantity
    public function updateQuantityCart(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $product = Product::where(['id' => $request->product_id, 'is_active' => 1,'is_approved' => 1])->where('stock', '>=', $request->quantity)->first();

                if($product) {

                    $cart = Cart::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

                    if($cart) {

                        $exist_parent_category = null;
                        $check_category = 0;

                        $exist_category = ProductCategory::where(['product_id' => $product->id, 'shop_id' => $product->seller_id])->whereHas('categoryData')->first();

                        if($exist_category) {
                            $exist_sub_category = BusinessCategory::where(['id' => $exist_category->category_id])->first();

                            if($exist_sub_category) {
                                $exist_parent_category = BusinessCategory::where(['id' => $exist_category->categoryData->parent_id])->first();

                                if($exist_parent_category) {
                                    $check_category = 1;
                                }
                            }
                        }

                        if($check_category == 0) {

                            $res = array(
                                'errorcode' => 1,
                                'data' => (object)[],
                                'message' => "Unavailable Product!"
                            );

                            return response()->json($res);
                        }

                        $cart->update(['quantity' => $request->quantity]);

                        $res = array(
                            'errorcode' => 0,
                            'data' => (object)[],
                            'message' => "Success!"
                        );
                    } else {
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Invalid data!"
                        );
                    }
                    
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Out of stock!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }


    //To remove cart item
    public function removeItemCart(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'product_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $product = Product::where(['id' => $request->product_id])->first();

                if($product) {

                    $cart = Cart::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

                    if($cart) {
                        $cart->delete();

                        $res = array(
                            'errorcode' => 0,
                            'data' => (object)[],
                            'message' => "Success!"
                        );
                    } else {
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Invalid data!"
                        );
                    }
                    
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Product not exist!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }

    public function postRatingandReview(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'rating' => 'required|integer',
            'review' => 'nullable'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $product = Product::where(['id' => $request->product_id,'is_approved' => 1])->first(); 

                if($product) {

                    $exist_product_rating = ProductRating::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

                    if($exist_product_rating) {
                        $exist_product_rating->update([
                            'rating' => $request->rating,
                            'review' => $request->review
                        ]);
                    } else {
                        ProductRating::create([
                            'product_id' => $request->product_id,
                            'user_id' => $request->user_id,
                            'rating' => $request->rating,
                            'review' => $request->review
                        ]);
                    }

                    $res = array(
                        'errorcode' => 0,
                        'data' => (object)[],
                        'message' => "Success!"
                    );
                    
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Product not exist!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }

    //To check address is suitable or not for delivery
    public function checkAddress(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'address_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $address = UserAddress::where(['id' => $request->address_id])->first();

                if($address) {

                    $latitude = $address->latitude;
                    $longitude = $address->longitude;

                    $distance = Settings::where(['slug' => 'max_delivery_distance'])->first();

                    $shops = BusinessCategoryShop::select('shop_id')->get()->toArray();
                    
                    if($latitude && $longitude) {
                        $shop_value = BusinessShop::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                            ->having('distance', '<', $distance->value)
                            ->where('name', 'LIKE', '%' . $request->search . '%')
                            // ->where('id', $shop->shop_id)
                            ->where('is_active', 1)
                            ->whereIn('id', $shops)
                            // ->orderByRaw("FIELD(type , 'Pre', 'Gen') ASC")
                            ->orderBy('distance')
                            ->first();

                        if($shop_value) {

                            $res = array(
                                'errorcode' => 0,
                                'data' => (object)[],
                                'message' => "Success!"
                            );
                        } else {
                            $res = array(
                                'errorcode' => 1,
                                'data' => (object)[],
                                'message' => "Delivery not available!"
                            );
                        }
                    } else {
                        $res = array(
                            'errorcode' => 1,
                            'data' => (object)[],
                            'message' => "Please add your location!"
                        );
                    }

                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Address not exist!"
                    );
                }
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }

    //To add addresses
    public function addAddress(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            // 'address_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $is_save = 0;
            $default = 0;

            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {

                if($request->isSaveAddress == 1)
                    $is_save = 1;

                if($request->isDefaultaddress == 1)
                    $default = 1;

                $address = UserAddress::create([
                            'user_id' => $request->user_id,
                            'address_type' => 0,
                            'build_name' => $request->house,
                            'area' => $request->area,
                            'location' => $request->location,
                            'landmark' => $request->landmark,
                            'latitude' => $request->latitude,
                            'longitude' => $request->longitude,
                            'name' => $request->name,
                            'mobile' => $request->phonenumber,
                            'pincode' => $request->pincode,
                            'type' => $request->address_type,
                            'is_save' => $is_save,
                            'default' => $default
                        ]); 

                $data['address_id'] = $address->id;

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );
                
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }

    //To edit addresses
    public function editAddress(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'address_id' => 'required|integer',
            'address_type' => 'required|integer|in:0,1,2'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $is_save = 0;
            $default = 0;

            $user_address = UserAddress::where(['id' => $request->address_id, 'user_id' => $request->user_id, 'address_type' => 0])->first();

            if($user_address) {

                if($request->isSaveAddress == 1)
                    $is_save = 1;

                if($request->isDefaultaddress == 1) {
                    $address = UserAddress::where(['user_id' => $request->user_id, 'address_type' => 0, 'default' => 1])->where('id', '!=', $request->address_id)->first();

                    if($address)
                        $address->update(['default' => 0]);

                    $default = 1;
                }

                $user_address->update([
                        'user_id' => $request->user_id,
                        'address_type' => 0,
                        'build_name' => $request->house,
                        'area' => $request->area,
                        'location' => $request->location,
                        'landmark' => $request->landmark,
                        'name' => $request->name,
                        'mobile' => $request->phonenumber,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'pincode' => $request->pincode,
                        'type' => $request->address_type,
                        'is_save' => $is_save,
                        'default' => $default
                    ]); 

                $res = array(
                    'errorcode' => 0,
                    'data' => (object)[],
                    'message' => "Success!"
                );
                
            } else {
                $res = array(
                    'errorcode' => 1,
                    'data' => (object)[],
                    'message' => "Address not exist!"
                );
            }
        }
        return response()->json($res);
    }

    //To get Saved addresses
    public function savedAddresses(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            // 'address_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $data = array();

                $addresses = UserAddress::where(['user_id' => $request->user_id, 'address_type' => 0, 'is_save' => 1])->get();

                foreach ($addresses as $key => $address) {
                    
                    $data[] = array(
                        'address_id' => $address->id,
                        'address_title' => $address->type,
                        'build_name' => $address->build_name,
                        'area' => $address->area,
                        'location' => $address->location,
                        'landmark' => $address->landmark,
                        'latitude' => $address->latitude,
                        'longitude' => $address->longitude,
                        'name' => $address->name,
                        'mobile' => $address->mobile,
                        'pincode' => $address->pincode,
                        'default' => $address->default
                    );
                }

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );

            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }

    //To delete specific address
    public function deleteAddress(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer',
            'address_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {

                $address = UserAddress::where(['id' => $request->address_id, 'user_id' => $request->user_id, 'address_type' => 0])->first();

                if($address) {
                    $address->delete();

                    $res = array(
                        'errorcode' => 0,
                        'data' => (object)[],
                        'message' => "Success!"
                    );
                } else {
                    $res = array(
                        'errorcode' => 1,
                        'data' => (object)[],
                        'message' => "Address not exist!"
                    );
                }
                
            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => (object)[],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }

    //To get notifications
    public function notifications(Request $request)
    {
        $rules = array(
            'user_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all() , $rules);
        
        if ($validator->fails()) {
            $res = array(
                'errorcode' => '3',
                'message' => $validator->messages()
            );

        } else {
            $user = User::where(['id' => $request->user_id, 'is_active' => 1])->first();

            if($user) {
                $data = array();
                $description = '';
                $not_array = [8, 9 , 10, 20];
                // $notifications = Notification::where(['to_id' => $request->user_id])/*->whereIn('notification_id', '!=', 8)->whereNotIn('notification_id', [8, 9, 10])*/->where('order_id', '!=', null)->orderBy('id', 'desc')->get();
                
                $user_id = $request->user_id;
                
                $notifications = Notification::where(function($q) use($user_id) {
                    $q->where('to_id', $user_id);
                    $q->orWhere('to_id', 0);
                })->whereHas('notificationCategory', function($q) {
                    $q->where('slug', '!=', 'shop_inactive');
                })->orderBy('id', 'desc')->get();

                foreach ($notifications as $key => $notification) {

                    if(!in_array($notification->notification_id, $not_array) ) {

                        $type = null;

                        if($notification->notificationCategory->slug == 'buy_anything_confirmation' || $notification->notificationCategory->slug == 'deliver_anything_confirmation') {

                            if($notification->notificationCategory->slug == 'buy_anything_confirmation') {
                                $type = 'buy_anything';

                                $description = 'Your Order ID #'.$notification->orderData->order_no.' has been confirmed.';

                            } else {
                                $type = 'deliver_anything';

                                $description = 'Your Order ID #'.$notification->orderData->order_no.' has been confirmed.';
                            }

                        } else {
                            $type = 'order';

                            if($notification->notificationCategory->slug == 'placed') {
                                $description = 'Your Order ID #'.$notification->orderData->order_no.' has been placed.';
                            } elseif($notification->notificationCategory->slug == 'accepted') {
                                $description = 'Your Order ID #'.$notification->orderData->order_no.' has been accepted by shop.';
                            } elseif($notification->notificationCategory->slug == 'shipped') {
                                $description = 'Your Order ID #'.$notification->orderData->order_no.' has been shipped.';
                            } elseif($notification->notificationCategory->slug == 'delivered') {
                                $description = 'Your Order ID #'.$notification->orderData->order_no.' has been delivered.';
                            } elseif($notification->notificationCategory->slug == 'cancelled') {
                                $description = 'Your Order ID #'.$notification->orderData->order_no.' has been cancelled.';
                            } elseif($notification->notificationCategory->slug == 'rejected') {
                                $description = 'Your Order ID #'.$notification->orderData->order_no.' has been rejected.';
                            } elseif($notification->notificationCategory->slug == 'common') {
                                $type = 'common';
                                $description = $notification->notificationCategory->description;
                            }
                        }
                        

                        $date = new DateTime($notification->created_at);
                        $notif_date = $date->format('d').' '.$date->format('M').' '.$date->format('Y');
                        
                        $data[] = array(
                            'type' => $type,
                            'order_id' => optional($notification->orderData)->id,
                            'shop_id' => $notification->shop_id,
                            'product_id' => $notification->product_id,
                            'notif_id' => $notification->id,
                            'notif_title' => $notification->notificationCategory->title,
                            'notif_description' => $description,
                            'notif_date' => $notif_date
                        );
                    }
                    

                    $notification->update(['is_view' => 1]);
                }

                $res = array(
                    'errorcode' => 0,
                    'data' => $data,
                    'message' => "Success!"
                );

            } else {
                $res = array(
                    'errorcode' => 9,
                    'data' => [],
                    'message' => "User not exist!"
                );
            }
        }
        return response()->json($res);
    }


    public function inviteUrl(Request $request)
    {
        $agent = new Agent();
        $device = $agent->device();
        
                if ($device == 'iPhone') {
                    $data = 'iPhone';
                }else {
                    $data = 'https://play.app.goo.gl/?link=https://play.google.com/store/apps/details?id=com.dafy';
                }
                    
                    
                    $res = array(
                        'errorcode' => 0,
                        'data' => $data,
                        'message' => "Success!"
                    );

           
        
                    return redirect($data);
    }

}
