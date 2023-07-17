<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerLogDetails;
use App\Models\CustomerFeedbackDetails;
use App\Models\CRM_Proposal;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
class CustomerLogController extends Controller
{
    public function customer_log_details_view(){
        
        return view('backend.crm.customerLogAdd');
    }

    public function customer_log_details_add(Request $request){
        
        $customer = new CustomerLogDetails;
        $customer -> title = $request -> title;
        $customer -> name = $request -> name;
        $customer -> status = $request -> status;
        $customer -> comment = $request -> comment;

        $customer->save();
        return redirect()->route("customer_log_details_manage.view");

    }
    
    public function customer_log_details_manage_view(){

        $customers = CustomerLogDetails::all();
       return view("backend.crm.customerLogManagement",compact("customers"));
    }

    public function customer_log_details_manage_delete($id){

        $customer = CustomerLogDetails::find($id);

        $customer->delete();
        return redirect()->route("customer_log_details_manage.view");
    }

    public function customer_log_details_manage_edit($id){

        $customer = CustomerLogDetails::find($id);
        return view("backend.crm.customerLogEdit",compact("customer"));
    }

    public function customer_log_details_manage_update(Request $request, $id){
        
        $customer = CustomerLogDetails::find($id);
        $customer->title = $request->title;
        $customer->name = $request->name;
        $customer->status = $request->status;
        $customer->comment = $request->comment;
        $customer->update();
        return redirect()->route("customer_log_details_manage.view")->withSuccess('Successfully Record Submitted!');
        
    }

    public function customer_log_details_manage_customer_changeStatus(Request $request)
    {

        $customer = CustomerLogDetails::find( $request->customer_id);
        if($customer->status == 1){
            $customer->status = 2;
            $status = 2;
        }
        else{
            $customer->status = 1;
            $status = 1;
        }
    
        $customer->update();
        return response()->json([
            'status' => $status,
            'serial_number' => $request->customer_id,
            
        ]);
    }

    public function customer_log_details_crm_proposal(){

        return view("backend.crm.customerProposal");
    }

    public function customer_log_details_crm_proposal_view(){

        $allData = CRM_Proposal::all();

        if($allData){
         return response()->json([
             "status" => 'success',
             "alldata" => $allData
         ]);
        }
        else{
         return response()->json([
             "status" => '404',
         ]);
        }
    }
    public function customer_log_details_crm_proposal_store(Request $request){
        dd($request->client_id);
        if(CustomerFeedbackDetails::find($request->client_id)){
            return $request->client_id;
        }
        else{
            return "there is no client id";
        }
        
        $customer = CustomerFeedbackDetails::find($request->client_id);
        return $customer;
        $customer->title = $request->title;
        $customer->name = $request->name;
        $customer->status = $request->status;
        $customer->comment = $request->comment;
        $customer->update();

        $product_details = [];
        for ($i=0; $i <count($request->product_name) ; $i++) { 
            $product_details[$i] =  [
                'product_name' => $request->product_name[$i],
                'product_quantity' => $request->product_quantity[$i],
                'product_price' => $request->product_price[$i]
            ];
        }

        $product_details = (json_encode($product_details));
        
        $proposal_instance = new CRM_Proposal;
        $proposal_instance->client_title = $request->client_title;
        $proposal_instance->customer_id = $request->client_id;
        $proposal_instance->proposal_subject = $request->proposal_subject;
        $proposal_instance->product_details = $product_details;
        $proposal_instance->proposed_expired_date = $request->proposed_expired_date;
        $proposal_instance->proposed_status = $request->proposed_status;
        $save_proposal = $proposal_instance->save();
        return redirect()->back();


        
    }

    public function customer_log_details_crm_proposal_changestatus(Request $request){

        $proposal = CRM_Proposal::find($request->id);
        $data_status = $proposal->proposed_status = $request->proposed_status;
        $proposal->id = $request->id;
        $data = $proposal->save();

        if($data){
            return response()->json([
                "result" => 'success',
                "status" => $data_status
            ]);
        }
        else{
            return response()->json([
                "result" => "error206"
            ]);
        }
    }
    public function customer_log_details_crm_proposal_delete(Request $request){

        $delete_client = CRM_Proposal::where('id','=',$request->id)->first();

        $delete_client->delete();
  
        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }

    public function customer_log_details_crm_customer_view($client_id){
        $client_information = CRM_Proposal::where('id','=',$client_id)->first();
        return view("backend.crm.customerProfile",compact("client_information"));

    }
    public function customer_log_details_crm_product_search(Request $request){

        $product = Product::select("name", "id")
        ->where('name', 'LIKE', '%'. $request->get('proposed_product_name'). '%')
        ->paginate(10);
        return response()->json([
            'success' => 'Successfully!',
            'product' => $product
        ]);

    }
    
}
