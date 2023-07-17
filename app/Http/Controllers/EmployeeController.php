<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use Excel;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\AttendanceTest;
use App\Models\AutomateAttendance;
use App\Models\User;
use App\Models\Payslip;
use App\Models\State;
use App\Http\Controllers\CategoryController;


date_default_timezone_set('Asia/Dhaka');


class EmployeeController extends Controller
{
    public function attendence_report_generate_view()
    {
        return view('backend.employee_manage.generate_attendance_view');
    }

    public function attendence_report_generate_excell_store(Request $request)
    {
        $employee_attendance_data = Employee::all();
        $delete_previous_employee_attendance =  DB::table('employees')->delete();
        if($delete_previous_employee_attendance ||  count($employee_attendance_data) == 0)
        {
            try{
                Excel::import(new EmployeeImport, $request->file);
            } catch(\Exception $exception){
                return view('backend.employee_manage.exception_employee');
            }
           

        }
        return redirect('/admin/employee_manage/attendance_generate_report');
    }
   

    public function attendence_report_generate()
    {
       
        $employee_attendance_data = Employee::all();
        return view('backend.employee_manage.employee_attendance_show', compact('employee_attendance_data'));
    }

    public function store_attendence_report_info(Request $request)
    {
        $total_date_count = count($request->date_attendance);
        
        $attendance_id = rand(1,50).date("ymdhis");

        //check attendance id is available or not
        $find_method = EmployeeAttendance::where('attendance_id', $attendance_id)->first();
        if($find_method !== null){
            $attendance_id = rand(1,50).date("ymdhis");
        }
     

        for ($i=0; $i <$total_date_count ; $i++) {
            $employee_attendence = new EmployeeAttendance();
        
            $employee_attendence->date_attendance = $request->date_attendance[$i];
            $employee_attendence->attendance_id = $attendance_id;
            $employee_attendence->attendance_in_time = $request->attendance_in_time[$i];
            $employee_attendence->attendance_out_time = $request->attendance_out_time[$i];
            $employee_attendence->approve_dellay = $request->approve_Delay[$i];
            $employee_attendence->un_approve_dellay = $request->un_approve_Delay[$i];
            $employee_attendence->un_approve_leave = $request->un_approve_leave[$i];
            $employee_attendence->approve_leave = $request->approve_leave[$i];
            
            if($i == 0)
            {
                $employee_attendence->comments_prepared = $request->comments_prepared;
                $employee_attendence->comments_admin = $request->comments_admin;
                $employee_attendence->comments_ceo = $request->comments_ceo;
                $employee_attendence->total_approve_dellay = $request->total_approve_Delay;
                $employee_attendence->total_un_approve_dellay = $request->total_un_approve_Delay;
                $employee_attendence->total_un_approve_leave = $request->total_un_approve_leave;
                $employee_attendence->total_approve_leave = $request->total_approve_leave;
                $employee_attendence->name = $request->name;
                $employee_attendence->salary = $request->salary;
                $employee_attendence->deduction_amount = $request->deduction_amount;
                $employee_attendence->final_payamount = $request->final_payamount;
                $employee_attendence->total_absence = $request->total_absence_val;
            }
            $save_attendance = $employee_attendence->save(); 
        }
        return redirect('/admin/employee_manage/attendance_list')->with('status', 'Employee attendance import Successfully');

    }

    public function employee_attendence_list()
    {
        $employee_attendance_data = EmployeeAttendance::select('attendance_id', 'name', 'updated_at')->groupBy('attendance_id')->orderBy('updated_at', 'DESC')->get();
        return view('backend.employee_manage.employee_attendance_list', compact('employee_attendance_data'));
    }


    public function employee_attendence_print(Request $request, $attendance_id)
    {
        $employee_attendance_data = EmployeeAttendance::where('attendance_id', $attendance_id)->get();
        return view('backend.employee_manage.employee_attendance_print', compact('employee_attendance_data'));
    }

    public function employee_attendence_edit($attendance_id)
    {
        $employee_attendance_data = EmployeeAttendance::where('attendance_id', $attendance_id)->get();
        return view('backend.employee_manage.employee_attendance_edit', compact('employee_attendance_data'));
    }

