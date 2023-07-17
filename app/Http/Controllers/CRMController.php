<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\State;
use App\Models\Address;
use App\Models\City;
use App\Models\CrmManage;
use App\Models\CrmAssignTo;
use App\Models\CRM_Comments;
use App\Models\CRM_Reminder;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Cart;
use App\Models\Order;
use App\Models\CustomerFeedbackDetails;
use App\Http\Controllers\CategoryController;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\DB;






class CRMController extends Controller
{
    public function customer_marketing_crm_list()
    {
        $all_user_data = User::select('id', 'user_type', 'customer_feedback_status', 'name', 'email', 'phone', 'updated_at')
                        ->where('user_type', 'customer')
                        ->orWhere('user_type', 'dealer')
                        ->orWhere('user_type', 'corporate')
                        ->orderBy('updated_at', 'desc')
                        ->paginate(15);


                        
        $data['brands'] = Brand::all();
        $data['all_state'] = State::all();
        $product_data = CustomerFeedbackDetails::select('product_id')->paginate(15);
        $category_controller_instance = new CategoryController();
        $all_bd_cities = $category_controller_instance->all_bd_cities();  

        return view('backend.crm.customer_marking_list', compact('all_user_data', 'all_bd_cities', 'product_data'), $data);

    }


    public function customer_marketing_crm_add_customer(Request $request)
    {

       
        // Contact Person Json Encode
        $contact_person_details = array();
        $total_contact_person =   count((array)$request->contact_person_name);
        
        for($i=1; $i <= $total_contact_person; $i++  ){
            $contact_person_details['person_name '.$i] = $request->contact_person_name[$i-1];
            $contact_person_details['person_email '.$i] = $request->contact_person_email[$i-1];
            $contact_person_details['person_phone '.$i] = $request->contact_person_phone_number[$i-1];
        }
       $encoded_contact_person_details = json_encode($contact_person_details);

        // Extra Field Json Encode

        $extra_field_details = array();
        $total_extra_field =   count((array)$request->extra_field_name);
        

        for($i=1; $i <= $total_extra_field; $i++  ){
            $extra_field_details['extra_field_name '.$i] = $request->extra_field_name[$i-1];
            $extra_field_details['extra_field_value '.$i] = $request->extra_field_value[$i-1];
        }

       $encoded_extra_field_details = json_encode($extra_field_details);



        //User Table 
        $customer_mobile = "+88".$request->phone;
        $match_customer_number = User::where('phone', $customer_mobile)->first();
        if($match_customer_number == null)
        {
            $user_instance = new User();
            $user_instance->name = $request->name;
            $user_instance->phone = $customer_mobile;
            $user_instance->customer_type = $request->customer_type;
            if($request->customer_type == 1){
                $user_instance->user_type = "customer";
            }elseif($request->customer_type == 2){
                $user_instance->user_type = "dealer";
            }else{
                $user_instance->user_type = "corporate";
            }
            if($request->customer_email != ""){
                $user_instance->email  = $request->customer_email;
            }
            $user_instance->save();
            
            //CRM Manage Table 
            $user_crm_instance = new CrmManage();
            $user_crm_instance->user_id = $user_instance->id;
            $user_crm_instance->registered_by = $request->registered_by;
            $user_crm_instance->contact_person_details = $encoded_contact_person_details;
            $user_crm_instance->reference_by = $request->reference_by;
            $user_crm_instance->bank_information = $request->bank_information;
            $user_crm_instance->trade_licence = $request->trade_licence;
            $user_crm_instance->tin_number = $request->tin_number;
            $user_crm_instance->bin_number = $request->bin_number;
            $user_crm_instance->extra_field_details = $encoded_extra_field_details;
            $user_crm_instance->save();

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

            return redirect()->back()->with('status', 'Customer Created Successfully');
        }
        else
        {
            return redirect()->back()->with('failed', 'Mobile Number Already Given');
        }


    }

    public function customer_marketing_crm_add_status_view($customer_id)
    {
        $client_id = $customer_id;
        return view('backend.crm.customer_status', compact('client_id'));
    }

    public function customer_marketing_crm_add_status_store(Request $request){
        
        $customer_feedback_instance = new CustomerFeedbackDetails();
        $customer_feedback_instance->customer_id = $request->customer_id;
        $customer_feedback_instance->feedback_status = $request->feedback_status;
        $customer_feedback_instance->feedback_details = $request->feedback_details;
        $interested_product = json_encode($request->products);
        $customer_feedback_instance->product_id = $interested_product;
        $save_feedback_instance = $customer_feedback_instance->save();

        if($save_feedback_instance){

            User::where('id', $request->customer_id)->update([
                'customer_feedback_status' => $request->feedback_status
            ]);
            return redirect()->route('customer_marketing_crm.list')->with('status', 'Customer Feedback Added Successfully');
        }
    }

