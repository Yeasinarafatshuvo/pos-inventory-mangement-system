<?php

namespace App\Http\Controllers\Api\Multivendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Upload;
use App\Models\SellerPackage;
use App\Models\SellerPackagePayment;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Hash;
use DB;
use Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;



class SellerController extends Controller
{
    public function seller_login(Request $request){
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

        if($user->user_type !== "seller"){
            return response()->json([
                'success' => false,
                'message' => translate('Invalid login information')
            ], 200);
        }else{
            $tokenResult = $user->createToken('Personal Access Token');
            return $this->loginSuccess($tokenResult, $user);
           
        }
      
        
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
                'name' => $user->name,
                'user_type' => $user->user_type,
                'email' => $user->email,
                'shop_id' => $user->shop_id,
                'phone' => $user->phone,
                'avatar' => api_asset($user->avatar),
            ],
           
        ]);
    }

    public function seller_product_list(Request $request)
    {
        if($request->has('search_products') && $request->search_products != null){
            $products = Product::with('image_url')->orderBy('created_at', 'desc')->where('shop_id', Auth::user()->shop->id)->where('name','LIKE','%'.$request->search_products."%")->orwhereRaw('json_contains(random_search, \'["' . $request->search_products . '"]\')');
        }else{
            $products = Product::with('image_url')->where('shop_id', Auth::user()->shop->id);
        }

        $paginate_numbers_value = 20;
        if($request->has('pagination_product_number_value')){
            $paginate_numbers_value = $request->pagination_product_number_value;
        }

        $all_seller_products = $products->paginate($paginate_numbers_value);

        return response()->json([
            'data' => [
                'seller_proudct_lists' => $all_seller_products
            ]
        ], 200);
  
    }


    public function seller_order_list(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
      
        $orders = Order::with('combined_order')->where('shop_id',  Auth::user()->shop->id);
        
        if ($request->has('search') && $request->search != null) {
           $code = $request->search;
           $orders = Order::with('combined_order')
                    ->where('shop_id',  Auth::user()->shop->id)
                    ->whereHas('combined_order', function ($query) use ($code) {
                         $query->where('code', 'LIKE', "%{$code}%");
                    });
        

        }
       

        $paginate_numbers_value = 20;
        
        if($request->has('pagination_product_number_value')){
            $paginate_numbers_value = $request->pagination_product_number_value;
        }

        $orders = $orders->paginate($paginate_numbers_value);

        return response()->json([
            'status' => 200,
            'data' => [
                'seller_orders_lists' => $orders
            ]
        ], 200);

    }

    public function seller_dashboard_summary()
    {
        $total_products_of_seller = Auth::user()->shop->products()->count();
        $total_orders_of_seller = Auth::user()->shop->orders()->count();
        $total_earning_of_seller = Auth::user()->shop->commission_histories()->sum('seller_earning');
        $total_sales_of_seller = Auth::user()->shop->orders()->where('payment_status', 'paid')->sum('grand_total');
        $total_ordered_placed_of_seller = Auth::user()->shop->orders()->where('delivery_status', 'order_placed')->count();
        $total_confirmed_ordered_of_seller = Auth::user()->shop->orders()->where('delivery_status', 'confirmed')->count();
        $total_processed_ordered_of_seller =  Auth::user()->shop->orders()->where('delivery_status', 'processed')->count();
        $total_delivered_ordered_of_seller =  Auth::user()->shop->orders()->where('delivery_status', 'delivered')->count();
        return response()->json([
            'status' => 200,
            'data' => [
                'seller_dashboard_summary' => [
                    'total_products_of_seller' => $total_products_of_seller,
                    'total_orders_of_seller' => $total_orders_of_seller,
                    'total_earning_of_seller' => $total_earning_of_seller,
                    'total_sales_of_seller' => $total_sales_of_seller,
                    'total_ordered_placed_of_seller' => $total_ordered_placed_of_seller,
                    'total_confirmed_ordered_of_seller' => $total_confirmed_ordered_of_seller,
                    'total_processed_ordered_of_seller' => $total_processed_ordered_of_seller,
                    'total_delivered_ordered_of_seller' => $total_delivered_ordered_of_seller,
                ]
                
            ]
        ], 200);
    }

    public function seller_dashboard_month_wise()
    {
            for ($i = 1; $i <= 12; $i++) {
                $item['sales_number_per_month'][$i] = Order::where('shop_id', Auth::user()->shop_id)->where('delivery_status', '!=', 'cancelled')->whereMonth('created_at', '=', $i)->whereYear('created_at', '=', date('Y'))->count();
                $item['sales_amount_per_month'][$i] = Order::where('shop_id', Auth::user()->shop_id)->where('delivery_status', '!=', 'cancelled')->whereMonth('created_at', '=', $i)->whereYear('created_at', '=', date('Y'))->sum('grand_total');
            }

            return response()->json([
                'status' => 200,
                'data' => $item
            ], 200);
        
    }

    public function seller_package()
    {

        return response()->json([
            'status' => 200,
            'data' =>[
                [
                   'seller_package_details' => [
                        'package_id' => Auth::user()->shop->seller_package->id,
                        'package_name' => Auth::user()->shop->seller_package->getTranslation('name'),
                        'package_expires' => Auth::user()->shop->package_invalid_at,
                        'product_upload' => Auth::user()->shop->products->count(),
                        'product_upload_limit' => Auth::user()->shop->product_upload_limit,
                        'shop_commission' => Auth::user()->shop->commission,
                   ] 
                ]
               
            ]
        ], 200);
      
    }

    public function get_seller_package_list(){
        $seller_packages = SellerPackage::with('seller_logo_url')->get();
        return response()->json([
            'status' => 200,
            'data' => [
                'seller_packages' => $seller_packages
            ]
        ], 200);
    }

    public function package_purchase_history()
    {
        $package_payments =  SellerPackagePayment::where('user_id', Auth::user()->id)->latest()->paginate(20);
        return response()->json([
            'status' => 200,
            'data' => [
                'package_payments' => $package_payments
            ]
        ], 200);
    }

    //start Coupon API 

    public function get_all_created_coupon()
    {
        $coupons = Coupon::where('shop_id', auth()->user()->shop_id)->latest()->get();

        return response()->json([
            'status' => 200,
            'data' => [
                'all_created_coupon' => $coupons
            ]
        ], 200);
    }

    public function storeNewCouponForSeller(Request $request)
    {
        if (count(Coupon::where('code', $request->coupon_code)->get()) > 0) {

            return response()->json([
                'status' => 409,
                'message' => 'Coupon already exist for this coupon code'
            ], 409);

        }
        
        $coupon = new Coupon;
        $coupon->type = $request->coupon_type;
        $coupon->shop_id = auth()->user()->shop_id;
        $coupon->code = $request->coupon_code;
        $coupon->discount = $request->discount;
        $coupon->discount_type = $request->discount_type;
        $coupon->start_date       = strtotime($request->start_date);
        $coupon->end_date         = strtotime($request->end_date);
        
        if ($request->coupon_type == "product_base") {
            $cupon_details = array();
            foreach ($request->product_ids as $product_id) {
                $data['product_id'] = $product_id;
                array_push($cupon_details, $data);
            }
            $coupon->details = json_encode($cupon_details);
   
            if ($coupon->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Coupon has been saved successfully',
                    'data' => [
                        'coupon_data' => $coupon
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status' => 422,
                    'message' => 'Something went wrong'
                ], 422);
            }
        } elseif ($request->coupon_type == "cart_base") {
            $data                     = array();
            $data['min_buy']          = $request->min_buy;
            $data['max_discount']     = $request->max_discount;
            $coupon->details          = json_encode($data);
            if ($coupon->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Coupon has been saved successfully',
                    'data' => [
                        'coupon_data' => $coupon
                    ]
                ], 200);
                
            } else {
                return response()->json([
                    'status' => 204,
                    'message' => 'Something went wrong'
                ], 204);
            }
        }
    }

    //end Coupon API

    public function seller_recent_orders()
    {
        $seller_recent_orders = DB::table('orders')->where('shop_id',  Auth::user()->shop->id)->latest()->limit(5)->get();
        return response()->json([
            'status' => 200,
            'data' => [
                'seller_recent_orders' => $seller_recent_orders
            ]
        ], 200);
    }

    public function seller_top_sell_products()
    {
        $seller_top_sell_products = Product::where('shop_id', Auth::user()->shop->id)->where('published', 1)->orderBy('num_of_sale', 'desc')->limit(12)->get();
        return response()->json([
            'status' => 200,
            'data' => [
                'seller_top_sell_products' => $seller_top_sell_products
            ]
        ], 200);
    }

    public function seller_profile_update(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name ??  $user->name;
        $user->phone = $request->phone ?? $user->phone;
        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        if($request->hasFile('seller_image')){

            $uploaded_file = $request->file('seller_image');
            $user_id = Auth::user()->id;

            $uplaod_id = $this->photo_upload($uploaded_file, $user_id);
        }

        $user->avatar = $uplaod_id ?? $user->avatar;
        if ($user->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Your Profile has been updated successfully!'
            ], 200);
        }else{
            return response()->json([
                'status' => 204,
                'message' => 'Sorry! Something went wrong.!'
            ], 204);
        }

    }


    public function seller_profile_info()
    {
        $seller_profile_image_name = "";
        $avatar_id =  Auth::user()->avatar;

        if (($asset = \App\Models\Upload::find($avatar_id)) != null) {
            $seller_profile_image_name = app('url')->asset('public/' . $asset->file_name, null);
        }
        return response()->json([
            'status' => 200,
            'data' => [
                'seller_profile_info' => Auth::user(),
                'seller_profile_image_name' => $seller_profile_image_name
            ]
        ], 200);
    }

    public function shop_profile()
    {
        $shop = Shop::where('user_id', auth()->user()->id)->first();
        $logo_id = Shop::select('logo')->where('user_id', auth()->user()->id)->first()['logo'];
        $ship_profile_image_name = "";
        if (($asset = \App\Models\Upload::find($logo_id)) != null) {
            $ship_profile_image_name = app('url')->asset('public/' . $asset->file_name, null);
        }
        
        return response()->json([
            'status' => 200,
            'data' => [
                'shop_profile' => $shop,
                'shop_profile_image_name' => $ship_profile_image_name
               
            ]
        ], 200);

        
    }

    public function shop_profile_info_edit(Request $request)
    {
        
        $shop = Shop::find(Auth::user()->shop->id);

        $shop_old_name = $shop->name;
        $slug = Str::slug($request->name, '-');
        $same_slug_count = Shop::where('slug','LIKE',$slug.'%')->count();
        $slug_suffix = $same_slug_count > 0 ? '-'.$same_slug_count+1 : '';
        $slug .= $slug_suffix;

        if($request->hasFile('logo')){

            $uploaded_file = $request->file('logo');
            $user_id = Auth::user()->id;

            $uplaod_id = $this->photo_upload($uploaded_file, $user_id);
        }
     
        $shop->name             = $request->name  ?? $shop->name;
        $shop->address          = $request->address ?? $shop->address;
        $shop->phone            = $request->phone ?? $shop->phone;
        $shop->slug             = $shop_old_name == $request->name ? $shop->slug : $slug;
        $shop->meta_title       = $request->meta_title ?? $shop->meta_title;
        $shop->meta_description = $request->meta_description ?? $shop->meta_description;
        if($request->hasFile('logo')){
            //$shop->logo   = $upload->id;
            $shop->logo   = $uplaod_id ?? $shop->logo;
        }
        $shop->update();

        $shop_updated_profile =  Shop::find(Auth::user()->shop->id);

        return response()->json([
            'status' => 200,
            'data' => [
                'shop_updated_profile' => $shop_updated_profile,
            ]
        ], 200);
    
    }

    public function photo_upload($upload_image, $user_id)
    {

            $upload = new Upload;
            $upload->file_original_name = null;
            $arr = explode('.', $upload_image->getClientOriginalName());

            for($i=0; $i < count($arr)-1; $i++){
                if($i == 0){
                    $upload->file_original_name .= $arr[$i];
                }
                else{
                    $upload->file_original_name .= ".".$arr[$i];
                }
            }
    
            $upload->file_name = $upload_image->store('uploads/all');
            $upload->user_id = $user_id;
            $upload->extension = $upload_image->getClientOriginalExtension();
            $upload->type = 'image';
            $upload->file_size = $upload_image->getSize();
            $save_upload_images = $upload->save();
            if($save_upload_images){
                return $upload->id;
            }

    }

    public function seller_product_published(Request $request)
    {
        $shop = auth()->user()->shop;
        if (seller_package_validity_check($shop->seller_package, $shop->package_invalid_at) != 'active') {
            return response()->json([
                'success' => false,
                'message' => translate('Please upgrade your package for changing status.')
            ]);
        }

        $product = Product::findOrFail($request->id);
        $product->published = $request->status;
        $product->save();

        cache_clear();

        if($request->status == 1){
            return response()->json([
                'success' => true,
                'status' => 'publised',
                'message' => translate('Products status publised successfully')
            ]);
        } else{
            return response()->json([
                'success' => true,
                'status' => 'unpublished',
                'message' => translate('Products status unpublished successfully')
            ]);
        }

       
    }

    public function seller_single_order(Request $request)
    {
       
        $id =  $request->id;
        $order = Order::with(['user','orderDetails.product', 'orderDetails.variation.combinations'])->findOrFail($id);
        
        if ($order->shop_id != auth()->user()->shop_id) {
            return response()->json([
                'success' => false,
                'message' => translate('Order Not found on your shop')
            ], 403);
        }
        
        if($order)
        {
            return response()->json([
            'success' => true,
            'status' => 200,
            'data' => [
                'order_detials' =>  $order,
                ]
            ], 200);
        }

        
    }


    public function seller_free_package_purchase(Request $request){

        $seller_package = SellerPackage::findOrFail($request->seller_package_id);

        if ($seller_package->product_upload_limit < Auth::user()->shop->products->count()){
            return response()->json([
                'success' => false,
                'status' => 204,
                'message' => 'You have more uploaded products than this package limit. You need to remove excessive products to downgrade.'
                ], 204);
        }
    

        if($seller_package->amount == 0){
            $package_data_done =  $this->updated_seller_package_data($seller_package->id);
            
            if($package_data_done){
                return response()->json([
                    'success' => true,
                    'message' => translate('Your package successfully Upgraded')
                ]);
            }
        }else{
             return response()->json([
                'success' => false,
                'message' => translate("Your are not permitted to buy this package now")
            ], 403);
            
        }
    }


    public function updated_seller_package_data($package_id){
        $shop = Auth::user()->shop;
        $seller_package = SellerPackage::findOrFail($package_id);
        $shop->seller_package_id = $seller_package->id;
        $shop->product_upload_limit = $seller_package->product_upload_limit;
        $shop->commission = $seller_package->commission;
        $shop->published = 1;
        $shop->package_invalid_at = date('Y-m-d', strtotime($seller_package->duration .'days'));
        $shop->save();
        return 1;
   
    }

    public function getImageData($image_id, $image_password = ""){

        if($image_password === "maak_asset"){
            $asset = \App\Models\Upload::find($image_id);
            $path = 'public/' .$asset->file_name;
           
    
            if(!File::exists($path)) {
                return response()->json(['message' => 'Image not found.'], 404);
            }
          
        
            $file = File::get($path);
           
            $type = File::mimeType($path);
            
            $response = Response::make($file, 200);
          
            $response->header("Content-Type", $type);
        
            return $response;
        }
        
         
    }

    

    





}
