<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CRM_Report_Model;
use App\Models\User;


class CrmReport extends Controller
{
    public function index(){
        
        return view("backend.crm_report.index");
    }

    public function crm_add_report(Request $request){

        $crm_report_instance = new CRM_Report_Model();

        $crm_report_instance->user_id = $request->user_id;
        $crm_report_instance->number_of_whatsapp_sent = $request->number_of_whatsapp_message_sent;
        $crm_report_instance->number_of_whatsapp_response = $request->number_of_whatsapp_message_response;
        $crm_report_instance->whatsapp_comment = $request->whatsapp_comment;
        $crm_report_instance->number_of_email_sent = $request->number_of_email_sent;
        $crm_report_instance->number_of_email_response = $request->number_of_email_response;
        $crm_report_instance->email_comment = $request->email_comment;
        $crm_report_instance->number_of_phone_call = $request->number_of_phone_call_sent;
        $crm_report_instance->number_of_phone_call_response = $request->number_of_phone_call_response;
        $crm_report_instance->phone_call_comment = $request->phone_comment;

        $save_instance = $crm_report_instance->save();

        if($save_instance){
            return response()->json(
                [
                    'status' => "Success",
                    'message' => 'Done'
                ]
            );
        }
        else{
            return response()->json(
                [
                    'status' => "Error",
                    'message' => 'something wrong'
                ]
            );
        }

        
    }

    public function crm_report_view(Request $request){

        $crm_report = CRM_Report_Model::all();

        return response()->json(
                [
                    'status' => "success",
                    'data' => $crm_report,
                    'message' => 'Successfully Data retrieved from database'
                ]
            );
    }

    public function crm_report_edit(Request $request){

        $id = $request->id;
        $crm_report = CRM_Report_Model::find($id);
        return response()->json(
            [
                'status' => "success",
                'crm_report' => $crm_report,
                'message' => 'Successfully Data retrieved from database'
            ]
        );
    }

    public function crm_report_update(Request $request){
        $id = $request->crm_report_id;
        $crm_report_instance = CRM_Report_Model::find($id);

        $crm_report_instance->user_id = $request->user_id;
        $crm_report_instance->number_of_whatsapp_sent = $request->number_of_whatsapp_message_sent;
        $crm_report_instance->number_of_whatsapp_response = $request->number_of_whatsapp_message_response;
        $crm_report_instance->whatsapp_comment = $request->whatsapp_comment;
        $crm_report_instance->number_of_email_sent = $request->number_of_email_sent;
        $crm_report_instance->number_of_email_response = $request->number_of_email_response;
        $crm_report_instance->email_comment = $request->email_comment;
        $crm_report_instance->number_of_phone_call = $request->number_of_phone_call_sent;
        $crm_report_instance->number_of_phone_call_response = $request->number_of_phone_call_response;
        $crm_report_instance->phone_call_comment = $request->phone_comment;

        $update_instance = $crm_report_instance->save();
        
        if($update_instance){
            return response()->json(
                [
                    'status' => "Success",
                    'message' => 'Done'
                ]
            );
        }
        else{
            return response()->json(
                [
                    'status' => "Error",
                    'message' => 'something wrong'
                ]
            );
        }

    }

    public function crm_report_print(Request $request){

        $crm_report_list = [];
        foreach($request->id_list_array as $key => $value){
            $crm_report = CRM_Report_Model::find($value);
            array_push($crm_report_list, $crm_report);
        }
    
        $request->session()->put('crm_report_list', $crm_report_list);
    
        return response()->json([
            'crm_report_list' => $crm_report_list,
        ]);
    }
    
    public function crm_report_print_view(Request $request){
    
        $crm_report_list = $request->session()->get('crm_report_list');
        return view("backend.crm_report.printPage", compact('crm_report_list'));
    }

    public function user_name_view(Request $request){
        $user_id = $request->id;
        $user = User::find($user_id);

        if($user !== null){
            return response()->json([
                'name' => $user->name,
            ]);
        }
        else{
            return response()->json([
                'name' => "",
            ]);
        }
        
    }

    public function crm_report_delete(Request $request){
        $crm_report_instance = CRM_Report_Model::find($request->id);
        $delete_instance = $crm_report_instance->delete();

        if($delete_instance){
            return response()->json([
                'status' => "done 101",
                'message' => "Successfully Delete CRM Report"
            ]);
        }
        else{
            return response()->json([
                'status' => "error 101",
                'message' => "Something Wrong"
            ]);
        }


    }

    

}