    public function customer_feedback_crm_list(){
        $customer_feedback_instance = CustomerFeedbackDetails::all();
        return view("backend.crm.customer_feedback_list",compact("customer_feedback_instance"));
    }

    public function customer_feedback_crm_list_viwe_each(Request $request){
        
        $customer_details_feedback = CustomerFeedbackDetails::select('product_id','feedback_details')->where('customer_id', $request->customer_feedback_id)->get();
        $customer_details = User::where('id', $request->customer_feedback_id)->get();

        if($customer_details_feedback != null){
            $all_products_id = json_decode($customer_details_feedback[0]->product_id);
        }
        else{
            
        }
        

        $all_products_name = [];

        foreach ($all_products_id as $key => $product_item_id) {
            $product_name = Product::where('id', $product_item_id)->pluck('name')->first();
            if($product_name != null){
                $all_products_name[$product_item_id] = $product_name;
            }
            
        }

         return response()->json([
            "all_products_name" => $all_products_name,
            "customer_details" => $customer_details,
            "customer_details_feedback" => $customer_details_feedback
        ]);

    }

    public function customer_feedback_crm_search_by_date(Request $request){
        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
        $customer_search_feedback_instance = CustomerFeedbackDetails::whereBetween('created_at', [$startDate, $endDate])->get();
        return view("backend.crm.customer_feedback_search_result",compact("customer_search_feedback_instance"));
    }


    public function customer_crm_feedback_edit($customer_feedback_id){
        $customer = CustomerFeedbackDetails::where('customer_id', $customer_feedback_id)->get();
        return view("backend.crm.customer_feedback_contact_list_edit",compact("customer"));
    }

    public function customer_crm_feedback_update(Request $request, $customer_id){

        $products = json_encode($request->products);
        $save_feedback_instance = CustomerFeedbackDetails::where('id', $request->feedback_id)->where('customer_id', $request->customer_id)->update([
            'feedback_status' => $request->feedback_status,
            'feedback_details' => $request->feedback_details,
            'product_id' => $products
        ]);

        if($save_feedback_instance){
            User::where('id', $request->customer_id)->update([
                'customer_feedback_status' => $request->feedback_status
            ]);
        }
        return redirect()->route('customer_marketing_crm.list')->with('status', 'Customer Feedback Status Change Successfully');
    }

    public function customer_product_interest_search(Request $request){

      if($request->search_product_name != null){
      $product = Product::where('name','LIKE','%'.$request->search_product_name.'%')
      ->get();

      return response()->json([
        'match_products' => $product
     ]);

      }
      else{
        return response()->json([
            'message' => "Error"
         ]);
      }

    }

    // CRM Customer Search By Date

    public function customer_crm_search_by_date(Request $request){

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();

        $customer_search_instance = User::join('crm_manages', 'users.id', '=', 'crm_manages.user_id')
            ->whereBetween('crm_manages.created_at', [$startDate, $endDate])
            ->select('users.*', 'crm_manages.*')
            ->get();
        

        return view("backend.customers.customer_search_result",compact("customer_search_instance"));
    }

    // CRM Add Comments 
    public function crm_add_comment(Request $request){
       
        $comment = $request->comment;
        $product = array_values(array_filter($request->product_added_array));
        $json_product = json_encode($product);
        $customer_id = $request->customer_id;
        $today = Carbon::now();
        $added_by = Auth::id();

        $crm_comment_instance = new CRM_Comments();
        $crm_comment_instance->comments = $request->comment;
        $crm_comment_instance->product_ids = $json_product;
        $crm_comment_instance->added_by = Auth::id();
        $crm_comment_instance->crm_id = $request->customer_id;
        $save_crm_comment = $crm_comment_instance->save();



        if($save_crm_comment){
            return response()->json(
                [
                    'status' => "success",
                    'customer_id' => $customer_id,
                    'product' => $json_product,
                    'message' => 'Data inserted successfully'
                ]
            );
        }else{
            return response()->json(
                [
                    'status' => "error",
                    'customer_id' => $customer_id,
                    'message' => 'Something wrong'
                ]
            );
        }

    }
    // CRM View Comments 
    public function crm_view_comment(Request $request){

        $customer_id = $request->customer_id;
    
        $user_comments_data =  DB::table('users')
                            ->join('c_r_m__comments', 'users.id', '=', 'c_r_m__comments.added_by')
                            ->select('c_r_m__comments.*', 'users.name')->where('crm_id', $customer_id)
                            ->get();


        if(count($user_comments_data) > 0){
            return response()->json(
                [
                    'data' => $user_comments_data,
                    'status' => "success"
                ]
            );
        }
        else{
            return response()->json(
                [
                    'data' => $user_comments_data,
                    'status' => "error"
                ]
            );
        }

    


    }

