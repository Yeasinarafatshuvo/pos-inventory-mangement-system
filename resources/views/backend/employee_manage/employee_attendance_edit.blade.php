@extends('backend.layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('content')
<style type="text/css">
	    
table,thead, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
.absence:hover{
   color:green !important;
}

.in_time_late_not_approve:hover{
   color:green !important;
}
.in_time_late_approve:hover{
   color:red !important;
}
.absence_hover:hover{
   color:red !important;
}


</style>

<body>
<div id="print_area_of_div">
    <form action="{{route('employee.attendance.update',$employee_attendance_data[0]->attendance_id)}}" method="POST">
    @csrf
    <table style="width:100%" class="row_height">
        <tr class="text-center">
            <th colspan="9" style="border: 1px solid black !important">For The Month Of {{date("M-Y",strtotime($employee_attendance_data[0]->date_attendance))}} (Individual Statement)</th>
        </tr>
        <tr class="text-center">
            <input type="hidden" name="name" value="{{$employee_attendance_data[0]->name}}">
            <th colspan="9" style="border: 1px solid black !important">Name: {{$employee_attendance_data[0]->name}}</th>
        </tr>
        <tr class="text-center">
          <th style="border: 1px solid black !important" rowspan="2" class="text-center">Date</th>
          <th style="border: 1px solid black !important" colspan="2" class="text-center">Every Day</th>
          <th style="border: 1px solid black !important" colspan="2" class="text-center">Delay</th>
          <th style="border: 1px solid black !important" rowspan="2" class="text-center">Cre. Leave (Date)</th>
          <th style="border: 1px solid black !important" rowspan="2" class="text-center">App Leave (Date)</th>
          <th style="border: 1px solid black !important" rowspan="2"  class="text-center"><span style="color: white" >.....</span>Comments<span style="color: white" >.....</span></th>
        </tr>
        <tr>
          <td style="border: 1px solid black !important" class="text-center">Attended time</td>
          <td style="border: 1px solid black !important" class="text-center">Out Time</td>
          <td style="border: 1px solid black !important" colspan="1" class="text-center">Un App.Delay</td>
          <td style="border: 1px solid black !important" colspan="1" class="text-center">App.Delay</td>
        </tr>
        @foreach ($employee_attendance_data as $key => $employee_attendance_item)
                <?php
                    $timestamp = strtotime($employee_attendance_item->date_attendance);
                    $day = date('D', $timestamp);

                    $atttendance_time = date('h:i', strtotime($employee_attendance_item->attendance_in_time));
                    $office_time =  date('h:i', strtotime('09:40'));
                ?>

            <tr>
                <input type="hidden" name="primary_id[]" value="{{$employee_attendance_item->id}}">
                @if ($day !== 'Fri')
                    <input type="hidden" name="date_attendance[]" value="{{$employee_attendance_item->date_attendance}}">
                    <td style="border: 1px solid black !important; text-align:center" class="p-2">{{$employee_attendance_item->date_attendance}}</td>
                    @else
                    <input type="hidden" name="date_attendance[]" value="{{$employee_attendance_item->date_attendance}}">
                    <td style="border: 1px solid black !important; color:red; text-align:center" class="p-2" >Friday</td>
                @endif
                <input type="hidden" name="attendance_in_time[]" value="{{$employee_attendance_item->attendance_in_time}}">
                <td class="text-center" style="border: 1px solid black !important;background: {{strtotime($atttendance_time) > strtotime($office_time)? 'red':'white'}};color: {{strtotime($atttendance_time) > strtotime($office_time)? 'white':'black'}}">{{$employee_attendance_item->attendance_in_time}}</td>

                <input type="hidden" name="attendance_out_time[]" value="{{$employee_attendance_item->attendance_out_time == ""?'': date('h:i', strtotime($employee_attendance_item->attendance_out_time))}}">
                <td style="border: 1px solid black !important; text-align:center">{{$employee_attendance_item->attendance_out_time == ""?'': date('h:i', strtotime($employee_attendance_item->attendance_out_time))}}</td>
                
                {{-- start un approved Delay --}}
                @if ($employee_attendance_item->un_approve_dellay !== null)
                    <input type="hidden" class="in_time_late_not_approve_class" id="in_time_late_apprv{{$key}}">
                    <input type="hidden" id="un_approve_Delay{{$key}}" name="un_approve_Delay[]" value="{{$employee_attendance_item->un_approve_dellay}}">
                    <td id="in_time_late_not_approve{{$key}}" style="border: 1px solid black !important; background-color:red; color:white; text-align:center" class="p-2" >{{$employee_attendance_item->un_approve_dellay}} <i class="fa fa-times pl-5 in_time_late_not_approve" onclick="in_time_late_not_approve(<?php echo $key?>)" style="color:red; cursor:pointer" aria-hidden="true"></i></td>
                @else
                    <input type="hidden" id="in_time_late_apprv{{$key}}">
                    <input type="hidden" id="un_approve_Delay{{$key}}" name="un_approve_Delay[]" value="{{$employee_attendance_item->un_approve_dellay}}">
                    <td id="in_time_late_not_approve{{$key}}" style="border: 1px solid black !important; color:red; text-align:center" class="p-2" >{{$employee_attendance_item->un_approve_dellay}}</td>
                @endif
               
                {{-- end un approved Delay --}}

                {{-- start approved Delay --}}
                @if ($employee_attendance_item->approve_dellay == 0)
                    <input type="hidden" name="approve_Delay[]" value="{{$employee_attendance_item->approve_dellay}}" id="approve_Delay{{$key}}">
                    <td id="in_time_late_approve{{$key}}" style="border: 1px solid black !important; color:white; text-align:center" class="p-2" ></td>
                @elseif ($employee_attendance_item->approve_dellay == 1)
                    <input type="hidden" name="approve_Delay[]" value="{{$employee_attendance_item->approve_dellay}}" id="approve_Delay{{$key}}">
                    <td id="in_time_late_approve{{$key}}" style="border: 1px solid black !important;background-color:green;color:white; text-align:center" class="p-2" ><span id="approved_cancel{{$key}}" class="count_apprv_late">Approved</span><i id="icon_delete{{$key}}" class="fa fa-times pl-5 in_time_late_approve" onclick='in_time_late_approve("<?php echo $key ?>", "<?php echo $employee_attendance_item->attendance_in_time ?>")' style="color:green; cursor:pointer" aria-hidden="true"></i></td>
                @endif
                {{-- end approved Delay --}}

                {{-- start leave not approve --}}
                @if ($employee_attendance_item->un_approve_leave !== null)
                    <input type="hidden" class="absence_leave_id" id="absence_leave_id{{$key}}">
                    <input type="hidden" name="un_approve_leave[]" value="{{$employee_attendance_item->un_approve_leave}}" id="un_approve_leave_val{{$key}}">
                    <td id="absence_leave{{$key}}"   style="border: 1px solid black !important; color:white; background:green;" colspan="1" class="text-center">{{$employee_attendance_item->un_approve_leave}}<i class="fa fa-times pl-5 absence absence_hover" onclick="absence_leave(<?php echo $key?>)" style="color:green; cursor:pointer" aria-hidden="true"></i></td>
                @else
                    <input type="hidden" id="absence_leave_id{{$key}}">
                    <input type="hidden" name="un_approve_leave[]" value="{{$employee_attendance_item->un_approve_leave}}" id="un_approve_leave_val{{$key}}">
                    <td   style="border: 1px solid black !important; color:red" colspan="1" class="text-center" id="not_approved_bind{{$key}}">{{$employee_attendance_item->un_approve_leave}}</td>
                @endif
                {{-- end leave not approve  --}}

                {{-- leave approve start --}}
                 @if ($employee_attendance_item->approve_leave == 0)
                        <input type="hidden" name="approve_leave[]" value="{{$employee_attendance_item->approve_leave}}" id="approve_leave_val{{$key}}">
                        <td   style="border: 1px solid black !important;color:white" colspan="1" class="text-center" id="approve_leave{{$key}}"></td>
                     @elseif ($employee_attendance_item->approve_leave == 1)
                        <input type="hidden" name="approve_leave[]" value="{{$employee_attendance_item->approve_leave}}" id="approve_leave_val{{$key}}">
                        <td   style="border: 1px solid black !important; background-color:green; color:white" colspan="1" class="text-center" id="approv_leave_column{{$key}}"><span class="count_apprv_leave" id="span_apprv{{$key}}">Approved</span><i class="fa fa-times pl-5  absence_hover" id="approve_icon_delete{{$key}}" onclick='absence_leave_not_approved("<?php echo $key?>", "<?php echo $employee_attendance_item->date_attendance ?>")' style="color:green; cursor:pointer" aria-hidden="true"></i></td>
                 @endif
                {{-- leave approve end --}}

                {{-- start comments input --}}
                @if ($key == 0)
                 <td  style="border: 1px solid black !important; text-align:center">Prepared</td>
                @endif
                @if ($key == 1)
                <td rowspan="10" style="border: 1px solid black !important; text-align:justify;padding-left:5px"><textarea style="border: none; text-align:justify" name="comments_prepared" cols="30"  rows="16">{{$employee_attendance_data[0]->comments_prepared}}</textarea></td>
                @endif
                @if ($key == 11)
                    <td  style="border: 1px solid black !important; text-align:center">Admin</td>
                @endif
                @if ($key == 12)
                    <td rowspan="10" style="border: 1px solid black !important;text-align:justify;padding-left:5px"><textarea style="border:none; text-align:justify" name="comments_admin" cols="30" rows="16">{{$employee_attendance_data[0]->comments_admin}}</textarea></td>
                @endif
                @if ($key == 22)
                    <td  style="border: 1px solid black !important; text-align:center">CEO</td>
                @endif
                @if ($key == 23)
                    <td rowspan="7" style="border: 1px solid black !important;padding-left:5px"><textarea style="border: none; text-align:justify" name="comments_ceo" cols="30" rows="12">{{$employee_attendance_data[0]->comments_ceo}}</textarea></td>
                @endif
                @if (!empty($key == 30))
                <td  style="border: 1px solid black !important; text-align:center"></td>
                @endif
                {{-- end comments input --}}
                
            </tr>
        @endforeach
        <tr class="text-center">
            <td  style="border: 1px solid black !important">Total</td>
            <td  style="border: 1px solid black !important"></td>
            <td  style="border: 1px solid black !important"></td>
            <input type="hidden" value="{{$employee_attendance_data[0]->total_un_approve_dellay}}" name="total_un_approve_Delay" id="total_un_approve_Delay">
            <td  style="border: 1px solid black !important"  id="un_approve_late">{{$employee_attendance_data[0]->total_un_approve_dellay}}</td>
            <input type="hidden" value="{{$employee_attendance_data[0]->total_approve_dellay}}" name="total_approve_Delay" id="total_approve_Delay">
            <td  style="border: 1px solid black !important" id="approve_late">{{$employee_attendance_data[0]->total_approve_dellay}}</td>
            <input type="hidden" value="{{$employee_attendance_data[0]->total_un_approve_leave}}" name="total_un_approve_leave" id="total_un_approve_leave">
            <td  style="border: 1px solid black !important" id="un_approve_leave">{{$employee_attendance_data[0]->total_un_approve_leave}}</td>
            <input type="hidden" value="{{$employee_attendance_data[0]->total_approve_leave}}" name="total_approve_leave" id="total_approve_leave">
            <td  style="border: 1px solid black !important" id="approve_leave">{{$employee_attendance_data[0]->total_approve_leave}}</td>
            <td  style="border: 1px solid black !important" ></td>
        </tr>
        <tr class="text-center">
            <td colspan="9" style="border: 1px solid black !important">Description</td>
        </tr>
        <tr class="text-center">
            <td colspan="3" style="border: 1px solid black !important">Name</td>
            <td colspan="1" style="border: 1px solid black !important">Salary</td>
            <td colspan="1" style="border: 1px solid black !important">Deduction Amount</td>
            <td colspan="4" style="border: 1px solid black !important">After Ded.Pay Amount</td>
        </tr>
        <tr class="text-center">
            <td colspan="3" style="border: 1px solid black !important">{{$employee_attendance_data[0]->name}}</td>
            <td id="print_salary_value" colspan="1" style="border: 1px solid black !important">
                <input type="number" onkeyup="get_employee_salary()" name="salary" id="employee_salary" value="{{$employee_attendance_data[0]->salary}}" style="padding-left:0px; padding-right:0px;margin:0px; text-align:center; border:none">
            </td>
            <input type="hidden" name="deduction_amount" id="deduction_amount" value="{{$employee_attendance_data[0]->deduction_amount}}">
            <td colspan="1" style="border: 1px solid black !important" id="deducted_salary">{{$employee_attendance_data[0]->deduction_amount}}</td>
            <input type="hidden" name="final_payamount" id="final_payamount" value="{{$employee_attendance_data[0]->final_payamount}}">
            <td colspan="4" style="border: 1px solid black !important" id="final_salary">{{$employee_attendance_data[0]->final_payamount}}</td>
        </tr>
        <tr class="text-center">
            @php
                $total_monthly_present_day = 30 -  $employee_attendance_data[0]->total_absence;
            @endphp
            <input type="hidden" name="total_absence_val" id="total_absence_val" value="{{$employee_attendance_data[0]->total_absence}}">
            <td colspan="9" style="border: 1px solid black !important" id="final_salary">Note: Un App.Delay <span id="un_approve_late_desc">{{$employee_attendance_data[0]->total_un_approve_Delay}}</span> Day, Absence <span id="total_absence_unapr">{{$employee_attendance_data[0]->total_absence}}</span> Day, After  Deduction Salary: <span id="salary_id"></span> {{$employee_attendance_data[0]->salary}}/30*<span id="total_present_count">{{$total_monthly_present_day}}</span>=<span id="ending_salary">{{$employee_attendance_data[0]->final_payamount}}</span></td>
        </tr>
    </table>
    <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-primary text-center mt-2">Save</button>
    </div>
</form>
</div>

<script type="text/javascript">
function absence_leave(key)
{
    
    var employee_salary = $('#employee_salary').val();
    if(employee_salary == ""){
        Swal.fire('please, enter employee salary')
    }else{
        $('#absence_leave'+key).css({'backgroundColor':'white'});
        $('#absence_leave'+key).html('');
        $('#absence_leave_id'+key).remove();
        $('#approve_leave'+key).html('<span class="count_apprv_leave">Approved</span>');
        $('#approve_leave_val'+key).val(1);
        $('#un_approve_leave_val'+key).val("");
        $('#approve_leave'+key).css({backgroundColor:'green', textAlign:'center'});
        $('#approve_leave').html($('.count_apprv_leave').length);
        $('#total_approve_leave').val($('.count_apprv_leave').length);
        
    }
   
    var total_day_for_late_in_time = $('.in_time_late_not_approve_class').length;
    var total_absence;
    if(total_day_for_late_in_time >= 3)
    {
         total_absence = Math.floor(total_day_for_late_in_time / 3);
    }else{
        total_absence = 0;
    }
    var absence = $('.absence_leave_id').length;
    var all_absence_sum = total_absence + absence;

    var employee_final_salary = (employee_salary / 30*(30-all_absence_sum));
    var deducted_amount = employee_salary - employee_final_salary;
    $('#total_presence').html(30 -all_absence_sum);
    $('#final_salary').html(Math.round(employee_final_salary));
    $('#final_payamount').val(Math.round(employee_final_salary));
    $('#deducted_salary').html(Math.round(deducted_amount));
    $('#deduction_amount').val(Math.round(deducted_amount));
    $('#ending_salary').html(Math.round(employee_final_salary));

    //total cost
   $('#un_approve_leave').html(absence);
   $('#total_un_approve_leave').val(absence);
   //note description
   $('#total_absence_unapr').html(all_absence_sum);
   $('#total_present_count').html(30 - all_absence_sum);
   $('#total_absence_val').val(all_absence_sum);
   $('#total_un_approve_leave').val(all_absence_sum);
   
}

function absence_leave_not_approved(key, approve_date)
{
    console.log(key);
    var employee_salary = $('#employee_salary').val();
    if(employee_salary == ""){
        Swal.fire('please, enter employee salary')
    }else{
        $('#approv_leave_column'+key).css({'backgroundColor':'white'});
        $('#not_approved_bind'+key).html(approve_date);
        $('#not_approved_bind'+key).css({'background':'green', 'color':'white'});
        $('#span_apprv'+key).remove();
        $('#approve_icon_delete'+key).remove();
        $('#approve_leave_val'+key).val(0);
        $('#un_approve_leave_val'+key).val(approve_date);
        $("#absence_leave_id"+key).addClass('absence_leave_id');
        $('#approve_leave').html($('.count_apprv_leave').length);
        $('#total_approve_leave').val($('.count_apprv_leave').length);
        
    }
   
    var total_day_for_late_in_time = $('.in_time_late_not_approve_class').length;
    var total_absence;
    if(total_day_for_late_in_time >= 3)
    {
         total_absence = Math.floor(total_day_for_late_in_time / 3);
    }else{
        total_absence = 0;
    }
    var absence = $('.absence_leave_id').length;
    var all_absence_sum = total_absence + absence;

    var employee_final_salary = (employee_salary / 30*(30-all_absence_sum));
    var deducted_amount = employee_salary - employee_final_salary;
    $('#total_presence').html(30 -all_absence_sum);
    $('#final_salary').html(Math.round(employee_final_salary));
    $('#final_payamount').val(Math.round(employee_final_salary));
    $('#deducted_salary').html(Math.round(deducted_amount));
    $('#deduction_amount').val(Math.round(deducted_amount));
    $('#ending_salary').html(Math.round(employee_final_salary));

    //total cost
   $('#un_approve_leave').html(absence);
   $('#total_un_approve_leave').val(absence);
   //note description
   $('#total_absence_unapr').html(all_absence_sum);
   $('#total_present_count').html(30 - all_absence_sum);
   $('#total_absence_val').val(all_absence_sum);
   $('#total_un_approve_leave').val(all_absence_sum);
}


function in_time_late_not_approve(key)
{
 
    var employee_salary = $('#employee_salary').val();
    if(employee_salary == ""){
        Swal.fire('please, enter employee salary')
    }else{
        $('#in_time_late_not_approve'+key).css({'backgroundColor':'white'});
        $('#in_time_late_not_approve'+key).html('');
        $('#in_time_late_apprv'+key).remove();
        $('#in_time_late_approve'+key).html('<span class="count_apprv_late">Approved</span>');
        $('#approve_Delay'+key).val(1);
        $('#un_approve_Delay'+key).val("");
        $('#in_time_late_approve'+key).css("background-color", "green");
        $('#approve_late').html($('.count_apprv_late').length);
        $('#total_approve_Delay').val($('.count_apprv_late').length);
        
        //total
        var approve_late_in_office = $('.in_time_late_not_approve_class').length;
        $('#un_approve_late').html(approve_late_in_office);
        $('#total_un_approve_Delay').val(approve_late_in_office);
       
        //description part
        $('#un_approve_late_desc').html(approve_late_in_office);
        var total_absence;
        if(approve_late_in_office >= 3)
        {
            total_absence = Math.floor(approve_late_in_office / 3);
        }else{
            total_absence = 0;
        }

        var absence = $('.absence_leave_id').length;
        var all_absence_sum = total_absence + absence;
       
        $('#total_absence_unapr').html(all_absence_sum);
        $('#total_present_count').html(30 - all_absence_sum);
        $('#total_absence_val').val(all_absence_sum);
        $('#total_un_approve_leave').val(all_absence_sum);
        $('#total_presence').html(30 - all_absence_sum);

        var employee_final_salary = (employee_salary / 30*(30-all_absence_sum));

        var deducted_amount = employee_salary - employee_final_salary;
        $('#final_salary').html(Math.round(employee_final_salary));
        $('#final_payamount').val(Math.round(employee_final_salary));
        $('#deducted_salary').html(Math.round(deducted_amount));
        $('#deduction_amount').val(Math.round(deducted_amount));
        $('#ending_salary').html(Math.round(employee_final_salary));
    }
}

function in_time_late_approve(key, time)
{

    var employee_salary = $('#employee_salary').val();
    if(employee_salary == ""){
        Swal.fire('please, enter employee salary')
    }else{
        $('#approved_cancel'+key).remove();
        $('#icon_delete'+key).remove();
        $('#in_time_late_approve'+key).css("background-color", "white");
        $('#in_time_late_not_approve'+key).html(time);
        $('#in_time_late_apprv'+key).addClass('in_time_late_not_approve_class');
        $('#in_time_late_not_approve'+key).css({'backgroundColor':'red'});
        $('#in_time_late_not_approve'+key).css({'color':'white'});
        $('#approve_Delay'+key).val(0);
        $('#un_approve_Delay'+key).val(time);
        $('#approve_late').html($('.count_apprv_late').length);
        $('#total_approve_Delay').val($('.count_apprv_late').length);
        
        //total
        var approve_late_in_office = $('.in_time_late_not_approve_class').length;
        $('#un_approve_late').html(approve_late_in_office);
        $('#total_un_approve_Delay').val(approve_late_in_office);
       
        //description part
        $('#un_approve_late_desc').html(approve_late_in_office);
        var total_absence;
        if(approve_late_in_office >= 3)
        {
            total_absence = Math.floor(approve_late_in_office / 3);
        }else{
            total_absence = 0;
        }

        var absence = $('.absence_leave_id').length;
        var all_absence_sum = total_absence + absence;
       
        $('#total_absence_unapr').html(all_absence_sum);
        $('#total_present_count').html(30 - all_absence_sum);
        $('#total_absence_val').val(all_absence_sum);
        $('#total_un_approve_leave').val(all_absence_sum);
        $('#total_presence').html(30 - all_absence_sum);

        var employee_final_salary = (employee_salary / 30*(30-all_absence_sum));

        var deducted_amount = employee_salary - employee_final_salary;
        $('#final_salary').html(Math.round(employee_final_salary));
        $('#final_payamount').val(Math.round(employee_final_salary));
        $('#deducted_salary').html(Math.round(deducted_amount));
        $('#deduction_amount').val(Math.round(deducted_amount));
        $('#ending_salary').html(Math.round(employee_final_salary));
    }
}

function get_employee_salary()
{
    var employee_salary = $('#employee_salary').val();
    var total_day_for_late_in_time = $('.in_time_late_not_approve_class').length;
    var total_absence;
    if(total_day_for_late_in_time >= 3)
    {
         total_absence = Math.floor(total_day_for_late_in_time / 3);
    }else{
        total_absence = 0;
    }
    var absence = $('.absence_leave_id').length;
    var all_absence_sum = total_absence + absence;
    var employee_final_salary = (employee_salary / 30*(30-all_absence_sum))
    var deducted_amount = employee_salary - employee_final_salary;
    $('#final_salary').html(Math.round(employee_final_salary));
    $('#final_payamount').val(Math.round(employee_final_salary));
    $('#ending_salary').html(Math.round(employee_final_salary));
    $('#deducted_salary').html(Math.round(deducted_amount));
    $('#deduction_amount').val(Math.round(deducted_amount));
    $('#total_presence').html(30 -all_absence_sum);
    $('#total_absence_unapr').html(all_absence_sum);
    $('#total_absence_val').val(all_absence_sum);
    $('#salary_id').html(employee_salary);
    $('#after_print_show').html(employee_salary);
}



</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