    public function employee_attendence_update(Request $request, $attendance_id)
    {
       
        $total_date_count = count($request->date_attendance);

        for ($i=0; $i <$total_date_count ; $i++)
        {
            $employee_attendence = EmployeeAttendance::find($request->primary_id[$i]);
        
            $employee_attendence->date_attendance = $request->date_attendance[$i];
            $employee_attendence->attendance_id = $attendance_id;
            $employee_attendence->attendance_in_time = $request->attendance_in_time[$i];
            $employee_attendence->attendance_out_time = $request->attendance_out_time[$i];
            $employee_attendence->approve_dellay = $request->approve_Delay[$i];
            $employee_attendence->un_approve_dellay = $request->un_approve_Delay[$i];
            $employee_attendence->un_approve_leave = $request->un_approve_leave[$i];
            $employee_attendence->approve_leave = $request->approve_leave[$i];

            if($i == 0)
            {
                $employee_attendence->comments_prepared = $request->comments_prepared;
                $employee_attendence->comments_admin = $request->comments_admin;
                $employee_attendence->comments_ceo = $request->comments_ceo;
                $employee_attendence->total_approve_dellay = $request->total_approve_Delay;
                $employee_attendence->total_un_approve_dellay = $request->total_un_approve_Delay;
                $employee_attendence->total_un_approve_leave = $request->total_un_approve_leave;
                $employee_attendence->total_approve_leave = $request->total_approve_leave;
                $employee_attendence->name = $request->name;
                $employee_attendence->salary = $request->salary;
                $employee_attendence->deduction_amount = $request->deduction_amount;
                $employee_attendence->final_payamount = $request->final_payamount;
                $employee_attendence->total_absence = $request->total_absence_val;
            }
            $save_attendance = $employee_attendence->save(); 
        }
        if($save_attendance){
            return redirect('/admin/employee_manage/attendance_list')->with('status', 'Employee attendance Updated Successfully');
        }

    }

    public function employee_attendence_delete($attendance_id)
    {
        $delete_employee_attendance = EmployeeAttendance::where('attendance_id' , $attendance_id)->delete();
        if($delete_employee_attendance){
            return redirect('/admin/employee_manage/attendance_list');
        }
    }

    public function automate_attendance()
    {
        $get_all_employee = User::where('customer_type', 4)->get();
        $category_instance = new CategoryController();
        $data['all_state'] = State::all();
        $all_bd_cities = $category_instance->all_bd_cities();
     
        return view('backend.employee_manage.employee_attendance_automate', compact('get_all_employee', 'all_bd_cities'), $data);
    }