    public function check_customer_id_crm_manage(Request $request){

        $customer_id = $request->customer_id;
        $user_id = $request->user_id;
        $user_count = CrmManage::select('id')->where('user_id', $customer_id)->get()->count();

        if($user_count<1){

            $user_crm_instance = new CrmManage();
            $user_crm_instance->user_id = $customer_id;
            $user_crm_instance->contact_person_details = null;
            $user_crm_instance->reference_by = null;
            $user_crm_instance->bank_information = null;
            $user_crm_instance->trade_licence = null;
            $user_crm_instance->tin_number = null;
            $user_crm_instance->bin_number = null;
            $user_crm_instance->extra_field_details = null;
            $user_crm_instance->save();

            return response()->json(
                [
                    'data' => $customer_id,
                    'message' => "Data Saved"
                ]
            );
        }
        if($user_count == 1){
            return response()->json(
                [
                    'data' => $customer_id,
                    'message' => 'Finaly Data Found'
                ]
            );
        }

    }

    // CRM Product Search

    public function crm_product_search(Request $request){
        $name_length = $request->name_length;
        if(!empty($request->product_name)){
            $product = Product::where('name','LIKE','%'.$request->product_name.'%')->limit(7)->get();

            return response()->json(
                [
                    'data' => $product,
                    'name_length' => $name_length,
                    'status' => "success"
                ]
            );
        }else{
            return response()->json(
                [
                    'status' => "error101"
                ]
            );
        }


    }

    // CRM Reminder
    public function crm_add_reminder(Request $request){

        $user_crm_instance = new CRM_Reminder();
        $user_crm_instance->note = $request->reminder_text;
        $user_crm_instance->date = date('Y-m-d', strtotime( $request->date));
        $user_crm_instance->time = date('H:i', strtotime( $request->date));
        $user_crm_instance->assign_by = $request->user_id;
        $user_crm_instance->created_by = $request->user_id;
        $user_crm_instance->customer_id = $request->customer_id;
        $user_crm_instance->status = $request->reminder_status;
        $user_crm_instance->assign_to = $request->assign_to_id;
        $user_crm_instance->interested_product = json_encode ($request->product_added_array);
        $save_crm_user = $user_crm_instance->save();


        if($save_crm_user){
            return response()->json(
                [
                    'customer_id' => $request->customer_id,
                    'status' => "success",
                    'message' => 'Data inserted successfully'
                ]
            );
        }else{
            return response()->json(
                [
                    'customer_id' => $request->customer_id,
                    'status' => "error",
                    'message' => 'Something wrong'
                ]
            );
        }
    }

    // Getting Product Name Using Ajax
    public function crm_getting_product_name_modal(Request $request){

        $customer_id = $request->customer_id;
        $customer_name = User::where('id', $customer_id)->pluck('name');

        $product_ids = $request->product_ids;
        $product_count = count($product_ids);

        $product_name_list = array();

        for ($i = 0; $i < $product_count; $i++) {

            if($product_ids[$i] != null){
                $product_name_list[] = Product::where('id', $product_ids[$i])->first()->name;
            }
                

        }

        return response()->json(
            [
                'data' => $product_name_list,
                'customer_name' => $customer_name
            ]
        );



    }

    // User Name Search 
    public function crm_user_search(Request $request){
        $name_length = $request->name_length;
        if(!empty($request->user_name)){
            $user = User::where('name','LIKE','%'.$request->user_name.'%')->limit(7)->get();

            return response()->json(
                [
                    'data' => $user,
                    'name_length' => $name_length,
                    'status' => "success"
                ]
            );
        }else{
            return response()->json(
                [
                    'status' => "error101"
                ]
            );
        }

    }

    public function marketing_followup_commented_clients_view(Request $request){
        $CRM_Comments = CRM_Comments::all();
        return view("backend.marketing_followup.commented_clients_view",compact("CRM_Comments"));
    }

