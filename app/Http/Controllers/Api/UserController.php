<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\UserCollection;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Upload;
use Illuminate\Http\Request;
use  App\Http\Controllers\Api\AuthController;

class UserController extends Controller
{
    public function dashboard()
    {
        $total_order_products = OrderDetail::distinct()
                                        ->whereIn('order_id', Order::where('user_id', auth('api')->user()->id)->pluck('id')->toArray());

        $recent_purchased_products = Product::whereIn('id',$total_order_products->pluck('product_id')->toArray())->limit(10)->get();
        $last_recharge = Wallet::where('user_id',auth('api')->user()->id)->latest()->first();

        return response()->json([
            'success' => true,
            'last_recharge' => [
                'amount' => $last_recharge ? $last_recharge->amount : 0.00,
                'date' => $last_recharge ? $last_recharge->created_at->format('d.m.Y') : '',
            ],
            'total_order_products' => $total_order_products->count('product_variation_id'),
            'recent_purchased_products' => new ProductCollection($recent_purchased_products)
        ]);
    }
    public function info()
    {
        $user = User::find(auth('api')->user()->id);

        return response()->json([
            'success' => true,
            'user' => new UserCollection($user),
            'followed_shops' => $user->followed_shops->pluck('id')->toArray()
        ]);
    }

    public function updateInfo(Request $request)
    {
        $user = User::find(auth('api')->user()->id);
        if (Hash::check($request->oldPassword, $user->password)) {
            
            if($request->hasFile('avatar')){
                $upload = new Upload;
                $upload->file_original_name = null;
                $arr = explode('.', $request->file('avatar')->getClientOriginalName());

                for($i=0; $i < count($arr)-1; $i++){
                    if($i == 0){
                        $upload->file_original_name .= $arr[$i];
                    }
                    else{
                        $upload->file_original_name .= ".".$arr[$i];
                    }
                }

                $upload->file_name = $request->file('avatar')->store('uploads/all');
                $upload->user_id = $user->id;
                $upload->extension = $request->file('avatar')->getClientOriginalExtension();
                $upload->type = 'image';
                $upload->file_size = $request->file('avatar')->getSize();
                $upload->save();

                $user->update([
                    'avatar' => $upload->id,
                ]);
            }
            $user->update([
                'name' => $request->name,
                // 'phone' => $request->phone
            ]);
            
            if($request->password){
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
            $user->save();

            return response()->json([
                'success' => true,
                'message' => translate('Profile information has been updated successfully'),
                'user' => new UserCollection($user)
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => translate('The old password you have entered is incorrect')
            ]);
        }
    }

    public function mobile_user_updateInfo(Request $request)
    {
        
        $user = User::find(auth('api')->user()->id);

            if($request->hasFile('avatar')){
                $upload = new Upload;
                $upload->file_original_name = null;
                $arr = explode('.', $request->file('avatar')->getClientOriginalName());

                for($i=0; $i < count($arr)-1; $i++){
                    if($i == 0){
                        $upload->file_original_name .= $arr[$i];
                    }
                    else{
                        $upload->file_original_name .= ".".$arr[$i];
                    }
                }

                $upload->file_name = $request->file('avatar')->store('uploads/all');
                $upload->user_id = $user->id;
                $upload->extension = $request->file('avatar')->getClientOriginalExtension();
                $upload->type = 'image';
                $upload->file_size = $request->file('avatar')->getSize();
                $upload->save();

                $user->update([
                    'avatar' => $upload->id,
                ]);
            }


            if(isset($request->OTP)){
                
                if(trim($request->OTP) == null || trim($request->OTP) == ""){
                    return response()->json([
                        'status' => 401,
                        'message' => 'Your otp is not match!'
                    ],401);
                }else{
                    $match_otp =  User::where('id', $user->id)->where('otp', trim($request->OTP))->first();
                    if($match_otp){
                        User::where('id', auth('api')->user()->id)->update([
                            'email' => $request->email,
                            'name' => $request->name,
                            'phone' => $request->phone
                        ]);

                         //after updating new user data
                        $user = User::find(auth('api')->user()->id);

                        return response()->json([
                            'success' => true,
                            'message' => translate('Profile information has been updated successfully'),
                            'user' => new UserCollection($user)
                        ]);

                    }else{
                        return response()->json([
                            'success' => false,
                            'status' => 401,
                            'message' => 'Your otp is not match!'
                        ],401);
                    }
                }
            }else{
                if($request->has('email') &&  !empty($request->email) && $request->has('name') &&  !empty($request->name) && $request->has('phone') &&  !empty($request->phone))
                {
                    $request->validate([
                        'email' => 'required|unique:users,email,'.auth('api')->user()->id,
                        'phone' => 'required|unique:users,phone,'.auth('api')->user()->id,
                        'name' => 'required'
                    ]);
    
                    if(count(User::where('phone', $request->phone)->where('id',auth('api')->user()->id)->get()) > 0){
                        //already exist phone that's why just update email and name
                        User::where('id', auth('api')->user()->id)->update([
                            'email' => $request->email,
                            'name' => $request->name
                        ]);
                         //after updating new user data
                        $user = User::find(auth('api')->user()->id);
    
                        return response()->json([
                            'success' => true,
                            'confirmed_otp' => false,
                            'message' => translate('Profile information has been updated successfully'),
                            'user' => new UserCollection($user)
                        ]);
                    }else{
                        $authcontroller_instance = new AuthController();
                        return $authcontroller_instance->create_otp_send_message($request->phone, auth('api')->user()->id, $request->app_signature_id, "edit_user");
                    }
                }
            }
    }

    public function update_notification_token(Request $request)
    {
        
        if($request->has('user_notification_token')){
            $user = User::find(auth('api')->user()->id);
            
           $user->user_notification_token = $request->user_notification_token;
           $user->update();
           
            return response()->json([
                'success' => true,
                'message' => 'User Notification Token updated Successfully!'
            ]);
        }

    }



}
