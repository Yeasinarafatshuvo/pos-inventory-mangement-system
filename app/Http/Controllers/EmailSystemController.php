<?php

namespace App\Http\Controllers;
use App\Models\EmailPanel;
use App\Models\User;
use App\Models\EmailTempPanel;
use Illuminate\Http\Request;
use Excel;
use App\Imports\CustomerEmailImport;
use Illuminate\Support\Facades\DB;
use MailchimpTransactional;
use Illuminate\Support\Facades\Hash;



class EmailSystemController extends Controller
{
    public function email_system_view(){
        return view('backend.email_panel.email_import_view');
    }

    public function user_email_info_store(Request $request){

        $customer_all_info = EmailPanel::all();
        //dd($customer_all_info );
        $delete_customer_previous_info = DB::table('email_panels')->delete();

        if($delete_customer_previous_info || count($customer_all_info) == 0){
            try{
                //import excell data to database
                Excel::import(new CustomerEmailImport, $request->file);

                //export data from db new user info and check unique data and again import it user table.
                EmailPanel::chunk(100, function ($all_customers) {
                    foreach ($all_customers as $single_customers_data) {

                        if(trim($single_customers_data->customer_email) !== "" && trim($single_customers_data->customer_phone) !== "")
                        {

                            $check_duplicate_data_users_data_table_from_new_customer_info = User::where('email', '=', trim($single_customers_data->customer_email))->where('phone', '=', trim($single_customers_data->customer_phone))->get();
                            if(count($check_duplicate_data_users_data_table_from_new_customer_info) == 0){
                              
                                // create new user for unique information
                                $user_instance = new User();
                                $user_instance->name = trim($single_customers_data->customer_name) == null ? trim($single_customers_data->customer_phone):trim($single_customers_data->customer_name);
                                $user_instance->email = trim($single_customers_data->customer_email);
                                $user_instance->phone = trim($single_customers_data->customer_phone);
                                $user_instance->password = Hash::make(trim($single_customers_data->customer_phone));
                                $user_instance->customer_type = 3;
                                $save_new_user = $user_instance->save();

                                //save contact at mailchimp panel
                                if($save_new_user){

                                    $mailchimp = new \MailchimpMarketing\ApiClient();
    
                                    $mailchimp->setConfig([
                                        'apiKey' => config('services.mailchimp.key'),
                                        'server' => 'us14'
                                    ]);

                                     $response = $mailchimp->lists->addListMember('4e880b6189',[
                                        "email_address" => $single_customers_data->customer_email,
                                        "status" => "subscribed",
                                    ]);

                                }

                            }
                        }
                    }
                });


            } catch(\Exception $exception){
                return view('backend.employee_manage.exception_employee');
            }

        }
        return redirect('/admin/email_system/user_email_info_list')->with('status', 'Customer Information  Updated Successfully');
       
    }


    public function user_email_info_list()
    {
        $customer_information = User::select('name', 'email','updated_at')->where('customer_type', 3)->orderBy('updated_at', 'DESC')->get();
        return view('backend.email_panel.customer_email_list', compact('customer_information'));
    }

    public function send_single_mail_write_body($customer_email)
    {
        $customer_email = $customer_email;
        return view('backend.email_panel.email_body', compact('customer_email'));

    }

    public function send_single_mail(Request $request)
    {
        
        if(!empty($request->customer_mail) && !empty($request->email_subject) && !empty($request->email_body))
        {
            $message = [
                "from_email" => "marketing@maakview.com",
                "subject" => $request->email_subject,
                "text" => $request->email_body,
                "to" => [
                    [
                        "email" => trim($request->customer_mail),
                        "type" => "to"
                    ]
                ]
            ];
           
            try {
                $mailchimp = new MailchimpTransactional\ApiClient();
                $mailchimp->setApiKey('YtNs0AZhtIfLCwPUvug8wA');
                $response = $mailchimp->messages->send(["message" => $message]);
                return redirect('/admin/email_system/user_email_info_list')->with('status', 'Mail send  Successfully');
            } catch (Error $e) {
                return redirect('/admin/email_system/user_email_info_list')->with('faild_mail', $e->getMessage());
            }

        }else{
            return redirect('/admin/email_system/user_email_info_list')->with('faild_mail', 'Please, fillup appropriate subject and body');
        }
     
    }


    public function save_temporary_email(Request $request)
    {
        if(isset($request->customer_email)){
            $all_previous_send_mail = EmailTempPanel::all();
            $delete_all_previous_send_mail = DB::table('email_temp_panels')->delete();
            if($delete_all_previous_send_mail || count($delete_all_previous_send_mail) == 0 )
            {
                for($i = 0; $i < count($request->customer_email); $i++)
                {
                    $temporary_email_instance = new EmailTempPanel();
                    $temporary_email_instance->selected_customer_email = $request->customer_email[$i];
                    $temporary_email_instance->save();
                }

                return redirect('/admin/email_system/choose_mail_type_body');
            }
            
        }else{
            return redirect('/admin/email_system/user_email_info_list')->with('faild_mail', 'You are not selected any mail');
        }

       
    }