    public function marketing_followup_comments_view(Request $request){
        $CRM_Comments = CRM_Comments::all();
        return view("backend.marketing_followup.comments_view",compact("CRM_Comments"));
    }

    public function marketing_followup_reminders_view(Request $request){
        $CRM_Reminders = CRM_Reminder::all();
        return view("backend.marketing_followup.reminders_view",compact("CRM_Reminders"));
    }

    public function getting_reminder_data(Request $request){

        $CRM_Reminder = CRM_Reminder::where('id',  $request->id)->get();
        $product_ids = CRM_Reminder::select('interested_product')->where('id', $request->id)->get();

        $product_ids = $product_ids->toArray();
        $product_ids = $product_ids[0];
        $interestedProducts = $product_ids['interested_product'];
        $interestedProducts = json_decode($interestedProducts, true);
        $product_count =  count($interestedProducts);
        $product_name_list = array();

        for ($i = 0; $i < $product_count; $i++) {

            if($interestedProducts[$i] != null){
                $product_name_list[] = Product::where('id', $interestedProducts[$i])->first()->name;
            }

        }

        $products = array_combine($interestedProducts, $product_name_list);

        $assign_to = CRM_Reminder::select('assign_to')->where('id', $request->id)->get();
        $assign_name = User::where('id', $assign_to[0]->assign_to)->first()->name;
        $assign_to = $assign_to[0]->assign_to;

        return response()->json(
            [
                'CRM_Reminder' => $CRM_Reminder,
                'products' => $products,
                'assign_to' => $assign_to,
                'assign_name' => $assign_name,
                'status' => "success",
            ]
        );

    }

    public function update_reminder(Request $request){

        $assign_n =  $request->assign_id;

        $crm_reminder_instance = CRM_Reminder::find($request->reminder_id);

        $crm_reminder_instance->id = $request->reminder_id;
        $crm_reminder_instance->note = $request->reminder_note;
        $crm_reminder_instance->customer_id = $request->customer_id;
        $crm_reminder_instance->assign_by = $request->user_id;
        $crm_reminder_instance->interested_product = $request->product_added_array;
        $crm_reminder_instance->assign_to = $request->assign_id;
        $crm_reminder_instance->status = $request->status;

        $crm_reminder_save = $crm_reminder_instance->save();

        if($crm_reminder_save){
            return response()->json(
                [
                    'message' => 'Successfully Updated',
                    'assign_id' => $assign_n,
                    'status' => 'success'
                ]
            );
        }
        else{
            return response()->json(
                [
                    'message' => 'Something Wrong',
                    'status' => 'error 101'
                ]
            );
        }


    }

    public function getting_data_view_modal(Request $request){

        $data = CRM_Comments::where('id', $request->id)->get();
        $added_by = CRM_Comments::where('id', $request->id)->pluck('added_by');
        $customer_name = CRM_Comments::where('id', $request->id)->pluck('crm_id');
        $added_by_user_name = getUserName($added_by);
        $customer_name = getUserName($customer_name);
        
        return response()->json(
            [
                'data' => $data,
                'added_by_user_name' => $added_by_user_name,
                'customer_name' => $customer_name,
                'status' => "success"
            ]
        );

    }

    public function delete_comments(Request $request){

        $user = CRM_Comments::find($request->id);
        $delete_user = $user->delete();

        if($delete_user){
            return response()->json(
                [
                    'status' => "success"
                ]
            );
        }
        else{
            return response()->json(
                [
                    'status' => "error"
                ]
            );
        }

    }

    public function getting_comments_view(Request $request){

        $data = CRM_Comments::where('id', $request->id)->get();
        $product_id_list = CRM_Comments::where('id', $request->id)->pluck('product_ids');
        $product_id_list = json_decode($product_id_list);
        $product_data = $product_id_list[0];

        $product_data= json_decode($product_data);
        $product_name_array= array();
        
        foreach ($product_data as $key => $value) {
             $product_name= getProductName($value);
            array_push($product_name_array, array(
                $value => $product_name
              ));
        }

        return response()->json(
            [
                'data' => $data,
                'product_name_array' => $product_name_array,
                'product_data' => $product_data,
                'status' => "success"
            ]
        );
    }

    public function update_comments(Request $request){


        $comment_instance = CRM_Comments::find($request->comment_id);
        $product_ids = json_encode($request->product_added_array);

        $comment_instance->id = $request->comment_id;
        $comment_instance->comments = $request->comment;
        $comment_instance->product_ids = $product_ids;
        $comment_instance_save = $comment_instance->save();

        if($comment_instance_save){

            return response()->json(
                [
                    'status' => "success"
                ]
            );
        }
        else{

            return response()->json(
                [
                    'status' => "error"
                ]
            );
        }

    }



