<?php

namespace App\Helper;

use Auth;
use Modules\Admin\Entities\AdminUser;
use Modules\Users\Entities\UserRole;
use Modules\Users\Entities\Role;
use Modules\Users\Entities\User;
use Modules\Users\Entities\Permission;
use Modules\Users\Entities\Rolepermission;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Modules\Shop\Entities\BusinessShop;

class SiteHelper
{
    public function can($perm,$guard = 'admin')
    {
        if(Auth::guard($guard)->check()){
            $user_id = Auth::guard($guard)->id();
            $user = AdminUser::find($user_id);

            $perm_list=Rolepermission::with('permission')->where('role_id',$user->role)->get();
        }
        if(Auth::guard('seller')->check()){
            $shop_id = Auth::guard('seller')->id();
            $shop    = BusinessShop::find($shop_id);
           
            $seller  = User::find($shop->seller_id);
            $role    = Role::whereName('Seller')->first();
            $user    = UserRole::where('user_id',$seller->id)->where('role_id',$role->id)->first();
            if($user){
                $perm_list=Rolepermission::with('permission')->where('role_id',$role->id)->get();
            }
            
        }
        

      
      
      
       $check_list=array();
       foreach($perm_list as $check)
       {
      $check_list[]=$check->permission->slug;
      $check_list = array_unique($check_list);
    	
       }

       

    	
    	return in_array($perm, $check_list);
    }


      public function sendAndroidPush($devices,$data)
    {
        
        $fields = array('registration_ids' => $devices,
                        'data'              => $data);
       

        $headers = array('Authorization: key=' .'AAAA3ZBXIjE:APA91bHhsmc9VgQgHlHrJr-javOHw_KKmp-5smbBCVlQSH5nVIaJf53NPvdWaY7EJqxjep6slsb1Nvn9td1euBmdLtQ7QYXzSFG1Ua1hR2K7EeUuu66WreaVxhH9jsrQNgazRYCDneTI','Content-Type: application/json');
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL,'https://fcm.googleapis.com/fcm/send');
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );  
        return $result;
    }

    public static function sendIosPush($devices,$data)
    { 
        // $devices = array(
        //     "eb13ae38a6ad2c6eba4254b9c21ac92f486574c5a1d8e7bfbc1dc7cc3a9ae101"
        // );
        //dd($devices);
        //$data = array('message'=>"test from php");
        $pushData['message'] = $data->message;
        $pushData['type'] = $data->type;
        $pushData['shop_id'] = $data->shop_id;

        foreach ($devices as $key => $value) 
        {
            //dd($value);
            $msg=$pushData['message'];
            $apnsServer = 'ssl://gateway.sandbox.push.apple.com:2195';
           //$apnsServer ='ssl://gateway.push.apple.com:2195';
            $privateKeyPassword = '';
            $message = $msg;
            $deviceToken =$value;
            $certificate =getcwd();
           // dd($certificate);
            $pushCertAndKeyPemFile = $certificate.'/iOS_certificates/aguaPush.pem';
            $stream = stream_context_create();
            stream_context_set_option($stream, 'ssl', 'passphrase', $privateKeyPassword);

            stream_context_set_option($stream, 'ssl', 'local_cert', $pushCertAndKeyPemFile);

            $connectionTimeout = 20;
            $connectionType = STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT;
            $connection = stream_socket_client($apnsServer, $errorNumber, $errorString, $connectionTimeout, $connectionType, $stream);

            $messageBody['aps'] = array('alert' => $message, 'sound' => 'default', 'badge' => 2,'key'=>$pushData['type'] ,'shop_id' =>$pushData['shop_id']);



            $data['aps']['badge']=0;
            $payload = json_encode($messageBody);
           // dd($payload);
            $notification = chr(0) .
            pack('n', 32) .

           // $deviceToken = pack('H*', str_replace(' ', '', sprintf('%u', CRC32($deviceToken))));

            pack('H*', $deviceToken) .
            pack('n', strlen($payload)) .
            $payload;
            $wroteSuccessfully = fwrite($connection, $notification, strlen($notification));
            if (!$wroteSuccessfully){
                $result[]= "Could not send the message<br/>";
            }
            else {
                $result[]= "Successfully sent the message<br/>";
            }
           // dd($result);
            fclose($connection);

        }

    }

    public function sendAndroidBulkPush($devices,$data)
    {
        $split_device = array_chunk($devices, 500);

        foreach ($split_device as $split) 
        {
            $fields = array('registration_ids' => $split,
                        'data'              => $data);
       

            $headers = array('Authorization: key=' .'AAAA3ZBXIjE:APA91bHhsmc9VgQgHlHrJr-javOHw_KKmp-5smbBCVlQSH5nVIaJf53NPvdWaY7EJqxjep6slsb1Nvn9td1euBmdLtQ7QYXzSFG1Ua1hR2K7EeUuu66WreaVxhH9jsrQNgazRYCDneTI','Content-Type: application/json');
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL,'https://fcm.googleapis.com/fcm/send');
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );
            curl_close( $ch ); 
        }
        
        return $result;
    }

    /**
     * Combines SQL and its bindings
     *
     * @param \Eloquent $query
     * @return string
     */
    public static function getEloquentSqlWithBindings($query)
    {
        // dd($query);
            return vsprintf(str_replace('?', '%s', str_replace('%', '%%', $query->toSql())), collect($query->getBindings())->map(function ($binding) {
                return is_numeric($binding) ? $binding : "'{$binding}'";
            })->toArray());
    }

        
}