    public function choose_email_type_body()
    {
        return view('backend.email_panel.choose_email_type');
    }

  

    public function multiple_mail_write_body()
    {
        return view('backend.email_panel.multiple_email_body');
    }

    public function send_multiple_mail(Request $request){
        if(!empty($request->email_subject) && !empty($request->email_body))
        {
            
            $email_subject = $request->email_subject;
            $email_body = $request->email_body;
            EmailTempPanel::chunk(100, function ($all_customers) use ($email_subject,$email_body)
            {
               
                foreach ($all_customers as $single_customer)
                {
                    
                    $message = [
                        "from_email" => "marketing@maakview.com",
                        "subject" => $email_subject,
                        "text" => $email_body,
                        "to" => [
                            [
                                "email" => $single_customer->selected_customer_email,
                                "type" => "to"
                            ]
                        ]
                    ];

                    try {
                        $mailchimp = new MailchimpTransactional\ApiClient();
                        $mailchimp->setApiKey('YtNs0AZhtIfLCwPUvug8wA');
                        $response = $mailchimp->messages->send(["message" => $message]);
                    } catch (Error $e) {
                        return redirect('/admin/email_system/user_email_info_list')->with('faild_mail', $e->getMessage());
                    }
                    
                }
            });

            return redirect('/admin/email_system/user_email_info_list')->with('status', 'Mail send  Successfully');

        }else{
            return redirect('/admin/email_system/user_email_info_list')->with('faild_mail', 'You can not empty email subject or body');
        }
        
    }

    public function template_single_mail_body($customer_email)
    {
        $customer_email = $customer_email;
        return view('backend.email_panel.template_single_body', compact('customer_email'));
    }

    public function send_template_single_mail_body(Request $request)
    {
        
        if(!empty($request->customer_mail) && !empty($request->email_subject) && !empty($request->template_name))
        {
            $mailchimp = new MailchimpTransactional\ApiClient();
            $mailchimp->setApiKey('YtNs0AZhtIfLCwPUvug8wA');
    
            $message = [
                "from_email" => "marketing@maakview.com",
                "subject" => $request->email_subject,
                "to" => [
                    [
                        "email" => trim($request->customer_mail),
                        "type" => "to"
                    ]
                ]
            ];

            $response = $mailchimp->messages->sendTemplate([
                "template_name" => trim($request->template_name),
                "template_content" => [
                    [
                        "name" => 'template',
                        "content" => 'write content here'
                    ]
                ],
                "message" => $message,
            ]);

            if($response[0]->status == 'sent'){
                return redirect('/admin/email_system/user_email_info_list')->with('status', 'Template Mail send  Successfully');
            }elseif($response[0]->status == 'rejected'){
                return redirect('/admin/email_system/user_email_info_list')->with('faild_mail', $response[0]->reject_reason);
            }

        }else{
            return redirect('/admin/email_system/user_email_info_list')->with('faild_mail', 'Please, fillup appropriate subject and body');
        }

    }

    public function multiple_template_mail_write_body(){
        return view('backend.email_panel.multiple_template_email_body');
    }

    public function send_multiple_template_mail(Request $request)
    {
        if(!empty($request->email_subject) && !empty($request->template_name))
        {
           
            
            $email_subject = $request->email_subject;
            $template_name = $request->template_name;
            EmailTempPanel::chunk(100, function ($all_customers) use ($email_subject,$template_name)
            {
               
                foreach ($all_customers as $single_customer)
                {

                    $mailchimp = new MailchimpTransactional\ApiClient();
                    $mailchimp->setApiKey('YtNs0AZhtIfLCwPUvug8wA');
                    
                    $message = [
                        "from_email" => "marketing@maakview.com",
                        "subject" => $email_subject,
                        "to" => [
                            [
                                "email" => trim($single_customer->selected_customer_email),
                                "type" => "to"
                            ]
                        ]
                    ];
        
                    $response = $mailchimp->messages->sendTemplate([
                        "template_name" => trim($template_name),
                        "template_content" => [
                            [
                                "name" => 'template',
                                "content" => 'write content here'
                            ]
                        ],
                        "message" => $message,
                    ]);
                    
                    if($response[0]->status == 'rejected'){
                        session()->put('rejected_mail', $response[0]->reject_reason);
                    }else{
                        session()->forget('rejected_mail');
                    }
                    
                }
            });
            if(session()->get('rejected_mail'))
            {
                return redirect('/admin/email_system/user_email_info_list')->with('faild_mail', session()->get('rejected_mail'));
            }else{
                return redirect('/admin/email_system/user_email_info_list')->with('status', 'Template Mail send  Successfully');
            }

        }else{
            return redirect('/admin/email_system/user_email_info_list')->with('faild_mail', 'You can not empty email subject or body');
        }
    }











    
}
