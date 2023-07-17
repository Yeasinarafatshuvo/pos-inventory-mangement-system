<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Utility\CategoryUtility;
use App\Models\CategoryTranslation;
use App\Models\Quotation;
use App\Models\Product;
use App\Models\Brand;
use App\Models\User;
use App\Models\Supplier;
use App\Models\ProductCategory;
use App\Models\CombinedOrder;
use App\Models\Order;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Address;
use App\Models\OrderDetail;
use App\Models\PurchaseTransaction;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturn;
use App\Models\UserHistory;
use App\Models\MoneyReceipt;
use App\Models\MoneyPayment;
use Illuminate\Support\Facades\Auth;
use \Crypt;
use DB;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_categories'])->only('index');
        $this->middleware(['permission:add_categories'])->only('create');
        $this->middleware(['permission:edit_categories'])->only('edit');
        $this->middleware(['permission:delete_categories'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $categories = Category::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(12);
        return view('backend.product.categories.index', compact('categories', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $categories = Category::where('parent_id', 0)
          ->with('childrenCategories')
          ->get();

      return view('backend.product.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category         = new Category;
        $category->name   = $request->name;
        $category->order_level = 0;
        if($request->order_level != null) {
            $category->order_level = $request->order_level;
        }

        $category->banner           = $request->banner;
        $category->icon             = $request->icon;
        $category->meta_title       = $request->meta_title;
        $category->meta_image       = $request->meta_image;
        $category->meta_description = $request->meta_description;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1 ;
        }

        if ($request->slug != null) {
            $category->slug = Str::slug($request->slug, '-');
        }
        else {
            $category->slug = Str::slug($request->name, '-').'-'.strtolower(Str::random(5));
        }

        $category->save();

        $category->attributes()->sync($request->filtering_attributes);

        $category_translation = CategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        flash(translate('Category has been inserted successfully'))->success();
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $category = Category::findOrFail($id);
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->whereNotIn('id', CategoryUtility::children_ids($category->id, true))->where('id', '!=' , $category->id)
            ->orderBy('name','asc')
            ->get();
        return view('backend.product.categories.edit', compact('category', 'categories','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $category->name = $request->name;
        }
        if($request->order_level != null) {
            $category->order_level = $request->order_level;
        }

        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->meta_title = $request->meta_title;
        $category->meta_image       = $request->meta_image;
        $category->meta_description = $request->meta_description;

        $previous_level = $category->level;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1 ;
        }
        else{
            $category->parent_id = 0;
            $category->level = 0;
        }

        if($category->level > $previous_level){
            CategoryUtility::move_level_down($category->id);
        }
        elseif ($category->level < $previous_level) {
            CategoryUtility::move_level_up($category->id);
        }

        $category->slug = (!is_null($request->slug)) ? Str::slug($request->slug, '-') : Str::slug($request->name, '-').'-'.strtolower(Str::random(5));

        $category->save();

        $category->attributes()->sync($request->filtering_attributes);

        $category_translation = CategoryTranslation::firstOrNew(['lang' => $request->lang, 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        flash(translate('Category has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Category Translations Delete
        $category->category_translations()->delete();
        
        $category->product_categories()->delete();

        CategoryUtility::delete_category($id);

        flash(translate('Category has been deleted successfully'))->success();
        return redirect()->route('categories.index');
    }

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        if($category->save()){
            return 1;
        }
        return 0;
    }

    
 //********quotAtion function start*********

    public function quotation_create(Request $request){
        $sort_search =null;
        $categories = Category::orderBy('created_at', 'desc');
        $categories = $categories->paginate(12);
        return view('backend.product.quotation.create',compact('categories', 'sort_search'));
    }

    public function product_search(Request $request)
    { 
            if($request->ajax()){
                if(empty($request->id)){
                    $match_products = Product::where('name','LIKE','%'.$request->search."%")->paginate(5);
                }else{
                    $match_products = Product::where('id',$request->id)->with('image_url')->get()->first();
                }

                return response()->json($match_products);
            }
       

    }

    //save qutation data to db
    public function storeQuotaiton(Request $request)
    {
        //at first check whether it is updated value or not by checking quotation number
        if(!empty($request->quotation_number && empty($request->is_duplicate))){
            
            //if quotation data is available than first delete previous data 
            $delete_Data = Quotation::where('quotation_number', $request->quotation_number)->forceDelete();
           if($delete_Data){
                $qutaiotnnum = $request->quotation_number;
           }
        
        }else if(!empty($request->quotation_number && $request->is_duplicate == 1)){
            //create unique number for qutation number
            $find_quotation_row_numbers = Quotation::withTrashed()->distinct('quotation_number')->count();
            $qutaiotnnum = 'MVQ'.str_pad($find_quotation_row_numbers+1,8,"0",STR_PAD_LEFT);
        }
        else{
            //create unique number for qutation number
            $find_quotation_row_numbers = Quotation::withTrashed()->distinct('quotation_number')->count();
            $qutaiotnnum = 'MVQ'.str_pad($find_quotation_row_numbers+1,8,"0",STR_PAD_LEFT);
            
        }

    
        $total_product_item_numbers = (count($request->id));
        if($total_product_item_numbers !== null){
           
            for($i = 0; $i<$total_product_item_numbers; $i++){
               
               $instanceof_quotation = new Quotation();
               $instanceof_quotation->quotation_number  = $qutaiotnnum;
               $instanceof_quotation->product_id = $request->id[$i];
               $instanceof_quotation->product_price = $request->highest_price[$i];
               $instanceof_quotation->quotation_type = $request->type;
               $instanceof_quotation->company_name = $request->company_name;
               $instanceof_quotation->company_address = $request->company_address;
               $instanceof_quotation->quotation_subject = $request->quotation_subject;
               $instanceof_quotation->terms_and_condition = $request->terms_and_condition;
               $instanceof_quotation->company_persons = $request->company_persons;
               $instanceof_quotation->attention_quot = $request->attention_quot;
               $instanceof_quotation->dear_sir = $request->dear_sir;
               $instanceof_quotation->quottaion_body = $request->quottaion_body;
               $instanceof_quotation->quantity = $request->quantity[$i];

               $instanceof_quotation->created_user = Auth::id();    
               
               $savedata = $instanceof_quotation->save();

            }
            
            if($savedata){
                if(!empty($request->quotation_number && empty($request->is_duplicate))){
                    return redirect('admin/quotation/list')->with('status', 'Quotation Updated Successfully');
                }
                if(!empty($request->quotation_number && $request->is_duplicate == 1)){
                    return redirect('admin/quotation/list')->with('status', 'Quotation Duplicate Successfully');
                }
                return redirect('admin/quotation/home')->with('status', 'Quotation added Successfully');
            }
            
        }
        
    }


    //view quotation date as per quotation number, type
    public function quotation_list()
    {
        $qutotation_list_data = Quotation::orderBy('created_at', 'desc')->get();
  
        return view('backend.product.quotation.quotation_list', compact('qutotation_list_data'));
    }

    //show quotation details as per quotation number
    public function quotation_list_details($quotation_number)
    {
        
        $specefic_quotation_number_products = Quotation::where('quotation_number', $quotation_number)->with('product','user')->get();
         
        return view('backend.product.quotation.quotation_list_details', compact('specefic_quotation_number_products'));
    }

    //show quotation details for customer as per quotation number
    public function quotation_list_detailsc($quotation_number)
    {
        
        
        //$quotation_no = Crypt::decrypt($quotation_number);
        $quotation_no = hex2bin($quotation_number);

        $specefic_quotation_number_products = Quotation::where('quotation_number', $quotation_no)->with('product','user')->get();
         
        return view('backend.product.quotation.quotation_list_detailsc', compact('specefic_quotation_number_products'));
    }
    
    //show liflet
    public function quotation_list_liflet($quotation_number)
    {
        
        $quotation_no = hex2bin($quotation_number);

        $specefic_quotation_number_products = Quotation::where('quotation_number', $quotation_no)->with('product','user')->get();
         
        return view('backend.product.quotation.quotation_list_liflet', compact('specefic_quotation_number_products'));
    }


    //delete quotaiton list
    public function delete_quotation($quotation_number)
    {
        $delete_specefic_quotation = Quotation::where('quotation_number', $quotation_number)->delete();
        if($delete_specefic_quotation){
            return redirect('admin/quotation/list')->with('status', 'Quotation Deleted Successfully');
        }
    }

    //return edit page for specefic quotation number
    public function edit_quotation($quotation_number)
    {
        $edit_specefic_quotation = Quotation::where('quotation_number', $quotation_number)->get();
        return view('backend.product.quotation.create', compact('edit_specefic_quotation'));
    }

    //return duplicate page for specefic quotation number
    public function duplicate_quotation($quotation_number)
    {
        $edit_specefic_quotation = Quotation::where('quotation_number', $quotation_number)->get();
        $duplicate_flag = 1;
        return view('backend.product.quotation.create', compact('edit_specefic_quotation', 'duplicate_flag'));
    }

    public function prayer_view_quotation($quotation_number)
    {
        $quotation_no = hex2bin($quotation_number);
        $specefic_quotation_number_products = Quotation::where('quotation_number', $quotation_no)->with('user')->get();
        return view('backend.product.quotation.quotation_list_prayer', compact('specefic_quotation_number_products'));
    }


//*************quotation function end*****************

//*************POS function start*****************

public function pos_dashboard()
{
 
    $product_Data =Product::paginate(16);
    
    $data['categories'] = Category::all();
    $data['brands'] = Brand::all();
    $data['all_state'] = State::all();
    $all_bd_cities = $this->all_bd_cities();
    return view('backend.product.pos.home', compact('product_Data','all_bd_cities'), $data);
}

//return only all bd cities
public function all_bd_cities()
{
    $state_all_id = State::select('id')->where('country_id', 18)->get();
    $all_bd_cities = [];
    foreach($state_all_id as $state){
        $cities_of_bd = City::select('id','name')->where('state_id', $state->id)->get();
         if($cities_of_bd){
            foreach($cities_of_bd as $new_city){
                $all_bd_cities[$new_city->id] = $new_city->name;
            }
         }
    }
    return  $all_bd_cities;
}

public function pos_search(Request $request)
{ 
        if($request->ajax()){
                if(!empty($request->search)){
                    $match_products = Product::where('name','LIKE','%'.$request->search."%")->paginate(4);
                    return response()->json([
                        'match_products' => $match_products,
                    ]);
                }elseif(!empty($request->id)){
                    $match_products = Product::where('id',$request->id)->with('image_url')->get()->first();
                    //check discount date
                    $_readable_code = date("m/d/Y", $match_products['discount_end_date']);
                    $_current_date = date("m/d/Y");
                    if(strtotime($_readable_code) >= strtotime($_current_date)){
                        $_discount_date = true;
                    }else{
                        $_discount_date = false;
                    }
                    return response()->json([
                        'match_products' => $match_products,
                        'discount_date' => $_discount_date
                    ]);
                }    
                    
            
        }
}

public function customer_search(Request $request)
{
        
    if($request->ajax())
    {
        if(empty($request->id)){
            if(is_numeric($request->search)){
               $match_customer = User::where(function($query) use ($request) {
                                                $query->where('user_type', 'customer')
                                                    ->orWhere('user_type', 'dealer');
                                            })->where('phone','LIKE','%'.$request->search."%")->paginate(5);
            }else{
                $match_customer = User::where(function($query) use ($request) {
                                            $query->where('user_type', 'customer')
                                                ->orWhere('user_type', 'dealer');
                                        })->where('name','LIKE','%'.$request->search."%")->paginate(5);
            }
            
        }
        else
        {
         $match_customer = User::where('id', $request->id)->first();
        }
        
         return response()->json($match_customer );
    }
    
    
}

public function staff_search(Request $request)
{
        
    if($request->ajax())
    {
        if(empty($request->id)){
            if(is_numeric($request->search)){
               $match_customer = User::where('user_type', 'staff')->where('phone','LIKE','%'.$request->search."%")->paginate(5);
            }else{
                $match_customer = User::where('user_type', 'staff')->where('name','LIKE','%'.$request->search."%")->paginate(5);
            }
            
        }
        else
        {
         $match_customer = User::where('id', $request->id)->first();
        }
        
         return response()->json($match_customer );
    }
    
    
}

public function customer_store(Request $request)
{
   
        $match_customer_number = User::where('phone',$request->phone)->first();
        if(!$match_customer_number)
        {
        $user_instance = new User();
        $user_instance->name = $request->name;
        $user_instance->phone = "+88".$request->phone;
        $user_instance->employee_id = $request->employee_id ? $request->employee_id: NULL;

        if($request->has('employee_id')){
            $user_instance->user_type = 'employee';
            $user_instance->customer_type = 4;
            $user_instance->designation = $request->employee_designation;
        }

        if($request->has('employee_salary')){
            $user_instance->employee_salary = $request->employee_salary;

        }

        $user_instance->save();
        //address table
        $address_instance = new Address;
        $address_instance->user_id = $user_instance->id;
        $address_instance->phone = $user_instance->phone;
        $address_instance->address = $request->customer_address;
        if($request->customer_postal_code != ""){
            $address_instance->postal_code = $request->customer_postal_code;
        }
        $address_instance->default_shipping =  "1";
        $address_instance->default_billing =  "1";

        //condition for country
        $address_instance->country = "Bangladesh";
        $address_instance->country_id = "18";

        //add state
        if($request->state != ""){
            $state_details = State::where('id', $request->state)->first();
            $address_instance->state = $state_details->name;
            $address_instance->state_id = $state_details->id;
        }
  
      
        //condition for citites
        if($request->city != ""){
            $city_details_by_specefic_id = City::where('id',$request->city)->first();
            $address_instance->city = $city_details_by_specefic_id->name;
            $address_instance->city_id = $city_details_by_specefic_id->id;
        }
       
        $address_instance->save();

        if($request->has('employee_id')){
            return response()->json([
                'status'=>200,
                'message'=> 'Employee Added Successfully',
            ]);
        }

        return response()->json([
            'status'=>200,
            'message'=> 'Customer Added Successfully',
        ]);
    }
    else
    {
        return response()->json([
            'status'=>403,
            'message'=> 'Mobile Number Already Given',
        ]); 
    }
    
    
}


public function product_select_search(Request $request)
{
    if($request->ajax())
    {
        if(empty($request->brand_id)){                      
            $match_products = DB::table('product_categories')->leftJoin('products', function($join) {
                    $join->on('product_categories.product_id', '=', 'products.id');
                })
                ->leftJoin('uploads', function($join) {
                    $join->on('products.photos', '=', 'uploads.id');
                })
              ->where('product_categories.category_id',$request->category_id)
              ->where('product_categories.deleted_at', NULL)
              ->select('products.id','products.name','products.highest_price','uploads.file_name')
              ->paginate(16);
             
        }
        elseif(empty($request->category_id))
        {
            $match_products = DB::table('products')
            ->leftJoin('uploads', function($join) {
                $join->on('products.photos', '=', 'uploads.id');
            })
            ->where('products.brand_id',$request->brand_id)
            ->where('products.deleted_at', NULL)
            ->select('products.id','products.name','products.highest_price','uploads.file_name')
            ->paginate(16);
        }
        elseif(!empty($request->brand_id) && !empty($request->category_id))
        {
            $match_products = DB::table('product_categories')->leftJoin('products', function($join) {
                $join->on('product_categories.product_id', '=', 'products.id');
            })
            ->leftJoin('uploads', function($join) {
                $join->on('products.photos', '=', 'uploads.id');
            })
            ->where('products.brand_id',$request->brand_id)
            ->where('product_categories.category_id',$request->category_id)
            ->where('product_categories.deleted_at', NULL)
            ->select('products.id','products.name','products.highest_price','uploads.file_name')
            ->paginate(16);
        }
        
        return response()->json($match_products);
    }

    
    
}


public function create_order(Request $request)
{
    
   $total_product_items_number =  count($request->product_id);

   //combine order
   $combined_orders = new CombinedOrder();
   $combined_orders->user_id = $request->customer_id;
   $last_order = Order::orderBy('id', 'DESC')->first();
   $combined_orders->code = "MVI".date('is').($last_order->id+1);
   $combined_orders->grand_total = $request->grand_total;
   $combined_orders->save();

           // SMS integration Start
           $token = "7866132738dca110e68e8b7cbc10e238a12c992211";
           $url = "http://api.greenweb.com.bd/api.php";
           $message = "Maakview order ".$combined_orders->code." Placed from POS.";
           $data= array(
           'to'=>"+8801715448159",
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
    //order table
    $Order_instance = new Order();
    $Order_instance->user_id = $request->customer_id;
    $address_data = Address::where('user_id', $request->customer_id)->first();
    
    if($address_data)
    {
       
        //Null state and state id from address 
        if(isset($address_data['state'])){
            $address_data['state'] = "";
            $address_data['state_id'] = "";
        }
        $json_shipping_address_data = json_encode($address_data);
        $Order_instance->shipping_address =$json_shipping_address_data;
        $Order_instance->billing_address =$json_shipping_address_data;

    }
   
    $Order_instance->delivery_status = "delivered";
    $Order_instance->delivery_type = "standard";
    $Order_instance->payment_status = "paid";
    $Order_instance->payment_type = "cash_on_delivery";
    $Order_instance->code = 1;
    $Order_instance->is_pos = 1;
    $Order_instance->shop_id = 6;
    if( $request->shipping_cost == "")
    {
        $Order_instance->shipping_cost = 0;
    }else{
        $Order_instance->shipping_cost = $request->shipping_cost;
    }
    
    if($request->special_discount == ""){
        $Order_instance->special_discount = '0';
    }else{
        $Order_instance->special_discount = $request->special_discount;
    }
    
    $Order_instance->grand_total = $request->grand_total;
    $Order_instance->combined_order_id = $combined_orders->id;
    $Order_instance->created_by = Auth::id();
    $Order_instance->is_approved = 0;
    $Order_instance->save();

   if($total_product_items_number !== null){
        for($i = 0; $i<$total_product_items_number; $i++){

            $order_details = new OrderDetail();

             //add serial number
             $filter_product_serial_no = $this->string_to_array_by_comma($request->prod_serial_num[$i]);
             $get_unique_data = array_unique($filter_product_serial_no);
             $order_details->prod_serial_num = json_encode($get_unique_data);

            // //order details table
            $order_details->product_id = $request->product_id[$i];
            $order_details->product_variation_id = $request->product_id[$i];
            $order_details->actual_price = $request->actual_price[$i];
            $order_details->price = $request->unit_price[$i];
            if($request->tax == ""){
                $order_details->tax = '0';
            }else{
                $order_details->tax = $request->tax;
            }
            
            $order_details->quantity = $request->product_qty[$i];
            $order_details->total = $request->pro_sub_total[$i];
            $order_details->order_id = $Order_instance->id;
            $order_details->sold_by = $request->staff_id;
            $save_order = $order_details->save();

            if($save_order){
                $stock_product_serial_number = Product::where('id', $request->product_id[$i])->pluck('serial_no');
                $without_null_stock_serial = $this->convert_json_serial_numbers_to_arrays($stock_product_serial_number[0]);

                foreach ($filter_product_serial_no as $key => $order_values) {
                    foreach ($without_null_stock_serial as $key => $value) {
                        if ($value == $order_values) {
                        unset($without_null_stock_serial[$key]);
                        }
                    }
                    
                }
                Product::where('id', $request->product_id[$i])->update([
                    'serial_no' => array_values($without_null_stock_serial),
                    'stock_quantity' =>  count($without_null_stock_serial)
                ]);

            }
                  
        }
        return response()->json([
            'status'=>200,
            'message'=> 'Order Created Successfully',
        ]);
         
   }
  
}

public function match_product_serial(Request $request)
{
    $si = explode(" ",$request->serial_number);
    $check = array_search("/",$si);
    
    if($check){
        $_get_product = Product::whereJsonContains('serial_no', strval(trim($request->serial_number)))->paginate(1);
    }else{
        $_get_product = Product::whereJsonContains('serial_no', strval(trim(preg_replace('/[^a-zA-Z0-9_ -]/s','',$request->serial_number))))->paginate(1);
    }
    $_readable_code = date("m/d/Y", $_get_product[0]['discount_end_date']);
    $_current_date = date("m/d/Y");
    if(strtotime($_readable_code) >= strtotime($_current_date)){
        $_discount_date = true;
    }else{
        $_discount_date = false;
    }
    if($_get_product[0]['name']){
        return response()->json([
            'match_products' => $_get_product,
            'serial' => $request->serial_number,
            'product_name' => $_get_product[0]['name'],
            'discount_date' => $_discount_date,
        ]);
    
    }
    
    
}

public function match_product_barcode_manually(Request $request)
{
    
    $_get_product = Product::whereJsonContains('serial_no', strval(trim($request->serial_number)))->paginate(1);
    
    $_readable_code = date("m/d/Y", $_get_product[0]['discount_end_date']);
    $_current_date = date("m/d/Y");
    if(strtotime($_readable_code) >= strtotime($_current_date)){
        $_discount_date = true;
    }else{
        $_discount_date = false;
    }
    if($_get_product[0]['name']){
        return response()->json([
            'match_products' => $_get_product,
            'serial' => $request->serial_number,
            'product_name' => $_get_product[0]['name'],
            'discount_date' => $_discount_date,
        ]);
    
    }

}
public function store_advance_payment(Request $request){
    if($request->ajax()){
        $combined_order_id = CombinedOrder::select('id')->where('code',$request->order_id)->first();
        Order::where('combined_order_id',$combined_order_id->id)->update([
            'advance_payment' => $request->advance_payment
        ]);
        return response()->json([
            'message' => 'Advance Payment done Successfully'
        ]);
    }
        
}

public function get_advance_payment(Request $request){
    if($request->ajax()){
        $_combined_order_id = CombinedOrder::select('id')->where('code',$request->order_id)->first();
        $_advance_payment_data = Order::select('advance_payment')->where('combined_order_id', $_combined_order_id->id)->first();
        $chek_data =( $_advance_payment_data == null)? 'null': $_advance_payment_data;
        return response()->json([
            'advance_payment_data' => $_advance_payment_data,
            'check_data' => $chek_data
        ]);
    }
}

public function update_shipment_cost(Request $request)
{
    $upate_shpment_cost = $request->shipment_cost_value;
    $combined_order_id = $request->combined_order_id;
    $order_details = Order::select('shipping_cost','grand_total')->where('combined_order_id',$combined_order_id)->first();
    $previous_shiping_cost = $order_details->shipping_cost;
    $previous_grand_total = $order_details->grand_total;
    $updated_grand_total = ($previous_grand_total -  $previous_shiping_cost) + $upate_shpment_cost;
    $update_grand_total_in_combined_order = CombinedOrder::where('id',$combined_order_id)->update([
        'grand_total' => $updated_grand_total
    ]);
    $update_grand_total = Order::where('combined_order_id',$combined_order_id)->update([
        'grand_total' => $updated_grand_total
    ]);
    if($update_grand_total)
    {
        $update_shiping_cost = Order::where('combined_order_id',$combined_order_id)->update([
            'shipping_cost' => $upate_shpment_cost
        ]);
    }

    if($update_shiping_cost)
    {
        return response()->json([
            'status' => 200,
            'message' => 'Shipping  Cost Updaed Successfully'
        ]);
    }

   
}

public function get_shipment_cost(Request $request)
{
    if($request->ajax()){
     
        $shipment_cost = Order::select('shipping_cost')->where('combined_order_id',$request->combined_order_id)->first();
        $shipment_cost->shipping_cost;
        $check_shipping_cost =( $shipment_cost->shipping_cost == null)? 'null':$shipment_cost->shipping_cost;
        return response()->json([
            'shipping_cost' =>  $check_shipping_cost,
        ]);
    }
}

//*************POS function end*****************
//*************Inventory function start*****************

public function inventory_index()
{
    return view('backend.product.Inventory.home');
}


public function check_duplicate_product(Request $request)
{ 
    //find product serial and convert data to array 
    $find_product_id_wise = Product::where('id', $request->id)->pluck('serial_no');
    $without_null_inventory_serial = $this->convert_json_serial_numbers_to_arrays($find_product_id_wise[0]);

    //current pos serial number data convert to array
    $current_product_serial_no = explode(",", $request->serial_no);
    
    $count = count($current_product_serial_no)-2;
    
    $finding_sn = $current_product_serial_no[$count];
    $status = $this->array_new_search($without_null_inventory_serial,$finding_sn);
    
    if($status == 1){
        return response()->json([
            'status' => $status,
            'product_serial' => $current_product_serial_no[$count]
        ]);
    }else{
        return $status;
    }
}

public function array_new_search($arr, $item)
{
    foreach ($arr as $key => $value) { 
        if($value == $item){
            return 1;
            break;
        }
    }
}

public function store_inventory_data(Request $request)
{
    
    $total_product_item_numbers = count($request->product_id);
  
    //invoice number for purchase product
    $last_purchase_order = PurchaseTransaction::orderBy('id', 'DESC')->first();
       
    if(!empty($last_purchase_order->id)){
        $last_p_order = $last_purchase_order->id+1;
    }else{
        $last_p_order = 1;
    }
    
    $purchase_invoice = "MVPI".date('is').$last_p_order;

    
    if($total_product_item_numbers !== null){
        for($i = 0; $i <$total_product_item_numbers; $i++ ){
            //add data   on purchase_details table
            $purchase_details_instance = new PurchaseDetail();
            $purchase_details_instance->supplier_id = $request->supplier_id;
            $purchase_details_instance->product_id = $request->product_id[$i];
            $purchase_details_instance->purchase_price = $request->product_purchase_price[$i];
            $purchase_details_instance->invoice_numbers = $purchase_invoice;
            $purchase_details_instance->product_qty = $request->product_qty[$i];
   
            $without_null_product_serial_no =  $this->string_to_array_by_comma($request->prod_serial_num[$i]);
            $purchase_details_instance->serial_numbers = json_encode($without_null_product_serial_no);
            $purchase_details_instance->created_by = Auth::id();
            $save_purchase_details = $purchase_details_instance->save();

            if($save_purchase_details){
                //add serial and product qty, update purchase price on product table after inventory
                $previous_serial_no = Product::where('id', $request->product_id[$i])->pluck('serial_no');
                $without_null_prev_values = $this->convert_json_serial_numbers_to_arrays($previous_serial_no[0]);
        
                $merge_serial_no = array_merge_recursive($without_null_product_serial_no, $without_null_prev_values);
                
                //check already save serial number and took unique serial number only
                $get_unique_data = array_values(array_unique($merge_serial_no));
                $previous_stock_qty = Product::where('id', $request->product_id[$i])->pluck('stock_quantity');
                
                
                if($previous_stock_qty[0] !== ""){
                    $total_qty =   $previous_stock_qty[0] + (int) $request->product_qty[$i];
                    if( $total_qty ==  count($get_unique_data)){
                        $total_stock_qty = $total_qty;
                    }else{
                        $total_stock_qty = count($get_unique_data);
                    }
                }else{
                    $total_stock_qty = (int) $request->product_qty[$i];
                }
            
                $save_serial_numbers = Product::where('id', $request->product_id[$i])->update([
                    'serial_no' => $get_unique_data,
                    'stock_quantity' =>   $total_stock_qty,
                    'purchase_price' => $request->product_purchase_price[$i]
                ]);
                
            }
           
          
        }
        //add data to purchase transaction table
        $purchase_transaction_instance = new PurchaseTransaction();
        $purchase_transaction_instance->payable = $request->total_payable;
        $purchase_transaction_instance->paid = $request->total_paid;
        $purchase_transaction_instance->due = $request->total_due;
        $purchase_transaction_instance->payment_type = $request->payment_type;
        $purchase_transaction_instance->transaction_id = $request->transaction_id;
        $purchase_transaction_instance->purchase_invoice =  $purchase_details_instance->invoice_numbers;
        $purchase_transaction_instance->supplier_id =  $purchase_details_instance->supplier_id;
        $purchase_transaction_instance->save();

    }
    
    return response()->json([
        'status'=>200,
        'message'=> 'Inventory Updated Successfully',
    ]); 
   
        
}

//this function work for check product inventory is done or not
public function match_inventory_product(Request $request)
{

    //find product serial and convert data to array 
    $find_product_id_wise = Product::where('id', $request->id)->pluck('serial_no');
    $without_null_inventory_serial = $this->convert_json_serial_numbers_to_arrays($find_product_id_wise[0]);

    //current pos serial number data convert to array
    $current_product_serial_no = explode(",", $request->serial_no);
    $count = count($current_product_serial_no)-2;

    $finding_sn = $current_product_serial_no[$count];
    $status = $this->array_new_search($without_null_inventory_serial,$finding_sn);

    if($status == 1){
        return $status;
    }else{
        return $current_product_serial_no[$count];
    }
}

public function supplier_search(Request $request)
{
        
    if($request->ajax())
    {
        if(empty($request->id)){
            if(is_numeric($request->search)){
                $match_customer = Supplier::where('phone','LIKE','%'.$request->search."%")->paginate(5);
            }else{
                $match_customer = Supplier::where('name','LIKE','%'.$request->search."%")->paginate(5);
            }
             
        }
        else
        {
         $match_customer = Supplier::where('id', $request->id)->first();
        }
        
         return response()->json($match_customer );
    }
    
    
}

public function supplier_information_store(Request $request)
{
    if($request->ajax()){
       $match_supplier_phone = Supplier::where('phone', $request->phone)->first();
       if(!$match_supplier_phone)
       {
       $suppliers_instance = new Supplier();
       $suppliers_instance->name = $request->name;
       $suppliers_instance->phone = $request->phone;
       $suppliers_instance->email = $request->email;
       $suppliers_instance->address = $request->address;
       $save_suppliers_info = $suppliers_instance->save();

       if($save_suppliers_info){
           return response()->json([
            'status'=>200,
            'message'=> 'Supplier Added Successfully',
           ]);
       }
    }
    else
    {
        return response()->json([
            'status'=>403,
            'message'=> 'This phone number already given!',
           ]);
    }





    }
}



//*************Inventory function end*****************
//*************Purchase Return Product start**************
public function purhcase_return_product()
{
    $invoice_numbers = PurchaseDetail::distinct('invoice_numbers')->pluck('invoice_numbers');

    return view('backend.product.Inventory.purhcase_return_product', compact('invoice_numbers'));
}

public function purhcase_return_product_store(Request $request)
{
    // $fixed_serial = strval(trim($this->string_to_array_by_comma($request->prod_serial_num[0])[0]));
    // $fixed_purchase_data = PurchaseDetail::whereRaw("JSON_CONTAINS(serial_numbers, '\"$fixed_serial\"')")->get()[0];
    // $fixed_invoice_number = $fixed_purchase_data->invoice_numbers;
 

    $total_product_items_number =  count($request->product_id);
    if($total_product_items_number !== null){
        for($i = 0; $i<$total_product_items_number; $i++){
           

                //removed product serial from product table and update product table data
               
                $filter_product_serial_no = $this->string_to_array_by_comma($request->prod_serial_num[$i]);
                $stock_product_serial_number = Product::where('id', $request->product_id[$i])->pluck('serial_no');
                $without_null_stock_serial = $this->convert_json_serial_numbers_to_arrays($stock_product_serial_number[0]);

                $after_remove_serial_number_from_return_product = $this->remove_serial_numbers_from_previous_stock($filter_product_serial_no, $without_null_stock_serial);
             
                $update_product = Product::where('id', $request->product_id[$i])->update([
                    'serial_no' => array_values($after_remove_serial_number_from_return_product),
                    'stock_quantity' =>  count($after_remove_serial_number_from_return_product)
                ]);

                //update purchase_details 
               
                if($update_product){
                
                    $serialNumber = strval(trim($filter_product_serial_no[0]));
                    
                    $purchse_table_data = PurchaseDetail::where('product_id', $request->product_id[$i])->whereRaw("JSON_CONTAINS(serial_numbers, '\"$serialNumber\"')")->get();

                    // for($i = 0; $i <count($filter_product_serial_no); $i++){
                    //     $test_serial =  strval(trim($filter_product_serial_no[$i]));
                    //     $check_serial_for_purchase_order_number = PurchaseDetail::where('product_id', $request->product_id[$i])->where('invoice_numbers',$fixed_invoice_number)->whereRaw("JSON_CONTAINS(serial_numbers, '\"$test_serial\"')")->get();
                    //     //if check serial not exist then return for same invoice purchase 
                    // }

                  
                    //save purchase return table data
                    $purchase_return_instance = new PurchaseReturn();
                    $purchase_return_instance->product_id = $request->product_id[$i];
                    $purchase_return_instance->product_price = $purchse_table_data[0]->purchase_price;
                    $purchase_return_instance->purchase_invoices = $purchse_table_data[0]->invoice_numbers;
                    $purchase_return_instance->purchas_retrn_prod_serial = json_encode(array_unique($filter_product_serial_no));
                    $purchase_return_instance->save();

              
                }


            }
         
   }

   return response()->json([
    'status'=>200,
    'message'=> 'Purhcase Product Return Successfully Done',
   ]);

}

public function purhcase_return_product_list()
{
    $all_return_purchase_data =  DB::table('purchase_returns')
                                ->select('purchase_invoices', 'id')
                                ->groupBy('purchase_invoices')
                                ->get();

    return view('backend.product.Inventory.purchase_return_product_list', compact('all_return_purchase_data'));
}

public function purhcase_return_product_details($purchase_invoices)
{
    $single_purchase_details = PurchaseReturn::where('purchase_invoices', $purchase_invoices)->get();
    
    return view('backend.product.Inventory.purchase_return_details_product', compact('single_purchase_details'));
}

public function purhcase_return_product_delete($purchase_invoices)
{

    $delete_return_purchase_history = PurchaseReturn::where('purchase_invoices', $purchase_invoices)->delete();
    if($delete_return_purchase_history){
        return redirect()->route('purchase.return.product.list');;
    }

}

public function remove_serial_numbers_from_previous_stock($filter_product_serial_no, $without_null_stock_serial)
{
    foreach ($filter_product_serial_no as $key => $order_values) {
        foreach ($without_null_stock_serial as $key => $value) {
            if ($value == $order_values) {
            unset($without_null_stock_serial[$key]);
            }
        }
        
    }

    return $without_null_stock_serial;

}
//*************Purchase Return Product end****************


// Report Function start from here
public function summary_report(){
    //today query start
    $today = Carbon::now()->format('Y-m-d');
    $data['_today_register_in_site'] = User::where(DB::raw('CAST(created_at as date)'), $today)->get()->count();
    $data['_today_received_order'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('is_pos', '0')->get()->count();
    $data['_today_received_order_value'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('is_pos', '0')->sum('grand_total');
    $data['_today_confirmed_order'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('delivery_status', 'confirmed')->where('is_pos', '0')->get()->count();
    $data['_today_confirmed_order_value'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('delivery_status', 'confirmed')->where('is_pos', '0')->sum('grand_total');
    $data['_today_cancelled_order'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('delivery_status', 'cancelled')->where('is_pos', '0')->get()->count();
    $data['_today_cancelled_order_value'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('delivery_status', 'cancelled')->where('is_pos', '0')->sum('grand_total');
    $data['_today_delivered_order'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('delivery_status', 'delivered')->where('is_pos', '0')->get()->count();
    $data['_today_delivered_order_value'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('delivery_status', 'delivered')->where('is_pos', '0')->sum('grand_total');
    $data['_today_paid_order'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('payment_status', 'paid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->where('is_pos', '0')->get()->count();
    $data['_today_paid_order_value'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('payment_status', 'paid')->where(function($query) {
                                $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                            })->where('is_pos', '0')->sum('grand_total');
    $data['_today_unpaid_order'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('payment_status', 'unpaid')->where(function($query) {
                                            $query->orWhere('delivery_status','=', 'delivered')
                                            ->orWhere('delivery_status','=', 'confirmed')
                                            ->orWhere('delivery_status','=', 'processed');
                                        })->where('is_pos', '0')->get()->count();
    $data['_today_unpaid_order_value'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('payment_status', 'unpaid')->where(function($query) {
                                        $query->orWhere('delivery_status','=', 'delivered')
                                            ->orWhere('delivery_status','=', 'confirmed')
                                            ->orWhere('delivery_status','=', 'processed');
                                    })->where('is_pos', '0')->sum('grand_total');
    $data['_today_no_response_customer_order'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('delivery_status','order_placed')->where('is_pos', '0')->get()->count();
    $data['_today_no_response_customer_order_value'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('delivery_status','order_placed')->where('is_pos', '0')->sum('grand_total');
    $data['_today_product_shipped'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('delivery_status','shipped')->where('is_pos', '0')->get()->count();
    $data['_today_product_shipped_value'] = Order::where(DB::raw('CAST(created_at as date)'), $today)->where('delivery_status','shipped')->where('is_pos', '0')->sum('grand_total');
    //today query end
    //yesterday query start
    $yesterday = Carbon::yesterday()->format('Y-m-d');
    $data['_yesterday_register_in_site'] = User::where(DB::raw('CAST(created_at as date)'), $yesterday)->get()->count();
    $data['_yesterday_received_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->get()->count();
    $data['_yesterday_received_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->sum('grand_total');
    $data['_yesterday_confirmed_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('delivery_status', 'confirmed')->get()->count();
    $data['_yesterday_confirmed_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('delivery_status', 'confirmed')->sum('grand_total');
    $data['_yesterday_cancelled_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('delivery_status', 'cancelled')->get()->count();
    $data['_yesterday_cancelled_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('delivery_status', 'cancelled')->sum('grand_total');
    $data['_yesterday_delivered_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('delivery_status', 'delivered')->get()->count();
    $data['_yesterday_delivered_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('delivery_status', 'delivered')->sum('grand_total');
    $data['_yesterday_paid_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('payment_status', 'paid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->get()->count();
    $data['_yesterday_paid_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('payment_status', 'paid')->where(function($query) {
                                $query->orWhere('delivery_status','=', 'delivered')
                                ->orWhere('delivery_status','=', 'confirmed')
                                ->orWhere('delivery_status','=', 'processed');
                            })->sum('grand_total');

    $data['_yesterday_unpaid_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('payment_status', 'unpaid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->get()->count();
    $data['_yesterday_unpaid_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('payment_status', 'unpaid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->sum('grand_total');
    $data['_yesterday_no_response_customer_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('delivery_status','order_placed')->get()->count();
    $data['_yesterday_no_response_customer_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('delivery_status','order_placed')->sum('grand_total');
    $data['_yesterday_poduct_shipped'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('delivery_status','shipped')->get()->count();
    $data['_yesterday_poduct_shipped_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), $yesterday)->where('delivery_status','shipped')->sum('grand_total');
    //yesterday query end
    //last 7 days query start
    $_seven_days_end =Carbon::now()->subDays(7)->format('Y-m-d');
    $data['_last_seven_days_register_in_site'] = User::where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->get()->count();
    $data['_last_seven_days_received_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->get()->count();
    $data['_last_seven_days_received_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->sum('grand_total');
    $data['_last_seven_days_confirmed_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('delivery_status', 'confirmed')->get()->count();
    $data['_last_seven_days_confirmed_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('delivery_status', 'confirmed')->sum('grand_total');
    $data['_last_seven_days_cancelled_order'] = Order::where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('delivery_status', 'cancelled')->get()->count();
    $data['_last_seven_days_cancelled_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('delivery_status', 'cancelled')->sum('grand_total');
    $data['_last_seven_days_delivered_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('delivery_status', 'delivered')->get()->count();
    $data['_last_seven_days_delivered_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('delivery_status', 'delivered')->sum('grand_total');
    $data['_last_seven_days_paid_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('payment_status', 'paid')->where(function($query) {
                                        $query->orWhere('delivery_status','=', 'delivered')
                                        ->orWhere('delivery_status','=', 'confirmed')
                                        ->orWhere('delivery_status','=', 'processed');
                                    })->get()->count();
    $data['_last_seven_days_paid_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('payment_status', 'paid')->where(function($query) {
                                                $query->orWhere('delivery_status','=', 'delivered')
                                                ->orWhere('delivery_status','=', 'confirmed')
                                                ->orWhere('delivery_status','=', 'processed');
                                            })->sum('grand_total'); 
    $data['_last_seven_days_unpaid_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('payment_status', 'unpaid')->where(function($query) {
                                        $query->orWhere('delivery_status','=', 'delivered')
                                        ->orWhere('delivery_status','=', 'confirmed')
                                        ->orWhere('delivery_status','=', 'processed');
                                    })->get()->count();
    $data['_last_seven_days_unpaid_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('payment_status', 'unpaid')->where(function($query) {
                                                $query->orWhere('delivery_status','=', 'delivered')
                                                ->orWhere('delivery_status','=', 'confirmed')
                                                ->orWhere('delivery_status','=', 'processed');
                                            })->sum('grand_total');
    $data['_last_seven_days_customer_no_response_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('delivery_status','order_placed')->get()->count();
    $data['_last_seven_days_customer_no_response_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('delivery_status','order_placed')->sum('grand_total');
    $data['_last_seven_days_product_shipeed_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('delivery_status','shipped')->get()->count();
    $data['_last_seven_days_product_shipeed_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_seven_days_end)->where('delivery_status','shipped')->sum('grand_total');
    
    //last 15 days query start
    $_fifteen_days_end =Carbon::now()->subDays(15)->format('Y-m-d');
    $data['_fifteen_days_register_in_site'] = User::where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->get()->count();
    $data['_fifteen_days_received_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->get()->count();
    $data['_fifteen_days_received_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->sum('grand_total');
    $data['_fifteen_days_confirmed_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('delivery_status', 'confirmed')->get()->count();
    $data['_fifteen_days_confirmed_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('delivery_status', 'confirmed')->sum('grand_total');
    $data['_fifteen_days_cancelled_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('delivery_status', 'cancelled')->get()->count();
    $data['_fifteen_days_cancelled_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('delivery_status', 'cancelled')->sum('grand_total');
    $data['_fifteen_days_delivered'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('delivery_status', 'delivered')->get()->count();
    $data['_fifteen_days_delivered_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('delivery_status', 'delivered')->sum('grand_total');
    $data['_fifteen_days_paid_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('payment_status', 'paid')->where(function($query) {
                                        $query->orWhere('delivery_status','=', 'delivered')
                                        ->orWhere('delivery_status','=', 'confirmed')
                                        ->orWhere('delivery_status','=', 'processed');
                                    })->get()->count();
    $data['_fifteen_days_paid_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('payment_status', 'paid')->where(function($query) {
                                            $query->orWhere('delivery_status','=', 'delivered')
                                            ->orWhere('delivery_status','=', 'confirmed')
                                            ->orWhere('delivery_status','=', 'processed');
                                        })->sum('grand_total');
    $data['_fifteen_days_unpaid_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('payment_status', 'unpaid')->where(function($query) {
                                        $query->orWhere('delivery_status','=', 'delivered')
                                        ->orWhere('delivery_status','=', 'confirmed')
                                        ->orWhere('delivery_status','=', 'processed');
                                    })->get()->count();
    $data['_fifteen_days_unpaid_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('payment_status', 'unpaid')->where(function($query) {
                                                $query->orWhere('delivery_status','=', 'delivered')
                                                ->orWhere('delivery_status','=', 'confirmed')
                                                ->orWhere('delivery_status','=', 'processed');
                                            })->sum('grand_total');
    $data['_fifteen_days_customer_no_response_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('delivery_status','order_placed')->get()->count();
    $data['_fifteen_days_customer_no_response_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('delivery_status','order_placed')->sum('grand_total');
    $data['_fifteen_days_product_shipeed_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('delivery_status','shipped')->get()->count();
    $data['_fifteen_days_product_shipeed_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_fifteen_days_end)->where('delivery_status','shipped')->sum('grand_total');

    //last 15 days query end
    //last 30 days query start
    $_thirty_days_end =Carbon::now()->subDays(30)->format('Y-m-d');
    $data['_thirty_days_register_in_site'] = User::where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->get()->count();
    $data['_thirty_days_received_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->get()->count();
    $data['_thirty_days_received_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->sum('grand_total');
    $data['_thirty_days_confirmed_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('delivery_status', 'confirmed')->get()->count();
    $data['_thirty_days_confirmed_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('delivery_status', 'confirmed')->sum('grand_total');
    $data['_thirty_days_cancelled_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('delivery_status', 'cancelled')->get()->count();
    $data['_thirty_days_cancelled_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('delivery_status', 'cancelled')->sum('grand_total');
    $data['_thirty_days_delivered_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('delivery_status', 'delivered')->get()->count();
    $data['_thirty_days_delivered_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('delivery_status', 'delivered')->sum('grand_total');
    $data['_thirty_days_paid_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('payment_status', 'paid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->get()->count();
    $data['_thirty_days_paid_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('payment_status', 'paid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->sum('grand_total');
    $data['_thirty_days_unpaid_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('payment_status', 'unpaid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->get()->count();
    $data['_thirty_days_unpaid_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('payment_status', 'unpaid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->sum('grand_total');
    $data['_thirty_days_customer_no_response_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('delivery_status','order_placed')->get()->count();
    $data['_thirty_days_customer_no_response_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('delivery_status','order_placed')->sum('grand_total');
    $data['_thirty_days_product_shipeed_order'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('delivery_status','shipped')->get()->count();
    $data['_thirty_days_product_shipeed_order_value'] = Order::where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $yesterday)->where(DB::raw('CAST(created_at as date)'), '>=', $_thirty_days_end)->where('delivery_status','shipped')->sum('grand_total');
    //last 30 days query end

    
    return view('backend.product.report.summary_report',$data);
    
}

public function summary_report_by_date(Request $request)
{
    $start_date = $request->start_date;
    $end_date = $request->end_date;
 
    $all_date = [];
    $startDate = strtotime($request->start_date);
    $endDate = strtotime($request->end_date);
  
    for ($currentDate = $startDate; $currentDate <= $endDate; 
                                    $currentDate += (86400)) {
                                            
        $every_single_date = date('Y-m-d', $currentDate);
        $all_date[] = $every_single_date;
    }
   

 
    $data['_register_in_site'] = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->groupBy('date')->orderBy('date', 'desc')->get();
    
    $data['_received_order'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->groupBy('date')->orderBy('date', 'desc')->get();
    
    $data['_received_order_value'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->groupBy('date')->orderBy('date', 'desc')->get();
   
    $data['_confirmed_order'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('delivery_status', 'confirmed')->groupBy('date')->orderBy('date', 'desc')->get();
    
    $data['_confirmed_order_value'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('delivery_status', 'confirmed')->groupBy('date')->orderBy('date', 'desc')->get();
  
    $data['_cancelled_order'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('delivery_status', 'cancelled')->groupBy('date')->orderBy('date', 'desc')->get();
    
    $data['_cancelled_order_value'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('delivery_status', 'cancelled')->groupBy('date')->orderBy('date', 'desc')->get();
   
    $data['_delivered_order'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('delivery_status', 'delivered')->groupBy('date')->orderBy('date', 'desc')->get();
   
    $data['_delivered_order_value'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('delivery_status', 'delivered')->groupBy('date')->orderBy('date', 'desc')->get();
   
    $data['_paid_order'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('payment_status', 'paid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->groupBy('date')->orderBy('date', 'desc')->get();
   
    $data['_paid_order_value'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('payment_status', 'paid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->groupBy('date')->orderBy('date', 'desc')->get();
                                
    $data['_unpaid_order'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('payment_status', 'unpaid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->groupBy('date')->orderBy('date', 'desc')->get();
                               
    $data['_unpaid_order_value'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('payment_status', 'unpaid')->where(function($query) {
                                        $query->orWhere('delivery_status','=', 'delivered')
                                        ->orWhere('delivery_status','=', 'confirmed')
                                        ->orWhere('delivery_status','=', 'processed');
                                    })->groupBy('date')->orderBy('date', 'desc')->get();
    $data['_customer_no_response_order'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('delivery_status', 'order_placed')->groupBy('date')->orderBy('date', 'desc')->get();
    $data['_customer_no_response_order_value'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('delivery_status', 'order_placed')->groupBy('date')->orderBy('date', 'desc')->get();
    $data['_product_shipped_order'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('delivery_status', 'shipped')->groupBy('date')->orderBy('date', 'desc')->get();
    $data['_product_shipped_order_value'] = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->where('delivery_status', 'shipped')->groupBy('date')->orderBy('date', 'desc')->get();
                                    
    return view('backend.product.report.summary_report_by_date', $data, compact('all_date'));

}

public function summary_report_by_month(Request $request)
{
    
    $_start_month = $request->start_month;
    $_end_month = $request->end_month;
    $_year = $request->year;
    $all_month = [];
    for($_current_month = $_start_month; $_current_month <= $request->end_month;$_current_month++ )
    {
        $all_month[] = (int) $_current_month;

    }
    
    $data['_register_in_site'] = User::select(DB::raw("MONTH(created_at) month"), DB::raw('count(*) as total'))->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->whereYear('created_at', $_year)->groupBy(DB::raw('MONTH(created_at)'))->get();
    
    $data['_received_order'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->whereYear('created_at', $_year)->groupBy(DB::raw('MONTH(created_at)'))->get();
   
    $data['_received_order_value'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->whereYear('created_at', $_year)->groupBy(DB::raw('MONTH(created_at)'))->get();
    $data['_confirmed_order'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('delivery_status', 'confirmed')->whereYear('created_at', $_year)->groupBy(DB::raw('MONTH(created_at)'))->get();
    

    $data['_confirmed_order_value'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('delivery_status', 'confirmed')->whereYear('created_at', $_year)->groupBy(DB::raw('MONTH(created_at)'))->get();
    $data['_confirmed_order_value'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('delivery_status', 'confirmed')->whereYear('created_at', $_year)->groupBy(DB::raw('MONTH(created_at)'))->get();
    
    $data['_cancelled_order'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('delivery_status', 'cancelled')->whereYear('created_at', $_year)->groupBy(DB::raw('MONTH(created_at)'))->get();
    
    $data['_cancelled_order_value'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('delivery_status', 'cancelled')->whereYear('created_at', $_year)->groupBy(DB::raw('MONTH(created_at)'))->get();
   
    $data['_delivered_order'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('delivery_status', 'delivered')->whereYear('created_at', $_year)->groupBy(DB::raw('MONTH(created_at)'))->get();
    
    $data['_delivered_order_value'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('delivery_status', 'delivered')->whereYear('created_at', $_year)->groupBy(DB::raw('MONTH(created_at)'))->get();
    
    $data['_paid_order'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('payment_status', 'paid')->whereYear('created_at', $_year)->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->groupBy(DB::raw('MONTH(created_at)'))->get();
                               
    $data['_paid_order_value'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('payment_status', 'paid')->whereYear('created_at', $_year)->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->groupBy(DB::raw('MONTH(created_at)'))->get();
                                              
    $data['_unpaid_order'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('payment_status', 'unpaid')->whereYear('created_at', $_year)->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->groupBy(DB::raw('MONTH(created_at)'))->get();
                                                    
    $data['_unpaid_order_value'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->where('payment_status', 'unpaid')->whereYear('created_at', $_year)->where(function($query) {
                                        $query->orWhere('delivery_status','=', 'delivered')
                                        ->orWhere('delivery_status','=', 'confirmed')
                                        ->orWhere('delivery_status','=', 'processed');
                                    })->groupBy(DB::raw('MONTH(created_at)'))->get();

    $data['_customer_no_response_order'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->whereYear('created_at', $_year)->where('delivery_status', 'order_placed')->groupBy(DB::raw('MONTH(created_at)'))->orderBy('month', 'desc')->get();
    $data['_customer_no_response_order_value'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->whereYear('created_at', $_year)->where('delivery_status', 'order_placed')->groupBy(DB::raw('MONTH(created_at)'))->orderBy('month', 'desc')->get();
    $data['_product_shipped_order'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->whereYear('created_at', $_year)->where('delivery_status', 'shipped')->groupBy(DB::raw('MONTH(created_at)'))->orderBy('month', 'desc')->get();
    $data['_product_shipped_order_value'] = Order::select(DB::raw("MONTH(created_at) month"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereMonth('created_at', '<=', $_end_month)->whereMonth('created_at', '>=', $_start_month)->whereYear('created_at', $_year)->where('delivery_status', 'shipped')->groupBy(DB::raw('MONTH(created_at)'))->orderBy('month', 'desc')->get();                                
    
    return view('backend.product.report.summary_report_by_month',$data, compact('all_month','_year'));
    
}

public function summary_report_by_year(Request $request)
{
 
    $_start_year = $request->start_year;
    $_end_year = $request->end_year;
  
    $all_year= [];
    for($_current_year = $_start_year; $_current_year <= $_end_year;$_current_year++ )
    {
        $all_year[] = (int) $_current_year;

    }
 

    $data['_register_in_site'] = User::select(DB::raw("YEAR(created_at) year"), DB::raw('count(*) as total'))->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->groupBy(DB::raw('YEAR(created_at)'))->get();
    
    $data['_received_order'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->groupBy(DB::raw('YEAR(created_at)'))->get();
   
    $data['_received_order_value'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->groupBy(DB::raw('YEAR(created_at)'))->get();
  
    $data['_confirmed_order'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'confirmed')->groupBy(DB::raw('YEAR(created_at)'))->get();

    $data['_confirmed_order_value'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'confirmed')->groupBy(DB::raw('YEAR(created_at)'))->get();
    
    $data['_confirmed_order_value'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'confirmed')->groupBy(DB::raw('YEAR(created_at)'))->get();
    
    $data['_cancelled_order'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'cancelled')->groupBy(DB::raw('YEAR(created_at)'))->get();
   
    $data['_cancelled_order_value'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'cancelled')->groupBy(DB::raw('YEAR(created_at)'))->get();
    
    $data['_delivered_order'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'delivered')->groupBy(DB::raw('YEAR(created_at)'))->get();
    
    $data['_delivered_order_value'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'delivered')->groupBy(DB::raw('YEAR(created_at)'))->get();
    
    $data['_paid_order'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('payment_status', 'paid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->groupBy(DB::raw('YEAR(created_at)'))->get();
                                                       
    $data['_paid_order_value'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('payment_status', 'paid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->groupBy(DB::raw('YEAR(created_at)'))->get();
                                              
    $data['_unpaid_order'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('payment_status', 'unpaid')->where(function($query) {
                                    $query->orWhere('delivery_status','=', 'delivered')
                                    ->orWhere('delivery_status','=', 'confirmed')
                                    ->orWhere('delivery_status','=', 'processed');
                                })->groupBy(DB::raw('YEAR(created_at)'))->get();
                                                        
    $data['_unpaid_order_value'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('payment_status', 'unpaid')->where(function($query) {
                                        $query->orWhere('delivery_status','=', 'delivered')
                                        ->orWhere('delivery_status','=', 'confirmed')
                                        ->orWhere('delivery_status','=', 'processed');
                                    })->groupBy(DB::raw('YEAR(created_at)'))->get();

    $data['_customer_no_response_order'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'order_placed')->groupBy(DB::raw('YEAR(created_at)'))->orderBy('year', 'desc')->get();
    $data['_customer_no_response_order_value'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'order_placed')->groupBy(DB::raw('YEAR(created_at)'))->orderBy('year', 'desc')->get();
    $data['_product_shipped_order'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('count(*) as total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'shipped')->groupBy(DB::raw('YEAR(created_at)'))->orderBy('year', 'desc')->get();
    $data['_product_shipped_order_value'] = Order::select(DB::raw("YEAR(created_at) year"), DB::raw('sum(grand_total) AS grand_total'))->where('is_pos', '0')->whereYear('created_at', '<=', $_end_year)->whereYear('created_at', '>=', $_start_year)->where('delivery_status', 'shipped')->groupBy(DB::raw('YEAR(created_at)'))->orderBy('year', 'desc')->get();                                                                
    
    return view('backend.product.report.summary_report_by_year', $data, compact('all_year'));
}

public function product_sale_report()
{
    $product_highest_sale_data_by_date_range =  DB::table('order_details')
                                                                ->leftJoin('orders', function($join) {
                                                                    $join->on('order_details.order_id', '=', 'orders.id');
                                                                })
                                                                ->where('payment_status','paid')
                                                                ->leftJoin('products', function($join) {
                                                                    $join->on('order_details.product_id', '=', 'products.id');
                                                                })
                                                                ->groupBy('product_id')->orderBy('quantity', 'desc')
                                                                ->select([
                                                                    'product_id',
                                                                    DB::raw('sum(quantity) AS quantity'),
                                                                    DB::raw('sum(special_discount) AS special_discount'),
                                                                    'total','products.name'
                                                                ])->get();
                                                                 
    return view('backend.product.report.product_sale_report', compact('product_highest_sale_data_by_date_range'));
}

public function product_sale_report_report_wise(Request $request)
{
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    $product_highest_sale_data_by_date_range =  DB::table('order_details')
                                                ->leftJoin('orders', function($join) use ($start_date, $end_date ) {
                                                    $join->on('order_details.order_id', '=', 'orders.id')
                                                    ->where('order_details.created_at', '<=',$end_date)
                                                    ->where('order_details.created_at', '>=',$start_date);
                                                })
                                                ->where('payment_status','paid')
                                                ->leftJoin('products', function($join) {
                                                    $join->on('order_details.product_id', '=', 'products.id');
                                                })
                                                ->groupBy('product_id')->orderBy('quantity', 'desc')
                                                ->select([
                                                    'product_id',
                                                    DB::raw('sum(quantity) AS quantity'),
                                                    DB::raw('sum(special_discount) AS special_discount'),
                                                    'total','products.name'
                                                ])->get();
                                                            
    return view('backend.product.report.product_sale_report_date_wise', compact('product_highest_sale_data_by_date_range', 'start_date','end_date'));
}

public function stock_report()
{

    $stock_report =  Product::select('name','stock_quantity','purchase_price','highest_price','dealer_price','corporate_price',DB::raw('(purchase_price*stock_quantity) as total_purchase_amount'),DB::raw('(highest_price*stock_quantity) as total_sale_amount'),DB::raw('(dealer_price*stock_quantity) as total_sale_dealer_amount'))->where('stock_quantity','>','0')->get();
    // dd($stock_report);
    return view('backend.product.report.stock_report', compact('stock_report'));

}

public function cash_report()
{

    //$order_value =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->where('delivery_status','=','shipped')->orWhere('delivery_status','=','delivered')->get();
    $order_value1 =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->whereDate('updated_at', Carbon::today())->where('delivery_status','shipped')->get();
    $order_value2 =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->whereDate('updated_at', Carbon::today())->where('delivery_status','delivered')->get();
    $order_value=($order_value1[0]->total_amount-$order_value1[0]->total_discount)+($order_value2[0]->total_amount-$order_value2[0]->total_discount);
    
    $total_received_today1 =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->whereDate('updated_at', Carbon::today())->where('delivery_status','shipped')->where('payment_status','paid')->get();
    $total_received_today2 =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->whereDate('updated_at', Carbon::today())->where('delivery_status','delivered')->where('payment_status','paid')->get();
    $total_received_today=($total_received_today1[0]->total_amount-$total_received_today1[0]->total_discount)+($total_received_today2[0]->total_amount-$total_received_today2[0]->total_discount);
    
    $total_receivable_due_1 =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->where('delivery_status','shipped')->where('payment_status','unpaid')->get();
    $total_receivable_due_2 =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->where('delivery_status','delivered')->where('payment_status','unpaid')->get();
    $total_receivable_due=($total_receivable_due_1[0]->total_amount-$total_receivable_due_1[0]->total_discount)+($total_receivable_due_2[0]->total_amount-$total_receivable_due_2[0]->total_discount);
    
    $total_receivable1 =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->where('delivery_status','shipped')->get();
    $total_receivable2 =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->where('delivery_status','delivered')->get();
    $total_receivable=($total_receivable1[0]->total_amount-$total_receivable1[0]->total_discount)+($total_receivable2[0]->total_amount-$total_receivable2[0]->total_discount);
    
    $total_received_all1 =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->where('delivery_status','shipped')->where('payment_status','paid')->get();
    $total_received_all2 =  Order::select(DB::raw('SUM(grand_total) as total_amount'),DB::raw('SUM(special_discount) as total_discount'))->where('delivery_status','delivered')->where('payment_status','paid')->get();
    $total_received_all=($total_received_all1[0]->total_amount-$total_received_all1[0]->total_discount)+($total_received_all2[0]->total_amount-$total_received_all2[0]->total_discount);
    
    //   dd($order_value2);
    return view('backend.product.report.cash_report', compact('order_value','total_received_today','total_receivable_due','total_receivable','total_received_all'));

}

public function metrics_report()
{

     $order_monthly_wise =  Order::select(DB::raw('count(id) as `data`'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))->get();

        //  dd($order_monthly_wise);

    return view('backend.product.report.metrics_report', compact('order_monthly_wise'));

}

// Report Function end from here

//purchase_order_invoice
public function purchase_order_home()
{
  
    $purchase_invoice_numbers = PurchaseDetail::select('invoice_numbers','create_money_payment', 'created_at')->groupBy('invoice_numbers')->OrderBy('created_at','DESC')->get();
    return view('backend.orders.purchase_home', compact('purchase_invoice_numbers'));
}

public function purchase_due_list()
{
    $purchase_due_invoice_numbers = PurchaseTransaction::select('purchase_invoice')->where('due', '>', 0)->OrderBy('created_at','DESC')->get();
    return view('backend.orders.purchase_due_list', compact('purchase_due_invoice_numbers'));
}

public function purchase_due_details($invoice_number)
{
    $purchase_order_details = PurchaseDetail::select('supplier_id', 'invoice_numbers','created_at','created_by')->where('invoice_numbers', $invoice_number)->first();
    $purchase_order_table_details = PurchaseDetail::where('invoice_numbers', $invoice_number)->get();
  
    $total_user_order = PurchaseTransaction::where('supplier_id',$purchase_order_details->supplier_id)
                                ->count();
    $supplier_info = Supplier::find($purchase_order_details->supplier_id);
    $transaction_details = PurchaseTransaction::where('purchase_invoice', $invoice_number)->first();
    return view('backend.orders.purchase_due_details', compact('purchase_order_details', 'total_user_order','supplier_info','purchase_order_table_details','transaction_details'));
}

public function purchase_order_view(Request $request, $invoice_number)
{
    $purchase_order_details = PurchaseDetail::select('supplier_id', 'invoice_numbers','created_at','created_by')->where('invoice_numbers', $invoice_number)->first();
    $purchase_order_table_details = PurchaseDetail::where('invoice_numbers', $invoice_number)->get();
  
    $total_user_order = PurchaseTransaction::where('supplier_id',$purchase_order_details->supplier_id)
                                ->count();
    $supplier_info = Supplier::find($purchase_order_details->supplier_id);
    $transaction_details = PurchaseTransaction::where('purchase_invoice', $invoice_number)->first();
    return view('backend.orders.purchase_details', compact('purchase_order_details', 'total_user_order','supplier_info','purchase_order_table_details','transaction_details'));
}

public function purchase_order_print(Request $request, $invoice_number)
{
    $purchase_order_details = PurchaseDetail::select('supplier_id', 'invoice_numbers','created_at','created_by')->where('invoice_numbers', $invoice_number)->first();
    $purchase_order_table_details = PurchaseDetail::where('invoice_numbers', $invoice_number)->get();
  
    $total_user_order = PurchaseTransaction::where('supplier_id',$purchase_order_details->supplier_id)
                                ->count();
    $supplier_info = Supplier::find($purchase_order_details->supplier_id);
    $transaction_details = PurchaseTransaction::where('purchase_invoice', $invoice_number)->first();
    return view('backend.orders.purchase_print', compact('purchase_order_details', 'total_user_order','supplier_info','purchase_order_table_details','transaction_details'));
}

public function due_payment(Request $request)
{
    if($request->ajax()){
        $transaction_information = PurchaseTransaction::select('payable', 'paid')->where('purchase_invoice', $request->puchase_invoice)->first();
        $total_paid = (!empty($transaction_information->paid )? $transaction_information->paid :0 )+ $request->due_payment;
        $due_balance_after_paid_due = $transaction_information->payable - $total_paid;

        //udpate due payment on transaction table
        PurchaseTransaction::where('purchase_invoice', $request->puchase_invoice)->update([
            'paid' => $total_paid,
            'due' => $due_balance_after_paid_due
        ]);

        //user history add 
        $due_payment = array('due_payment'=>$request->due_payment);
        $user_history_instance = new UserHistory();        
        $user_history_instance->user_id = Auth::id();
        $user_history_instance->user_action = "Due payment";
        $user_history_instance->invoice_id = $request->puchase_invoice;
        $user_history_instance->change_information = json_encode($due_payment);
        $save_user_history = $user_history_instance->save();

        if($save_user_history){
            return response()->json([
                'status' => 200,
                'message' => 'Due payment store successfully',
                'total_paid' => $total_paid,
                'due_balance_after_paid_due' => $due_balance_after_paid_due,
            ]);
        }
        
    }

}

public function supplier_payment_receipt_create($invoice_number)
{
    $purchase_invoice_number = $invoice_number;
    return view('backend.orders.supplier_payment_receipt_create', compact('purchase_invoice_number'));
}

public function supplier_payment_receipt_store(Request $request)
{
    
     //validation 
     $validatedData = $request->validate([
        'order_invoice' => 'required',
        'total_payment_in_number' => 'required',
        'total_payment_in_word' => 'required',
        'maakview_checque_number' => 'required',
        'bill_info' => 'required'
    ]);
    
   
    if($validatedData){
        $instance_of_money_payment = new MoneyPayment();
        $instance_of_money_payment->order_invoice = $request->order_invoice;
        $instance_of_money_payment->total_payment_in_number = $request->total_payment_in_number;
        $instance_of_money_payment->maakview_account_name = $request->maakview_account_name;
        $instance_of_money_payment->total_payment_in_word = $request->total_payment_in_word;
        $instance_of_money_payment->maakview_checque_number = $request->maakview_checque_number;
        $instance_of_money_payment->maakview_cheque_date = $request->maakview_cheque_date;
        $instance_of_money_payment->bill_info = $request->bill_info;
        $save_money_receipt = $instance_of_money_payment->save();

        if($save_money_receipt){
             //save money payment status in purchase details table
             PurchaseDetail::where('invoice_numbers',$request->order_invoice)->update([
                'create_money_payment' => 1
            ]);

            return  redirect()->route('orders.purchase_order.home');
        }


    }

    

}

public function supplier_payment_receipt_edit($invoice_number)
{
    $purchase_invoice_number = $invoice_number;
    $supplier_payment_receipt_data = MoneyPayment::where('order_invoice', $purchase_invoice_number)->first();
    return view('backend.orders.supplier_payment_receipt_edit', compact('purchase_invoice_number', 'supplier_payment_receipt_data'));

}

public function supplier_payment_receipt_update(Request $request, $invoice_number)
{

     //validation 
     $validatedData = $request->validate([
        'order_invoice' => 'required',
        'total_payment_in_number' => 'required',
        'total_payment_in_word' => 'required',
        'maakview_checque_number' => 'required',
        'bill_info' => 'required'
    ]);

    if($validatedData){
        MoneyPayment::where('order_invoice', $invoice_number)->update([
            'order_invoice' => $request->order_invoice,
            'total_payment_in_number' => $request->total_payment_in_number,
            'maakview_account_name' => $request->maakview_account_name,
            'total_payment_in_word' => $request->total_payment_in_word,
            'maakview_checque_number' => $request->maakview_checque_number,
            'maakview_cheque_date' => $request->maakview_cheque_date,
            'bill_info' => $request->bill_info
        ]);
        return  redirect()->route('orders.purchase_order.home');
    }
}

public function supplier_payment_receipt_print($invoice_number)
{
    $supplier_money_payment_data = MoneyPayment::where('order_invoice', $invoice_number)->first();
    $get_supplier_id = PurchaseDetail::select('supplier_id', 'created_at')->where('invoice_numbers', $invoice_number)->first();
    $user_name = 'Not Found';
    $get_supplier_name =  User::select('name')->where('id', $get_supplier_id->supplier_id)->first();
    if($get_supplier_name != null){
        $user_name = $get_supplier_name->name;
    }
    $purchase_created_date = $get_supplier_id->created_at;
    

    return view('backend.invoices.supplier_money_payment_print', compact('supplier_money_payment_data', 'user_name','purchase_created_date'));
}

public function purchase_order_delete(Request $request)
{
    $invoice_number =  $request->invoice_number;
  
    //delete puchase trqansaction table 
    $delete_purchase_transaction_table = PurchaseTransaction::where('purchase_invoice', $invoice_number)->delete();
    if($delete_purchase_transaction_table)
    {
        $purchase_details = PurchaseDetail::select('product_id')->where('invoice_numbers', $invoice_number)->get();
        if($purchase_details !== null){
            
            foreach($purchase_details as $key => $product)
            {
                $purchase_proudct_serial = PurchaseDetail::where('product_id', $product->product_id)->where('invoice_numbers', $invoice_number)->pluck('serial_numbers');
                $purchase_serial_within_array = $this->convert_json_serial_numbers_to_arrays($purchase_proudct_serial);
                $stock_product_serial_number = Product::where('id', $product->product_id)->pluck('serial_no');
                $stock_serial_numbers_in_array = $this->convert_json_serial_numbers_to_arrays($stock_product_serial_number);
                $remove_purhcase_serial_number_from_stock_serial = $this->unique_array_values_from_two_array($stock_serial_numbers_in_array,$purchase_serial_within_array);
                //after remove purhcase product re-stock in proudct 
                $re_stock_product = Product::where('id', $product->product_id)->update([
                    'serial_no' => array_values($remove_purhcase_serial_number_from_stock_serial),
                    'stock_quantity' =>  count($remove_purhcase_serial_number_from_stock_serial)
                ]);

                if($re_stock_product)
                {
                    PurchaseDetail::where('product_id', $product->product_id)->where('invoice_numbers', $invoice_number)->delete();

                }
            

            }
        
        }

        return response()->json([
            'status' => 200,
            'message' => 'Purhcase Deleted successfully',
            'invoice' => $invoice_number
        ]);
    }

}
//this function take two array 
//from first array remove all the element which is similar to second array and first parent array will  changed
public function unique_array_values_from_two_array($stock_serial, $non_stock_serial)
{
    foreach ($non_stock_serial as $key => $order_values) {
        foreach ($stock_serial as $key => $value) {
            if ($value == $order_values) {
            unset($stock_serial[$key]);
            }
        }
        
    }
    return $stock_serial;


}

//this function takes json formate values and return a array 
public function convert_json_serial_numbers_to_arrays($json_formate_product_serial)
{
    $product_serial_in_array = explode(",", $json_formate_product_serial);
    $newArray = [];
    foreach($product_serial_in_array as $item){
        array_push($newArray, preg_replace(' /[^A-Za-z0-9]+/', '', $item));
    }

    $without_null_purchase_serial_numbers = array_filter($newArray, function($item){
        return $item !== "" && $item !== "," ;
    });

    return $without_null_purchase_serial_numbers;

}

//take string and return array values
public function string_to_array_by_comma($string_values)
{
    $string_to_array = explode(",", $string_values);
    $string_to_array_filter_values = array_filter($string_to_array, function($item){
        return $item !== "" && $item !== "," ;
    });

    return $string_to_array_filter_values;
}

//start user history 
public function advance_paymnet_history(Request $request)
{
    if($request->ajax())
    {
        $combined_order_id = CombinedOrder::select('id')->where('code', $request->order_id)->first();
        $previous_advance_payment = Order::select('advance_payment')->where('combined_order_id', $combined_order_id->id)->first();
       
        if($previous_advance_payment->advance_payment != $request->advance_payment)
        {
            
            $adv_payment = array('adv_payment'=>$request->advance_payment);
            $user_history_instance = new UserHistory();        
            $user_history_instance->user_id = Auth::id();
            $user_history_instance->user_action = "Advance payment";
            $user_history_instance->invoice_id = $request->order_id;
            $user_history_instance->change_information = json_encode($adv_payment);
            $save_user_history = $user_history_instance->save();
            if($save_user_history)
            {
                return response()->json([
                    'status' => 200,
                ]);
            }
        }
        

    }

}

public function order_payment_status_history(Request $request)
{
    if($request->ajax())
    {
        $combined_order_id =  Order::select('combined_order_id')->where('id',$request->order_id)->first();
        $invoice_number = CombinedOrder::select('code')->where('id',$combined_order_id->combined_order_id)->first();
        $payment_status = array('payment_status'=>$request->status);
        $user_history_instance = new UserHistory();
        $user_history_instance->user_id = Auth::id();
        $user_history_instance->user_action = "payment status";
        $user_history_instance->invoice_id = $invoice_number->code;
        $user_history_instance->change_information = json_encode($payment_status);
        $save_user_history = $user_history_instance->save();
        if($save_user_history)
        {
            return response()->json([
                'status' => 200,
            ]);
        }
        
    }
}

public function order_delivery_status_history(Request $request)
{
    if($request->ajax())
    {
        $combined_order_data =  Order::select('combined_order_id')->where('id',$request->order_id)->first();
        $invoice_number = CombinedOrder::select('code')->where('id',$combined_order_data->combined_order_id)->first();
        
        $delivery_status = array('delivery_status'=>$request->status);
        $user_history_instance = new UserHistory();
        $user_history_instance->user_id = Auth::id();
        $user_history_instance->user_action = "delivery status";
        $user_history_instance->invoice_id = $invoice_number->code;
        $user_history_instance->change_information = json_encode($delivery_status);
        $save_user_history = $user_history_instance->save();
        if($save_user_history)
        {
            return response()->json([
                'status' => 200,
            ]);
        }
    }
}

public function order_shipment_cost_history(Request $request)
{
    if($request->ajax())
    {
        $previous_shipping_cost = Order::select('shipping_cost')->where('combined_order_id',$request->combined_order_id)->first();
        $invoice_number = CombinedOrder::select('code')->where('id',$request->combined_order_id)->first();

        if($previous_shipping_cost->shipping_cost != $request->shipment_cost_value)
        {
            $shipment_cost = array('shipment_cost'=>$request->shipment_cost_value);
            $user_history_instance = new UserHistory();
            $user_history_instance->user_id = Auth::id();
            $user_history_instance->user_action = "Shipping cost update";
            $user_history_instance->invoice_id = $invoice_number->code;
            $user_history_instance->change_information = json_encode($shipment_cost);
            $save_user_history = $user_history_instance->save();
            if($save_user_history)
            {
                return response()->json([
                    'status' => 200,
                ]);
            }
           
        }
    }
}

public function user_history_list()
{
    $user_all_history = UserHistory::orderBy('id', 'DESC')->get();
    return view('backend.history.user_history_list', compact('user_all_history'));
}

public function user_history_list_by_date(Request $request)
{
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    
    $user_all_history = UserHistory::where(DB::raw('CAST(created_at as date)'), '<=', $end_date)->where(DB::raw('CAST(created_at as date)'), '>=', $start_date)->get();
    return view('backend.history.user_history_by_date', compact('user_all_history', 'start_date', 'end_date'));
}

//end user history

public function order_cancel_cause(Request $request)
{
   
    $save_order_cancel_reason = Order::where('id', $request->order_id)->update([
        'cancel_reason' => $request->cause_order_cancel
    ]);

    if($save_order_cancel_reason)
    {
        return  response()->json([
            'status' => 200,
        ]);
    }

   
}

public function approved_order(Request $request)
{
    if($request->ajax())
    {
        Order::where('id', $request->order_id)->update([
            'is_approved' => $request->approved_value
        ]);
        
        return response()->json([
            'status' => 200,
            'approved_val' => $request->approved_value
        ]);
    }

}
//start create order money receipt
public function create_money_receipt($invoice_number)
{
    $order_invoice_number = $invoice_number;
    if($order_invoice_number != ''){
        $find_created_money_receipt = MoneyReceipt::where('order_invoice', $order_invoice_number)->first();
       
        if($find_created_money_receipt){
            return view('backend.orders.edit_money_receipt', compact('find_created_money_receipt', 'order_invoice_number'));
        }else{
            return view('backend.orders.create_money_receipt', compact('order_invoice_number'));
        }
        
    }else{
        return Redirect::back();
    }

}

public function store_money_receipt(Request $request)
{
   
    //validation 
    $validatedData = $request->validate([
        'order_invoice' => 'required',
        'total_payment_in_number' => 'required',
        'total_payment_in_word' => 'required',
        'customer_checque_number' => 'required',
        'bill_info' => 'required'
    ]);

    if($validatedData){
       
        $instance_of_money_receipt = new MoneyReceipt();
        $instance_of_money_receipt->order_invoice = $request->order_invoice;
        $instance_of_money_receipt->total_payment_in_number = $request->total_payment_in_number;
        $instance_of_money_receipt->customer_account_name = $request->customer_account_name;
        $instance_of_money_receipt->total_payment_in_word = $request->total_payment_in_word;
        $instance_of_money_receipt->customer_checque_number = $request->customer_checque_number;
        $instance_of_money_receipt->customer_cheque_date = $request->customer_cheque_date;
        $instance_of_money_receipt->bill_info = $request->bill_info;
        $save_money_receipt = $instance_of_money_receipt->save();

        if($save_money_receipt){
            //save money receipt status in order table
            CombinedOrder::where('code',$request->order_invoice)->update([
                'create_money_receipt' => 1
            ]);
            
            return  redirect()->route('orders.index');
        }

    }

}

public function update_money_receipt(Request $request, $invoice_number){
    //validation 
    $validatedData = $request->validate([
        'order_invoice' => 'required',
        'total_payment_in_number' => 'required',
        'total_payment_in_word' => 'required',
        'customer_checque_number' => 'required',
        'bill_info' => 'required'
    ]);

    if($validatedData){
        MoneyReceipt::where('order_invoice',$invoice_number)->update([
            'total_payment_in_number' => $request->total_payment_in_number,
            'customer_account_name' => $request->customer_account_name,
            'total_payment_in_word' => $request->total_payment_in_word,
            'customer_checque_number' => $request->customer_checque_number,
            'customer_cheque_date' => $request->customer_cheque_date,
            'bill_info' => $request->bill_info
        ]);
        return  redirect()->route('orders.index');
    }


}

public function money_receipt_print($invoice_number)
{
    $money_receipt_data = MoneyReceipt::where('order_invoice', $invoice_number)->first();
    $user_name = 'Not Found';
    $combined_order_id = CombinedOrder::select('id')->where('code',$invoice_number)->first();
    $order_created_date = '';
    if(isset($combined_order_id)){
        $get_user_id = Order::select('user_id','created_at')->where('combined_order_id', $combined_order_id->id)->first();
        $order_created_date = $get_user_id->created_at;
        $get_user_name = User::select('name')->where('id', $get_user_id->user_id)->first();
        $user_name = $get_user_name->name;
    }
   
    return view('backend.invoices.money_receipt_print', compact('money_receipt_data', 'user_name','order_created_date'));
}


//end create order money receipt

//start product report by barcode 
public function product_report_by_barocode()
{
    return view('backend.product.report.product_report_by_barcode');
}

public function product_report_by_barocode_match_serial(Request $request)
{
    $product_barcode_id = trim($request->serial_number);
    
    //purchase report
    $_get_purchase_details = PurchaseDetail::whereJsonContains('serial_numbers', strval(trim($request->serial_number)))->first();

    if($_get_purchase_details){
        $products_purchase_data = array('product_id' => $_get_purchase_details['product_id'], "purchase_price" => $_get_purchase_details['purchase_price'], "invoice_numbers" => $_get_purchase_details['invoice_numbers'],"supplier_id" => !empty($_get_purchase_details['supplier_id']) ? $_get_purchase_details['supplier_id']:null, "created_at" => $_get_purchase_details['created_at']);
        
    }else{
        $products_purchase_data="";
        // return redirect()->route('product.report_by_barocde')->with('not_purchase', 'Product Not found!');
    }

    //sell report
    $_get_sell_details = OrderDetail::whereJsonContains('prod_serial_num', strval(trim($request->serial_number)))->first();
        
    if($_get_sell_details){
        $products_sell_data = array('order_id' => $_get_sell_details['order_id'], "product_sell_price" => $_get_sell_details['price'], "product_sell_date" => $_get_sell_details['created_at'],"product_sold_by" => $_get_sell_details['sold_by'], "product_id" => $_get_sell_details['product_id']);
        
    }else{
        $products_sell_data = array('order_id' => null, "product_sell_price" => null, "product_sell_date" => null);
    }
   
    return view('backend.product.report.product_report_by_barcode_submit', compact('products_purchase_data','products_sell_data','product_barcode_id'));
 
}
//end product report by barcode 



}
