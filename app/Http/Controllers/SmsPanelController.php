<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TemporarySmsNumber;
use App\Models\SmsReport;
use DB;

class SmsPanelController extends Controller
{
    public function customer_information_list_sms_view(Request $request)
    {
        
        $receiver_info =   $request->choos_receiver;
        return $this->list_view_based_on_receiver($receiver_info);

    }

    public function list_view_based_on_receiver($receiver_info)
    {
        if($receiver_info === trim('customer')){
            $customer_type = 1;
        }elseif($receiver_info === trim('dealer')){
            $customer_type = 2;
        }elseif($receiver_info === trim('corporate')){
            $customer_type = 3;
        }else{
            return view('backend.sms_panel.choose_sms_receiver');
        }

        $customer_information = User::select('name', 'phone','updated_at')->where('customer_type', $customer_type)->orderBy('updated_at', 'DESC')->get();
        return view('backend.sms_panel.customer_info_sms_list', compact('customer_information', 'receiver_info'));

    }

    public function single_sms_body_view($customer_mobile_number, Request $request)
    {
        //delete session data first
        $request->session()->forget('empty_sms_body');
        $customer_mobile_no = $customer_mobile_number;
        return view('backend.sms_panel.customer_single_sms_body', compact('customer_mobile_no'));
    }

    public function choose_sms_receiver_view()
    {
        return view('backend.sms_panel.choose_sms_receiver');
    }

    public function send_signle_sms(Request $request)
    {
       //delete session first
       $request->session()->forget('empty_sms_body');

        $customer_mobile_no = $request->customer_mobile_no;
        $sms_body = $request->sms_body;
        if(!empty(trim($request->sms_body)))
        {
            try{
                // SMS integration Start
                $token = "7866132738dca110e68e8b7cbc10e238a12c992211";
                $url = "http://api.greenweb.com.bd/api.php";
                $message = $sms_body;
                $data= array(
                'to'=> "$customer_mobile_no",
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

                
                
                if($smsresult){
                    //start sms sending report
                    $sms_report_instance = new SmsReport();
                    $sms_report_instance->phone_number = $customer_mobile_no;
                    if(substr($smsresult,0,2) === 'Ok'){
                        $sms_report_instance->send_status = 'Send';
                    }else{
                    $sms_report_instance->send_status = $smsresult;
                    }
                    $sms_report_instance->sms_body = $sms_body;
                    $sms_report_instance->save();

                    return  redirect()->route('customer_info_list_sms.view')->with('status', "SMS Send Successfully");
                }else{
                    return redirect()->route('customer_info_list_sms.view')->with('failed_sms', $smsresult);
                }
                
            } catch(\Exception $exception){
                return redirect()->route('customer_info_list_sms.view')->with('failed_sms', $exception);
            }
        }else{
            //add session for warning alert
            $request->session()->put('empty_sms_body', 'You can not empty sms body');
            return view('backend.sms_panel.customer_single_sms_body', compact('customer_mobile_no'));
        }

    }


    public function store_temporary_sms_number(Request $request)
    {

        if(isset($request->customer_mobile)){

            $delete_all_previous_send_mail = DB::table('temporary_sms_numbers')->delete();

            if($delete_all_previous_send_mail || $delete_all_previous_send_mail == 0 )
            {
               
                for($i = 0; $i < count($request->customer_mobile); $i++)
                {
                    $temporary_sms_number_instance = new TemporarySmsNumber();
                    $temporary_sms_number_instance->phone_number = $request->customer_mobile[$i];
                    $temporary_sms_number_instance->save();
                }

                return view('backend.sms_panel.customer_multiple_sms_body');
            }
            
        }else{
            return $this->list_view_based_on_receiver($request->receiver_info);
        }

    }

    public function send_multiple_sms_body_view()
    {
        return view('backend.sms_panel.customer_multiple_sms_body');
    }

    public function send_multiple_sms(Request $request)
    {

        if(!empty(trim($request->sms_body)))
        {
            $sms_body = $request->sms_body;
            TemporarySmsNumber::chunk(500, function ($all_numbers) use ($sms_body)
            {
                foreach ($all_numbers as $single_single_numbers)
                {

                    try{
                        // SMS integration Start
                       $token = "7866132738dca110e68e8b7cbc10e238a12c992211";
                       $url = "http://api.greenweb.com.bd/api.php";
                       $message = $sms_body;
                       $data= array(
                       'to'=> "$single_single_numbers->phone_number",
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

                        //start sms sending report
                       if($smsresult){
                           $sms_report_instance = new SmsReport();
                           $sms_report_instance->phone_number = $single_single_numbers->phone_number;
                           if(substr($smsresult,0,2) === 'Ok'){
                                $sms_report_instance->send_status = 'Send';
                           }else{
                            $sms_report_instance->send_status = $smsresult;
                           }
                           $sms_report_instance->sms_body = $sms_body;
                           $sms_report_instance->save();
                       }
                       
                   } catch(\Exception $exception){
                       return redirect()->route('temporary_sms_number.store')->with('failed_sms', $exception);
                   }

                }
            });
            return redirect()->route('choose_sms_receiver.view')->with('status', 'Sucessfully Send All Sms');
        }else{
            return redirect()->route('multiple_sms_body.view')->with('faild_sms', 'You can not empty sms body');
        }

    }

    public function sms_sending_report()
    {
        $sms_sending_data = SmsReport::orderBy('created_at','DESC')->get();
        return view('backend.sms_panel.sms_report', compact('sms_sending_data'));

    }

    public function sms_sending_date_wise_report(Request $request)
    {
       

        $start_date = $request->start_date;
        $end_date = $request->end_date;
    
        $all_date = [];
        $startDate = strtotime($request->start_date);
        $endDate = strtotime($request->end_date);
    
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                                                
            $every_single_date = date('Y-m-d', $currentDate);
            $all_date[] = $every_single_date;
        }
    
        $all_sms_report = SmsReport::whereDate('created_at', '<=', $end_date)->whereDate('created_at', '>=', $start_date)->orderBy('created_at','DESC')->get();
        return view('backend.sms_panel.sms_report_by_date', compact('all_sms_report', 'all_date'));


    }

    public function sms_sending_month_wise_report(Request $request)
    {
       

        $_start_month = $request->start_month;
        $_end_month = $request->end_month;
        $_year = $request->year;
        $all_month = [];
        for($_current_month = $_start_month; $_current_month <= $request->end_month;$_current_month++ )
        {
            $all_month[] = (int) $_current_month;

        }
    
        $all_sms_report_by_month = SmsReport::whereMonth('created_at', '<=', $_end_month)
                                            ->whereMonth('created_at', '>=', $_start_month)
                                            ->whereYear('created_at', $_year)
                                            ->orderBy('created_at','DESC')
                                            ->get();

        return view('backend.sms_panel.sms_report_by_month', compact('all_sms_report_by_month', 'all_month','_year'));

    }


}
