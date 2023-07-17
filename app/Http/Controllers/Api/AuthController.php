<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserCollection;
use App\Mail\EmailManager;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Mail;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $user = User::where('phone', $request->phone)->orWhere('email', $request->email)->withTrashed()->first();
        if ($user != null) {
            return response()->json([
                'success' => false,
                'message' => translate('User already exists.'),
                'data' => null
            ]);
        }
        if (!$request->has('phone') || !$request->has('email')) {
            return response()->json([
                'success' => false,
                'message' => translate('Email & phone is required.'),
                'data' => null
            ], 200);
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'verification_code' => rand(100000, 999999)
        ]);

        if(get_setting('email_verification') != 1){
            $user->email_verified_at = date('Y-m-d H:m:s');
        }
        else {
            $user->notify(new EmailVerificationNotification());
        }
        $user->save();

        if($request->has('temp_user_id') && $request->temp_user_id != null){
            Cart::where('temp_user_id', $request->temp_user_id)->update(
            [
                'user_id' => $user->id,
                'temp_user_id' => null
            ]);
        }

        if(get_setting('email_verification') == 1){
            return response()->json([
                'success' => true,
                'message' => translate('A verification code has been sent to your email.')
            ], 200);
        }

        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'success' => false,
                'message' => translate('Invalid login information')
            ], 200);

        $user = $request->user();
        
        if($request->has('temp_user_id') && $request->temp_user_id != null){
            Cart::where('temp_user_id', $request->temp_user_id)->update(
            [
                'user_id' => $user->id,
                'temp_user_id' => null
            ]);
        }
        
        if($user->user_type == 'customer'){
            if(get_setting('email_verification') == 1 && $user->email_verified_at == null){
                return response()->json([
                    'success' => true,
                    'verified' => false,
                    'message' => translate('Please verify your account')
                ], 200);
            }
            $tokenResult = $user->createToken('Personal Access Token');
            return $this->loginSuccess($tokenResult, $user);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => translate('Only customers can login here')
            ], 200);
        }
    }

    public function verify(Request $request){
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => translate('No user found with this email address.')
            ], 200);
        }
        if($user->verification_code != $request->code){
            return response()->json([
                'success' => false,
                'message' => translate('Code does not match.')
            ], 200);
        }else{
            $user->email_verified_at = date('Y-m-d H:m:s');
            $user->save();
            $tokenResult = $user->createToken('Personal Access Token');
            return $this->loginSuccess($tokenResult, $user);
        }
    }

    public function resend_code(Request $request){
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {

            $user = User::where('email', $request->email)->first();
            if ($user != null) {
                $user->verification_code = rand(100000,999999);
                $user->save();

                $user->notify(new EmailVerificationNotification());

                return response()->json([
                    'success' => true,
                    'message' => translate('A verification code has been sent to your email.')
                ], 200);
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => translate('No user found with this email address.')
                ], 200);
            }
        }
        else{
            return response()->json([
                'success' => false,
                'message' => translate('Invalid email address.')
            ], 200);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();
        return response()->json([
            'message' => translate('Successfully logged out')
        ]);
    }

    protected function loginSuccess($tokenResult, $user)
    {
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(100);
        $token->save();
        return response()->json([
            'success' => true,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'verified' => true,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'balance' => $user->balance,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => api_asset($user->avatar),
            ],
            'followed_shops' => $user->followed_shops->pluck('id')->toArray()
        ]);
    }
    
        
    public function secret_login(Request $request)
    {

        $user = User::find($request->id);
       
        //if( ! Hash::check( $user->password , $request->password ) )
        if($user->password !== $request->password)
        {
            return "not ok";
            // return Redirect::to('/admin/profile')
            //     ->with('message', 'Current Password Error !')
            //     ->withInput();
        }
 
        if($user->user_type == 'customer'){
            if(get_setting('email_verification') == 1 && $user->email_verified_at == null){
                return response()->json([
                    'success' => true,
                    'verified' => false,
                    'message' => translate('Please verify your account')
                ], 200);
            }
            $tokenResult = $user->createToken('Personal Access Token');
            return $this->loginSuccess($tokenResult, $user);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => translate('Only customers can login here')
            ], 200);
        }
    }

    // Mobile Apps function start
    public function apps_login(Request $request){

        $user = User::where('phone', $request->phone)->first();
        if ($user != null) {
            return $this->user_info($request->phone);
        }
        else{
            return $this->apps_signup($request->phone);
        }
       
    }

    public function apps_signup($phone){
        $user = new User([
            'name' => $phone,
            'email' => $phone."@gmail.com",
            'phone' => $phone,
            'password' => Hash::make($phone),
            'verification_code' => rand(100000, 999999)
        ]);

        if(get_setting('email_verification') != 1){
            $user->email_verified_at = date('Y-m-d H:m:s');
        }
        else {
            $user->notify(new EmailVerificationNotification());
        }
        $user->save();
        return $this->user_info($phone);
    }

    public function user_info($phone=""){

         $user = User::where('phone',$phone)->first();
         return response()->json([
            'user' => [
                'id' => $user->id,
                'balance' => $user->balance,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => api_asset($user->avatar),
            ],
            'followed_shops' => $user->followed_shops->pluck('id')->toArray()
        ]);
        
    }

    public function user_info_update(Request $request){

        $user_update = User::find($request->id);
        $user_update->name = $request->name;
        $user_update->email = $request->email;
        $user_update->phone = $request->phone;
        $user_update->save();

        $user = User::where('id',$request->id)->first();
         return response()->json([
            'user' => [
                'id' => $user->id,
                'balance' => $user->balance,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => api_asset($user->avatar),
            ],
            'followed_shops' => $user->followed_shops->pluck('id')->toArray()
        ]);
        
    }
    
    //mobile apps login api created by yeasin
    public function mobile_apps_login(Request $request)
    {
        
        $request->validate([
            'phone' => 'required|min:14'
        ]);

        $app_signature_id = "";

        if(isset($request->phone) && !isset($request->OTP)){
            $find_user = User::where('phone', $request->phone)->first();
            if(!$find_user){
                $user = new User([
                    'name' => $request->phone,
                    'email' => $request->phone."@gmail.com",
                    'phone' => $request->phone,
                    'password' => Hash::make($request->phone),
                    'verification_code' => rand(100000, 999999)
                ]);

                if(get_setting('email_verification') != 1){
                    $user->email_verified_at = date('Y-m-d H:m:s');
                }
                else {
                    $user->notify(new EmailVerificationNotification());
                }

                $save_new_user = $user->save();

                if($save_new_user){
                    $get_user_id = User::select('id')->where('phone', $request->phone)->first();
                    //create, update and send otp through message
                    if($request->has('app_signature_id')){
                        $app_signature_id = $request->app_signature_id;
                    }
                    return $this->create_otp_send_message($request->phone,$get_user_id->id,$app_signature_id);
                }

            }else{
                //create, update and send otp through message
                if($request->has('app_signature_id')){
                    $app_signature_id = $request->app_signature_id;
                }
                return $this->create_otp_send_message($request->phone,$find_user->id,$app_signature_id);
            }
        }

        if(isset($request->phone) && isset($request->OTP))
        {
            $user = User::where('phone', $request->phone)->first();
            
            if($user){
                if(trim($request->OTP) == null || trim($request->OTP) == ""){
                    return response()->json([
                        'status' => 401,
                        'message' => 'Your otp is not match!'
                    ],401);
                }else{
                    
                    $match_otp =  User::where('id', $user->id)->where('otp', trim($request->OTP))->first();
                    
                    if($match_otp){

                        //create access token and save access token
                        $tokenResult = $user->createToken('Personal Access Token');
                        $token = $tokenResult->token;
                        $token->expires_at = Carbon::now()->addWeeks(100);
                        $token->save();

                        //replace temporary user for real user in cart table
                        if($request->has('temp_user_id') && $request->temp_user_id != null){
                            Cart::where('temp_user_id', $request->temp_user_id)->update(
                            [
                                'user_id' => $user->id,
                                'temp_user_id' => null
                            ]);
                        }

                        return response()->json([
                            'status' => 200,
                            'user_data' => [
                                'id' => $user->id,
                                'balance' => $user->balance,
                                'name' => $user->name,
                                'email' => $user->email,
                                'phone' => $user->phone,
                                'avatar' => api_asset($user->avatar),
                            ],
                            'followed_shops' => $user->followed_shops->pluck('id')->toArray(),
                            'acess_token' => $tokenResult->accessToken,
                            'token_type' => 'Bearer',
                            'expires_at' => Carbon::parse(
                                $tokenResult->token->expires_at
                            )->toDateTimeString(), 
                        ]);
                }else{
                    return response()->json([
                        'status' => 401,
                        'message' => 'Your otp is not match!'
                    ],401);
                }
            }
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'User Not Found!'
                ],404);
            }
            
          
        }
        
    }

    //create opt, update otp and send otp
    public function create_otp_send_message($phone_number, $user_id, $app_signature_id, $sms_type="new_user"){
        $generator = "1357902468";
        $result = "";
  
        for ($i = 1; $i <= 6; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }
    
        //update otp to db
        if($sms_type == "edit_user"){
             $update_user_otp = User::where('id', $user_id)->update([
                'otp' =>  $result
            ]);
             
        }else{
             $update_user_otp = User::where('phone', $phone_number)->where('id', $user_id)->update([
                'otp' =>  $result
            ]);
        }
       

        //send otp to the customer mobile number
        if($update_user_otp){
            // SMS integration Start
            $sms_body = "<#> Your MaakView OTP Code is: $result"."\n".$app_signature_id;
            $token = "7866132738dca110e68e8b7cbc10e238a12c992211";
            $url = "http://api.greenweb.com.bd/api.php";
            $message = $sms_body;
            $data= array(
            'to'=> "$phone_number",
            'message'=>"$message",
            'token'=>"$token"
            ); 
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $smsresult = curl_exec($ch);
            // SMS integration End
            if(substr($smsresult,0,2) === 'Ok'){
                return response()->json([
                    'status' => 200,
                    'confirmed_otp' => true,
                    'message' => 'Your OTP code is send to your mobile'
                ]);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => 'Something went wrong! Please, try again later.'
                ],500);
            }

        }

        
    }

    public function mobile_apps_login_by_social_media(Request $request)
    { 
        
        $access_token = $request->accessToken;
        $provider = $request->provider; 
        $secret = $request->secret; 
      
        if($provider == 'facebook' || $provider == 'google' || $provider ==  'twitter'){
    
            try{
                if($provider == 'twitter'){
                    try{
                        $user = Socialite::driver('twitter')->userFromTokenAndSecret($access_token, $secret);
                    }catch (\Exception $e) {    
                        
                        return response()->json([
                            'success' => false,
                            'message' => 'Twitter Login Failed!'
                        ],400);
                    } 
                }else{
                    $user = Socialite::driver($provider)->userFromToken($access_token);
                }
                
                 // check if they're an existing user
                 if($user->email == null)
                 {
                      $existingUser = User::where('provider_id', $user->id)->withTrashed()->first();
                 }
                 else{
                     $existingUser = User::where('provider_id', $user->id)->orWhere('email', $user->email)->withTrashed()->first();
                 }
                
             
                if(!$existingUser) {
                    // create a new user
                    $newUser                  = new User;
                    $newUser->name            = $user->name;
                    $newUser->email           = $user->email ?? null;
                    $newUser->email_verified_at = $user->email ? date('Y-m-d H:m:s'): null;
                    $newUser->provider_id     = $user->id;
                    $newUser->save();
                }
                
                $tokenResult = $existingUser ? $existingUser->createToken('Personal Access Token') : $newUser->createToken('Personal Access Token');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Social Login Successful',
                    'access_token' => $tokenResult->accessToken,
                    'user_data' => $existingUser ?  $existingUser : $newUser
                ],200);

                
            } catch (\Exception $e) {
  
                return response()->json([
                    'success' => false,
                    'message' => 'Social Login Failed!'
                ],$e->getCode());
            }    
           
     
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Social Provider not Valid!'
            ], 404);
        }
     
    }
    
    
    // Mobile Apps function end
    
    
}