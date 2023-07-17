<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\SubscribeController;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\ApiStoreAttendanceDataApiController;
use App\Http\Controllers\Reseller\AuthController as ResellerAuthController;
use App\Http\Controllers\Api\Multivendor\SellerController;
use Illuminate\Http\Request;



Route::middleware(['cors'])->group(function () {

    Route::group( [ 'prefix' => 'reseller' ], function()
    {
        Route::post('login', [ResellerAuthController::class, 'login']);
        Route::post('register', [ResellerAuthController::class, 'register']);

        Route::middleware('auth:reseller_api')->group(function () {
            Route::post('me', [ResellerAuthController::class, 'me']);
        });
    });
    
Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {

    Route::group(['prefix' => 'auth'], function () {
        
        // Mobile Apps Api start
            // Route::post('apps_login', [AuthController::class,'apps_login']);
            // Route::get('user_info/{phone}', [AuthController::class,'user_info']);
            // Route::post('user_info_update', [AuthController::class,'user_info_update']);
            Route::post('mobile_apps_login', [AuthController::class,'mobile_apps_login']);
            Route::post('mobile_apps_login_by_social_media', [AuthController::class,'mobile_apps_login_by_social_media']);
            Route::get('mobile/track_order/{order_code}', [OrderController::class,'order_show_tracking_for_mobile']);
        
        //for product search 
            Route::post('search_for_api', [ProductController::class,'search_for_api']);
        // Mobile Apps Api end

        //Mobile apps route start
        Route::get('addresses_for_apps/{id?}', [AddressController::class,'addresses_for_apps']);
        Route::get('get_all_cities', [AddressController::class,'get_all_cities']);
        Route::post('createShippingAddress_for_apps', [AddressController::class,'createShippingAddress_for_apps']);  
        Route::post('updateShippingAddress_for_apps', [AddressController::class,'updateShippingAddress_for_apps']);
        Route::get('deleteShippingAddress_for_apps/{id}/{address_id}', [AddressController::class,'deleteShippingAddress_for_apps']);
        Route::get('defaultShippingAddress_for_apps/{id}/{address_id}', [AddressController::class,'defaultShippingAddress_for_apps']);
        // Route::get('defaultBillingAddress_for_apps/{id}', [AddressController::class,'defaultBillingAddress_for_apps']);                     
        //Mobile apps route End

        Route::post('sabbir_test', [AuthController::class,'sabbir_test']);
        Route::get('sabbir_show', [AuthController::class,'sabbir_show']);
        Route::post('login', [AuthController::class,'login']);
        Route::post('signup', [AuthController::class,'signup']);
        Route::post('verify', [AuthController::class,'verify']);
        Route::post('resend-code', [AuthController::class,'resend_code']);

        Route::post('password/create', [PasswordResetController::class,'create']);
        Route::post('password/reset', [PasswordResetController::class,'reset']);

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('logout', [AuthController::class,'logout']);
            Route::get('user', [AuthController::class,'user']);
        });
    });

    Route::get('locale/{language_code}', [TranslationController::class,'index']);
    Route::get('setting/home/{section}', [SettingController::class,'home_setting']);
    Route::get('setting/footer', [SettingController::class,'footer_setting']);
    Route::get('setting/header', [SettingController::class,'header_setting']);
    Route::post('subscribe', [SubscribeController::class,'subscribe']);

    Route::get('all-categories', [CategoryController::class,'index']);
    Route::get('categories/first-level', [CategoryController::class,'first_level_categories']);
    Route::get('all-brands', [BrandController::class,'index']);
    Route::get('all-offers', [OfferController::class,'index']);
    Route::get('offer/{slug}', [OfferController::class,'show']);
    Route::get('page/{slug}', [PageController::class,'show']);


    Route::group(['prefix' => 'product'], function () {
        Route::get('/details/{product_slug}', [ProductController::class,'show']);
        Route::post('get-by-ids', [ProductController::class,'get_by_ids']);
        Route::get('search', [ProductController::class,'search']);
        Route::get('related/{product_id}', [ProductController::class,'related']);
        Route::get('bought-together/{product_id}', [ProductController::class,'bought_together']);
        Route::get('random/{limit}/{product_id?}', [ProductController::class,'random_products']);
        Route::get('latest/{limit}', [ProductController::class,'latest_products']);
        Route::get('reviews/{product_id}', [ReviewController::class,'index']);

        //product search api by yeasin
        Route::get('product_search_by_name_and_id', [ProductController::class,'product_search_by_name_and_id']);

    });

    Route::get('all-countries', [AddressController::class,'get_all_countries']);
    Route::get('states/{country_id}', [AddressController::class,'get_states_by_country_id']);
    Route::get('cities/{state_id}', [AddressController::class,'get_cities_by_state_id']);

    Route::post('carts', [CartController::class,'index']);
    Route::post('carts/add', [CartController::class,'add']);
    Route::post('carts/change-quantity', [CartController::class,'changeQuantity']);
    Route::post('carts/destroy', [CartController::class,'destroy']);


     //pc builder cart api for multiple product 
     Route::post('carts/add/pc-builder', [CartController::class,'pc_builder_cart_add']);
   
    Route::group(['middleware' => 'auth:api'], function () {

        Route::group(['prefix' => 'checkout'], function () {
            Route::get('get-shipping-cost/{address_id}', [OrderController::class,'get_shipping_cost']);
            Route::post('order/store', [OrderController::class,'store']);
            Route::post('coupon/apply', [CouponController::class,'apply']);
        });

        

        Route::group(['prefix' => 'user'], function () {

            Route::get('dashboard', [UserController::class,'dashboard']);

            Route::get('chats', [ChatController::class,'index']);
            Route::post('chats/send', [ChatController::class,'send']);
            Route::get('chats/new-messages', [ChatController::class,'new_messages']);

            Route::get('info', [UserController::class,'info']);
            Route::post('info/update', [UserController::class,'updateInfo']);

            Route::get('coupons', [CouponController::class,'index']);

            Route::get('orders', [OrderController::class,'index']);
            Route::get('order/{order_code}', [OrderController::class,'show']);
            Route::get('order/cancel/{order_id}', [OrderController::class,'cancel']);
            Route::get('order/invoice-download/{order_code}', [OrderController::class,'invoice_download']);

            Route::get('review/check/{product_id}', [ReviewController::class,'check_review_status']);
            Route::post('review/submit', [ReviewController::class,'submit_review']);

            Route::apiResource('wishlists', WishlistController::class)->except(['update', 'show']);
            Route::apiResource('follow', FollowController::class)->except(['update', 'show']);

            Route::get('addresses', [AddressController::class,'addresses']);
            Route::post('address/create', [AddressController::class,'createShippingAddress']);
            Route::post('address/update', [AddressController::class,'updateShippingAddress']);
            Route::get('address/delete/{id}', [AddressController::class,'deleteShippingAddress']);
            Route::get('address/default-shipping/{id}', [AddressController::class,'defaultShippingAddress']);
            Route::get('address/default-billing/{id}', [AddressController::class,'defaultBillingAddress']);


            Route::post('wallet/recharge', [WalletController::class,'recharge']);
            Route::get('wallet/history', [WalletController::class,'walletRechargeHistory']);
            //yeasin api
            Route::get('orders/mobile_users_orders', [OrderController::class,'user_order_details_for_mobile_user']);
            Route::post('info/mobile_users_update', [UserController::class,'mobile_user_updateInfo']);
            Route::post('info/update_notification_token', [UserController::class,'update_notification_token']);
            
        });

        //seller api start by yeasin
        Route::group(['prefix' => 'seller'], function () {

            Route::get('get_seller_products', [SellerController::class,'seller_product_list']);
            Route::get('get_seller_order_list', [SellerController::class,'seller_order_list']);
            Route::get('get_seller_dashboard_summary', [SellerController::class,'seller_dashboard_summary']);
            Route::get('get_seller_dashboard_summary_month_wise', [SellerController::class,'seller_dashboard_month_wise']);
            Route::get('get_seller_package', [SellerController::class,'seller_package']);
            Route::get('get_seller_package_list', [SellerController::class,'get_seller_package_list']);
            Route::get('package_purchase_history', [SellerController::class,'package_purchase_history']);
            Route::get('get_seller_recent_orders', [SellerController::class,'seller_recent_orders']);
            Route::get('seller_top_sell_products', [SellerController::class,'seller_top_sell_products']);
            Route::post('seller_profile_update', [SellerController::class,'seller_profile_update']);
            Route::get('seller_profile_info', [SellerController::class,'seller_profile_info']);
            Route::get('shop_profile', [SellerController::class,'shop_profile']);
            Route::post('shop_profile_info_edit', [SellerController::class,'shop_profile_info_edit']);
            Route::get('get_all_created_coupon', [SellerController::class,'get_all_created_coupon']);
            Route::get('get_all_created_coupon', [SellerController::class,'get_all_created_coupon']);
            Route::post('storeNewCouponForSeller', [SellerController::class,'storeNewCouponForSeller']);
            Route::post('published_product', [SellerController::class,'seller_product_published']);
            Route::get('seller_single_order_details', [SellerController::class,'seller_single_order']);
            Route::post('seller_free_package_purchase', [SellerController::class,'seller_free_package_purchase']);

        });

    });
   
    //for shops
    Route::post('shop/register', [ShopController::class,'shop_register']);
    Route::get('all-shops', [ShopController::class,'index']);
    Route::get('shop/{slug}', [ShopController::class,'show']);
    Route::get('shop/{slug}/home', [ShopController::class,'shop_home']);
    Route::get('shop/{slug}/coupons', [ShopController::class,'shop_coupons']);
    Route::get('shop/{slug}/products', [ShopController::class,'shop_products']);
    //seller login api
    Route::post('seller/login', [SellerController::class,'seller_login']);

    Route::post('/store_attendance_data', function(Request $request){
       
        $data = $request->all();

         // Encode the array back into JSON format
         $json = json_encode($data);
          
         // Overwrite the contents of the data.json file with the new data
         $file = public_path('data.json');
         file_put_contents($file, $json, LOCK_EX);

         
    });

    Route::post('/store_new_attendance_data', [ApiStoreAttendanceDataApiController::class, 'store_new_attendance_data']);
    Route::get('/get_image_data/{image_id}/{image_password}', [SellerController::class, 'getImageData']);
    
});

Route::fallback(function () {
    return response()->json([
        'data' => [],
        'success' => false,
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});
});