    public function automate_attendance_generate(Request $request)
    {
       
        $employee_attendance_id = $request->employee_attendance_id;
        $attendance_month = $request->attendance_month;
        $attendance_year =  $request->attendance_year;


        $attendance_values = json_decode(Storage::get('data.json'));
        
   
        $single_user_values = array();
        $inc = 0;
        foreach($attendance_values as $key => $item){
         
          
             if($item->deviceUserId === $employee_attendance_id && (new \DateTime($item->recordTime))->setTimezone(new \DateTimeZone('Asia/Dhaka'))->format('m') == $attendance_month && (new \DateTime($item->recordTime))->setTimezone(new \DateTimeZone('Asia/Dhaka'))->format('Y') ==  $attendance_year){
                $inc++;
                $single_user_values[$inc]['user_id'] =  $item->deviceUserId;
                $single_user_values[$inc]['recordDate'] =(new \DateTime($item->recordTime))->setTimezone(new \DateTimeZone('Asia/Dhaka'))->format('Y-m-d ');
                $single_user_values[$inc]['recordHour'] = (new \DateTime($item->recordTime . "-0 hour"))->setTimezone(new \DateTimeZone('Asia/Dhaka'))->format('h:i');
            }
           
        }

       
    
        
        // Initialize an empty array to hold the filtered data
        $filteredAttendance = [];

        $groupedByDate = [];
        foreach ($single_user_values as $record) {
            $groupedByDate[$record['recordDate']][] = $record;
        }

        $filteredAttendance = $this->segregate_intime_outtime($groupedByDate);
        
        // Create an array of all dates in given month
        $all_month_dates = array();
        for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN,$attendance_month,$attendance_year); $i++) {
            $all_month_dates[] = date('Y-m-d', strtotime("2023-$attendance_month-$i"));
        }
        
        // Loop through the existing array and add empty arrays for missing dates

        $month_wise_grouping_attendance = array();

        // loop through the data array
        foreach ($filteredAttendance as $record) {
            $date = trim($record['recordDate']);
            $month_wise_grouping_attendance[$date] = $record;
            
        }
        
       
        foreach ($all_month_dates as $date_item) {
            if (!array_key_exists($date_item, $month_wise_grouping_attendance)) {
                $month_wise_grouping_attendance[$date_item] =  [
                        "user_id" => $employee_attendance_id,
                        "recordDate" => $date_item,
                        "firstRecordHour" =>NULL,
                        "lastRecordHour" => NULL
                    ];
            }
        }

        



        
        //sequentially date wise import data to db
        foreach ($all_month_dates as $key => $date_check) {
            $user_id = (int) $month_wise_grouping_attendance[$date_check]['user_id'];
            $record_date =  trim($month_wise_grouping_attendance[$date_check]['recordDate']);
            
            $check_import_data = AutomateAttendance::where('user_id', $user_id)->whereDate('date_attendance', $record_date)->first();
                 
            if(!$check_import_data){
                $attendance_instance = new AutomateAttendance();
                
                    
                    $attendance_instance->user_id = $month_wise_grouping_attendance[$date_check]['user_id'];
                    $attendance_instance->date_attendance = trim($month_wise_grouping_attendance[$date_check]['recordDate']);
                    $attendance_instance->attendance_in_time	 =  $month_wise_grouping_attendance[$date_check]['firstRecordHour'];
                    $attendance_instance->attendance_out_time	 =  $month_wise_grouping_attendance[$date_check]['lastRecordHour'];
                    $attendance_instance->save();
                
                
            }else{
                
                if(  $check_import_data->attendance_in_time == null){
                    
                     AutomateAttendance::where('user_id', $user_id)->whereDate('date_attendance', $record_date)->update([
                        'attendance_in_time'	 =>  $month_wise_grouping_attendance[$date_check]['firstRecordHour']
                    ]);
                }
                 if(  $check_import_data->attendance_out_time == null){
                     
                    AutomateAttendance::where('user_id', $user_id)->whereDate('date_attendance', $record_date)->update([
                        'attendance_out_time'	 =>  $month_wise_grouping_attendance[$date_check]['lastRecordHour']
                    ]);
                }
              
                
            }
            
            
        }

        return redirect()->route('employee.automate_attendance.list');
      
    }


    public function segregate_intime_outtime($groupedByDate)
    {
        $result = [];

        foreach ($groupedByDate as $date => $records) {

            // Get the first and last entries from the array
            $inTime = $records[0]['recordHour'];
            $outTime = end($records)['recordHour'];

            $result[$date] = [
                'user_id' => $records[0]['user_id'],
                'recordDate' => $date,
                'firstRecordHour' => $inTime,
                'lastRecordHour' => count($records) == 1 ? NULL: $outTime,
            ];
        }

        return $result;
    }


 

    public function automate_attendance_list()
    {
        
        $employee_list_data  = DB::table('automate_attendances')
                            ->select('user_id','in_out_edit_value','salary_generate_value','payslip_create', DB::raw('YEAR(date_attendance) as year'), DB::raw('MONTH(date_attendance) as month'), DB::raw('count(*) as attendance_count'))
                            ->groupBy('user_id', 'year', 'month')
                            ->get();


        return view('backend.employee_manage.employee_attendance_automate_show', compact('employee_list_data'));
    }

    public function automate_attendance_edit(Request $request, $user_id, $year, $month)
    {
        $user_id = $user_id;
        $attendance_year = $year;
        $attendance_month = $month;

        $month_wise_attendance_data = AutomateAttendance::where('user_id', $user_id)
                                                    ->where(DB::raw('YEAR(date_attendance)'), $attendance_year)
                                                    ->where(DB::raw('MONTH(date_attendance)'), $attendance_month)
                                                    ->get();
        // dd($month_wise_attendance_data);
        return view('backend.employee_manage.employee_attendance_automate_edit', compact('month_wise_attendance_data'));
        
    }

    public function automate_attendance_store_edited_in_out_time_data(Request $request)
    {
        $total_row_count_for_attendance_id = count($request->id);
      
        $total_present_count = 0;
        for ($i=0; $i <$total_row_count_for_attendance_id ; $i++) { 
            
            if( !empty(trim($request->attendance_in_time[$i])) || !empty(trim($request->attendance_out_time[$i]))){
                $total_present_count++;
            }
            AutomateAttendance::where('id', $request->id[$i])->update([
                'attendance_in_time' => $request->attendance_in_time[$i],
                'attendance_out_time' => $request->attendance_out_time[$i],
            ]);
        }
      

        AutomateAttendance::where('id', $request->id[0])->update([
            'in_out_edit_value' => 1,
            'total_present_count' => $total_present_count
        ]);



        return redirect()->route('employee.automate_attendance.list');



    }


    public function automate_attendance_generate_salary(Request $request,$user_id, $year, $month)
    {
        $user_id = $user_id;
        $attendance_year = $year;
        $attendance_month = $month;

        $employee_attendance_data = AutomateAttendance::where('user_id', $user_id)
                                                    ->where(DB::raw('YEAR(date_attendance)'), $attendance_year)
                                                    ->where(DB::raw('MONTH(date_attendance)'), $attendance_month)
                                                    ->get();
        //dd($employee_attendance_data);
        return view('backend.employee_manage.employee_attendance_automate_salary_generate', compact('employee_attendance_data'));

    }

    public function automate_attendance_generate_salary_store(Request $request)
    {
        // dd($request->all());

        $total_row_count_for_attendance_id = count($request->id);

        for ($i=0; $i <$total_row_count_for_attendance_id ; $i++) { 
            AutomateAttendance::where('id', $request->id[$i])->update([
                'approve_dellay' => $request->approve_Delay[$i],
                'un_approve_dellay' => $request->un_approve_Delay[$i],
                'approve_leave' => $request->approve_leave[$i],
                'un_approve_leave' => $request->un_approve_leave[$i],
            ]);
        }

        AutomateAttendance::where('id', $request->id[0])->update([
            'salary_generate_value' => 1,
            "comments_admin" => $request->comments_admin,
            "comments_prepared" => $request->comments_prepared,
            "comments_ceo" => $request->comments_ceo,
            "total_approve_dellay" => $request->total_approve_Delay,
            "total_un_approve_dellay" => $request->total_un_approve_Delay,
            "total_un_approve_leave" => $request->total_un_approve_leave,
            "total_approve_leave" => $request->total_approve_leave,
            "salary" => $request->salary,
            "deduction_amount" => $request->deduction_amount,
            "final_payamount" => $request->final_payamount,
            "total_absence" => $request->total_absence_val,
        ]);


        return redirect()->route('employee.automate_attendance.list');

    }


    public function automate_attendance_print(Request $request,$user_id, $year, $month)
    {
        $user_id = $user_id;
        $attendance_year = $year;
        $attendance_month = $month;

        $employee_attendance_data = AutomateAttendance::where('user_id', $user_id)
                                                    ->where(DB::raw('YEAR(date_attendance)'), $attendance_year)
                                                    ->where(DB::raw('MONTH(date_attendance)'), $attendance_month)
                                                    ->get();
        
        return view('backend.employee_manage.employee_attendance_automate_print', compact('employee_attendance_data'));

    }

    public  function automate_attendance_delete(Request $request,$user_id, $year, $month)
    {
        $user_id = $user_id;
        $attendance_year = $year;
        $attendance_month = $month;

        $employee_attendance_data = AutomateAttendance::where('user_id', $user_id)
                                                    ->where(DB::raw('YEAR(date_attendance)'), $attendance_year)
                                                    ->where(DB::raw('MONTH(date_attendance)'), $attendance_month)
                                                    ->delete();
        
        return redirect()->route('employee.automate_attendance.list');
    }

    public function employee_panel_list()
    {
        $employee_data = User::where('customer_type', 4)->get();
        return view('backend.employee_manage.employee_automate_panel_list', compact('employee_data'));
    }

    public function employee_panel_edit(Request $request, $id)
    {
        $user_data = User::where('id', $id)->first();

        return view('backend.employee_manage.employee_automate_edit', compact('user_data'));

    }

    public function employee_panel_update(Request $request)
    {
        // dd($request->all());
        $request->validate([    
            'employee_user_id' => 'required',
            'employee_name' => 'required',
            'employee_phone' => 'required',
            'employee_id' => 'required',
            'employee_designation' => 'required',
            'employee_salary' => 'required',
            'employee_department' => 'required',
            'employee_status' => 'required',
            'employe_date_of_joining' => 'required'
        
        ],
        [
            'employee_user_id.required' => 'Employee user ID is required',
            'employee_name.required' => 'Employee name is required',
            'employee_phone.required' => 'Employee phone number is required',
            'employee_id.required' => 'Employee attendance ID is required',
            'employee_salary.required' => 'Employee salary is required',
            'employee_designation.required' => 'Employee Designation is required',
            'employee_department.required' => 'Employee Department is required',
            'employee_status.required' => 'Employee Status is required',
            'employee_status.required' => 'Employee Status is required',
            'employe_date_of_joining.required' => 'Employee Joining Date is required'

        ]);

        User::where('id', $request->employee_user_id)->update([
            'name' => $request->employee_name,
            'phone' => "+88".$request->employee_phone,
            'employee_id' => $request->employee_id,
            'employee_salary' => $request->employee_salary,
            'designation' => $request->employee_designation,
            'employee_department' => $request->employee_department,
            'employee_status' => $request->employee_status,
            'employe_date_of_joining' => $request->employe_date_of_joining,
            'empl_permanent_dat' => $request->empl_permanent_dat,
        ]);

        return redirect()->route('employee.employee_panel.list')->with('message', 'Employee panel list updated successfully');

        
    }

    public function employee_current_attendance_data()
    {

        $current_year = date('Y');
        $current_month = date('m');

        $employee_list_data = DB::table('automate_attendances')
                            ->select('user_id', 'in_out_edit_value', 'salary_generate_value', DB::raw('YEAR(date_attendance) as year'), DB::raw('MONTH(date_attendance) as month'), DB::raw('count(*) as attendance_count'))
                            ->where(DB::raw('YEAR(date_attendance)'), '=', $current_year)
                            ->where(DB::raw('MONTH(date_attendance)'), '=', $current_month)
                            ->groupBy('user_id', 'year', 'month')
                            ->get();

        
        return view('backend.employee_manage.employee_attendance_automate_current_attendance', compact('employee_list_data'));

    }

    public function employee_current_attendance_details($user_id, $year, $month)
    {
        $user_id = $user_id;
        $attendance_year = $year;
        $attendance_month = $month;

        $current_month_attendance_data = AutomateAttendance::where('user_id', $user_id)
                                                    ->where(DB::raw('YEAR(date_attendance)'), $attendance_year)
                                                    ->where(DB::raw('MONTH(date_attendance)'), $attendance_month)
                                                    ->get();
        return view('backend.employee_manage.employee_attendance_atutomate_single_employee_current_data', compact('current_month_attendance_data'));

    }

    public function payslip_create($user_id, $year, $month)
    {
        $user_id = $user_id;
        $attendance_year = $year;
        $attendance_month = $month;

        $current_month_employee_datas= AutomateAttendance::where('user_id', $user_id)
                                                            ->where(DB::raw('YEAR(date_attendance)'), $attendance_year)
                                                            ->where(DB::raw('MONTH(date_attendance)'), $attendance_month)
                                                            ->first();
        $current_month_total_un_approve_leave = $current_month_employee_datas['total_un_approve_leave'];
        $current_month_total_present_count = $current_month_employee_datas['total_present_count'];
        $current_month_employee_salary = $current_month_employee_datas['salary'];


        $user_data = User::where('employee_id', $user_id)->first();
        
        return view('backend.employee_manage.payslip_automate_generate', compact('user_data', 'current_month_total_un_approve_leave','current_month_total_present_count', 'attendance_year','attendance_month','current_month_employee_salary', 'attendance_month', 'attendance_year'));
    }

    public function payslip_store(Request $request)
    {
        
        $data =$request->except('_token');
  
        Payslip::create($data);

        AutomateAttendance::where('user_id', $request->automated_user_id)
                            ->where(DB::raw('YEAR(date_attendance)'), $request->attendance_year)
                            ->where(DB::raw('MONTH(date_attendance)'), $request->attendance_month)
                            ->update([
                                'payslip_create' => 1
                            ]);

 
        return redirect()->route('employee.automate_attendance.list');

    }

    public function payslip_edit($user_id, $year, $month)
    {
        $user_id = $user_id;
        $attendance_year = $year;
        $attendance_month = $month;

        $single_employee_payslip_data= Payslip::where('automated_user_id', $user_id)
                                                            ->where('attendance_year', $attendance_year)
                                                            ->where('attendance_month', $attendance_month)
                                                            ->first();

        $current_month_employee_datas= AutomateAttendance::where('user_id', $user_id)
                                                            ->where(DB::raw('YEAR(date_attendance)'), $attendance_year)
                                                            ->where(DB::raw('MONTH(date_attendance)'), $attendance_month)
                                                            ->first();

        $current_month_total_present_count = $current_month_employee_datas['total_present_count'];
        $current_month_employee_salary = $current_month_employee_datas['salary'];
                                                            
        $user_data = User::where('employee_id', $user_id)->first();
       
        return view('backend.employee_manage.payslip_automate_edit', compact('single_employee_payslip_data','current_month_total_present_count', 'user_data', 'current_month_employee_salary', 'attendance_month', 'attendance_year'));
    }

    public function payslip_update(Request $request, $user_id, $year, $month)
    {
        $user_id = $user_id;
        $attendance_year = $year;
        $attendance_month = $month;

        $data =$request->except('_token');
        
        $single_employee_payslip_data= Payslip::where('automated_user_id', $user_id)
                                                            ->where('attendance_year', $attendance_year)
                                                            ->where('attendance_month', $attendance_month)
                                                            ->update([
                                                                'mobile_allowance' => $data['mobile_allowance'],
                                                                'lunch_allowance' => $data['lunch_allowance'],
                                                                'festibal_allowance' => $data['festibal_allowance'],
                                                                'other_allowance' => $data['other_allowance'],
                                                                'provident_fund' => $data['provident_fund'],
                                                                'delay' => $data['delay'],
                                                                'absence' => $data['absence'],
                                                                'loan_adjust' => $data['loan_adjust'],
                                                                'advance_adjust' => $data['advance_adjust'],
                                                                'total_earnings_value' => $data['total_earnings_value'],
                                                                'total_deduction' => $data['total_deduction'],
                                                                'total_net_pay' => $data['total_net_pay'],
                                                                'paymentMethod' => $data['paymentMethod'],
                                                                'check_details' => $data['check_details'],
                                                            ]);
        return redirect()->route('employee.automate_attendance.list');

    }

    public function payslip_print($user_id, $year, $month)
    {
        $user_id = $user_id;
        $attendance_year = $year;
        $attendance_month = $month;

        $single_employee_payslip_data= Payslip::where('automated_user_id', $user_id)
                                                            ->where('attendance_year', $attendance_year)
                                                            ->where('attendance_month', $attendance_month)
                                                            ->first();

        $current_month_employee_datas= AutomateAttendance::where('user_id', $user_id)
                                                            ->where(DB::raw('YEAR(date_attendance)'), $attendance_year)
                                                            ->where(DB::raw('MONTH(date_attendance)'), $attendance_month)
                                                            ->first();

        $current_month_total_present_count = $current_month_employee_datas['total_present_count'];
        $current_month_employee_salary = $current_month_employee_datas['salary'];

        $user_data = User::where('employee_id', $user_id)->first();

        return view('backend.employee_manage.payslip_automate_print', compact('single_employee_payslip_data','current_month_total_present_count', 'user_data', 'current_month_employee_salary', 'attendance_month', 'attendance_year'));


    }



    public function udpate_attendance_data()
    {
         // Set the URL of the API endpoint
        $url = 'http://192.168.0.171:3999/attendence-list';

        // Fetch the data from the API
        $data = file_get_contents($url);
        // Decode the JSON data into a PHP array
            $array = json_decode($data, true);
            
        //$response = Http::post('http://192.168.0.171/maakview_micro_version/api/v1/store_attendance_data', $array);
        //$response = Http::post('http://192.168.0.171/maakview_micro_version/api/v1/store_new_attendance_data', $array);
        $response = Http::post('https://www.maakview.com/api/v1/store_new_attendance_data', $array);
        if($response){
            return "Data fetch Succesfully Done";
        }
    }



}