        // CRM Add Comments from Modal
        public function add_comment_from_modal(Request $request){
       

            $product = $request->product_added_array;
            $json_product = json_encode($product);

            $customer_id = $request->customer_id;
            $today = Carbon::now();

            $crm_comment_instance = new CRM_Comments();
            $crm_comment_instance->comments = $request->comment_text;
            $crm_comment_instance->product_ids = $json_product;
            $crm_comment_instance->added_by = $request->user_id;
            $crm_comment_instance->crm_id = $request->customer_id;
            $save_crm_comment = $crm_comment_instance->save();


    
    
            if($save_crm_comment){
                return response()->json(
                    [
                        'status' => "success",
                        'message' => 'Data inserted successfully'
                    ]
                );
            }else{
                return response()->json(
                    [
                        'status' => "error",
                        'message' => 'Something wrong'
                    ]
                );
            }
    
        }

        public function customer_profile_view(Request $request){
            
            $cart_data = Cart::where('user_id', $request->id)->get();
            $user_data = User::where('id', $request->id)->get();
            $whishlist_data = Wishlist::where('user_id', $request->id)->get();
            $review_data = Review::where('user_id', $request->id)->get();
            $order_data = Order::where('user_id', $request->id)->get();
            $user = User::where('id', $request->id)->first();
            $followed_shop_data = $user->followed_shops;
   
            return view("backend.marketing_followup.customer_profile_view", compact('cart_data','user_data','whishlist_data','review_data','followed_shop_data','order_data'));
        }

        public function delete_reminder(Request $request){
            $reminder_id = $request->reminder_id;
            $reminder_instance = CRM_Reminder::find($reminder_id);
            if (!$reminder_instance) {
                return response()->json(
                    [
                        'status' => "error 201",
                        'message' => 'Item not found.'
                    ]
                );
            }
            $reminder_delete_instance = $reminder_instance->delete();

            if($reminder_delete_instance){
                return response()->json(
                    [
                        'status' => "success",
                        'reminder_id' => $reminder_id,
                        'message' => 'Data inserted successfully'
                    ]
                );
            }
            else{
                return response()->json(
                    [
                        'status' => "error 202",
                        'message' => 'Something Wrong'
                    ]
                );
            }
        }

        public function view_reminder(Request $request){
            $reminder_id = $request->reminder_id;
            $reminder_data = CRM_Reminder::where('id', $reminder_id)->get();
            $product_ids = CRM_Reminder::select('interested_product')->where('id', $reminder_id)->get();

            $product_ids = $product_ids->toArray();
            $product_ids = $product_ids[0];
            $interestedProducts = $product_ids['interested_product'];
            $interestedProducts = json_decode($interestedProducts, true);
            $product_count =  count($interestedProducts);
            $product_name_list = array();

            for ($i = 0; $i < $product_count; $i++) {
    
                if($interestedProducts[$i] != null){
                    $product_name_list[] = Product::where('id', $interestedProducts[$i])->first()->name;
                }
    
            }

            $products = array_combine($interestedProducts, $product_name_list);

            $assign_to = CRM_Reminder::select('assign_to')->where('id', $reminder_id)->get();
            $assign_name = User::where('id', $assign_to[0]->assign_to)->first()->name;
            $assign_to = $assign_to[0]->assign_to;
    

            return response()->json(
                [
                    'status' => "Success",
                    'reminder_data' => $reminder_data,
                    'products' => $products,
                    'assign_to' => $assign_to,
                    'assign_name' => $assign_name,
                    'message' => 'Successfully'
                ]
            );
        }

        public function crm_getting_comments_modal(Request $request){
            $comment_id = $request->comment_id;

            $data = CRM_Comments::where('id', $request->comment_id)->get();
            $product_id_list = CRM_Comments::where('id', $request->comment_id)->pluck('product_ids');
            $product_id_list = json_decode($product_id_list);
            $product_data = $product_id_list[0];
    
            $product_data= json_decode($product_data);
            $product_name_array= array();
            
            foreach ($product_data as $key => $value) {
                 $product_name= getProductName($value);
                array_push($product_name_array, array(
                    $value => $product_name
                  ));
            }
    
            return response()->json(
                [
                    'data' => $data,
                    'product_name_array' => $product_name_array,
                    'product_data' => $product_data,
                    'comment_id' => $comment_id,
                    'status' => "success"
                ]
            );

        }


}
