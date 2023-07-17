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


</style>

<body>
<div id="print_area_of_div">
    <form action="{{route('employee.attendance.store_info')}}" method="POST">
    @csrf
    <table style="width:100%">
        <tr>
        <tr class="text-center">
            <th colspan="9" style="border: 1px solid black !important">For The Month Of {{date("M-Y",strtotime($employee_attendance_data[1]->date))}} (Individual Statement)</th>
        </tr>
        <tr class="text-center">
            <th colspan="9" style="border: 1px solid black !important">Name:{{$employee_attendance_data[1]->name}}</th>
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
          <td style="border: 1px solid black !important" colspan="1" class="text-center">App.Delay</td>
          <td style="border: 1px solid black !important" colspan="1" class="text-center">Un App.Delay</td>
        </tr>
        @foreach ($employee_attendance_data as $key => $employee_attendance_item)
        <?php
            $timestamp = strtotime($employee_attendance_item->date);
            $day = date('D', $timestamp);
        ?>
        @if ($key != 0 && !empty($employee_attendance_item->date))
                    <?php
                        $atttendance_time = date('h:i', strtotime($employee_attendance_item->check_in));
                        $office_time =  date('h:i', strtotime('09:40'));
                    
                    ?>
                <tr> 
                    @if ($day !== 'Fri')
                        <input type="hidden" name="date_attendance[]" value="{{$employee_attendance_item->date}}">
                        <td style="border: 1px solid black !important; text-align:center" class="p-2">{{$employee_attendance_item->date}}</td>
                        @else
                        <input type="hidden" name="date_attendance[]" value="{{$employee_attendance_item->date}}">
                        <td style="border: 1px solid black !important; color:red; text-align:center" class="p-2" >Friday</td>
                    @endif
                    <input type="hidden" name="attendance_in_time[]" value="{{$employee_attendance_item->check_in}}">    
                    <td class="text-center" style="border: 1px solid black !important;background: {{strtotime($atttendance_time) > strtotime($office_time)? 'red':'white'}}">{{$employee_attendance_item->check_in}}</td>
                    <input type="hidden" name="attendance_out_time[]" value="{{$employee_attendance_item->check_out == ""?'': date('h:i', strtotime($employee_attendance_item->check_out))}}">   
                    <td style="border: 1px solid black !important">{{$employee_attendance_item->check_out == ""?'': date('h:i', strtotime($employee_attendance_item->check_out))}}</td>
                    {{-- late approve start --}}
                    <input type="hidden" name="approve_Delay[]" value="0" id="approve_Delay{{$key}}">
                    <td id="in_time_late_approve{{$key}}"  style="border: 1px solid black !important" colspan="1" class="text-center"></td>
                    {{-- late approve end --}}
                    
                    {{-- late not approve start --}}
                    @if (strtotime($atttendance_time) > strtotime($office_time))
                        <input type="hidden" class="in_time_late_not_approve_class" id="in_time_late_apprv{{$key}}">
                        <input type="hidden" id="un_approve_Delay{{$key}}" name="un_approve_Delay[]" value="{{$atttendance_time}}">
                        <td class="text-center" id="in_time_late_not_approve{{$key}}" style="border: 1px solid black !important;background: {{strtotime($atttendance_time) > strtotime($office_time)? 'red':'white'}}">{{$atttendance_time }} <i class="fa fa-times pl-5 in_time_late_not_approve" onclick="in_time_late_not_approve(<?php echo $key?>)" style="color:red; cursor:pointer" aria-hidden="true"></i></td>
                    @else
                        <input type="hidden" name="un_approve_Delay[]" value="">
                        <td style="border: 1px solid black !important" class="text-center"></td>
                    @endif
                    {{-- late not approve approve end --}}

                    {{-- condition for absence leave --}}
                    @if ($employee_attendance_item->check_in == "" && $day !== 'Fri')
                        <input type="hidden" class="absence_leave_id" id="absence_leave_id{{$key}}">
                        <input type="hidden" name="un_approve_leave[]" value="{{$employee_attendance_item->date}}" id="un_approve_leave_val{{$key}}">
                        <td style="border: 1px solid black !important; background:red" class="text-center absence_leave"  id="absence_leave{{$key}}">{{$employee_attendance_item->date}}<i class="fa fa-times pl-5 absence" onclick="absence_leave(<?php echo $key?>)" style="color:red; cursor:pointer" aria-hidden="true"></i></td>
                    @else
                        <input type="hidden" name="un_approve_leave[]" value="" id="un_approve_leave_val{{$key}}">
                        <td style="border: 1px solid black !important"></td>
                    @endif

                    {{-- condition for approve leave --}}
                    <input type="hidden" name="approve_leave[]" value="0" id="approve_leave_val{{$key}}">
                    <td style="border: 1px solid black !important" id="approve_leave{{$key}}"></td>

                    {{-- start comments input --}}
                    @if ($key == 1)
                        <td  style="border: 1px solid black !important; text-align:center">Prepared</td>
                    @endif
                    @if ($key == 2)
                     <td rowspan="9" style="border: 1px solid black !important"><textarea style="width: 100%; text-align:justify" name="comments_prepared" id="" cols="30" rows="17"></textarea></td>
                    @endif
                    @if ($key == 11)
                        <td  style="border: 1px solid black !important; text-align:center">Admin</td>
                    @endif
                    @if ($key == 12)
                        <td rowspan="10" style="border: 1px solid black !important"><textarea style="width: 100%; text-align:justify" name="comments_admin" id="" cols="30" rows="18"></textarea></td>
                    @endif
                    @if ($key == 22)
                        <td  style="border: 1px solid black !important; text-align:center">CEO</td>
                    @endif
                    @if ($key == 23)
                        <td rowspan="7" style="border: 1px solid black !important"><textarea style="width: 100%; text-align:justify" name="comments_ceo" id="" cols="30" rows="13"></textarea></td>
                    @endif
                    @if (!empty($key == 30))
                    <td  style="border: 1px solid black !important; text-align:center"></td>
                    @endif
                    {{-- end comments input --}}
                </tr>
            @endif
        @endforeach
        <tr class="text-center">
            <td  style="border: 1px solid black !important">Total</td>
            <td  style="border: 1px solid black !important"></td>
            <td  style="border: 1px solid black !important"></td>
            <input type="hidden" value="0" name="total_approve_Delay" id="total_approve_Delay">
            <td  style="border: 1px solid black !important" id="approve_late"></td>
            <input type="hidden" value="0" name="total_un_approve_Delay" id="total_un_approve_Delay">
            <td  style="border: 1px solid black !important" id="un_approve_late"></td>
            <input type="hidden" value="0" name="total_un_approve_leave" id="total_un_approve_leave">
            <td  style="border: 1px solid black !important" id="un_approve_leave"></td>
            <input type="hidden" value="0" name="total_approve_leave" id="total_approve_leave">
            <td  style="border: 1px solid black !important" id="approve_leave"></td>
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
            <input type="hidden" name="name" value="{{$employee_attendance_data[1]->name}}">
            <td colspan="3" style="border: 1px solid black !important">{{$employee_attendance_data[1]->name}}</td>
            <td id="print_salary_value" colspan="1" style="border: 1px solid black !important">
                <span style="visibility: hidden;position: absolute;left:380" id="after_print_show"></span>
                <input type="number" onkeyup="get_employee_salary()" name="salary" id="employee_salary" value="" style="padding-left:0px; padding-right:0px;margin:0px; text-align:center; border:none">
            </td>
            <input type="hidden" name="deduction_amount" id="deduction_amount" value="">
            <td colspan="1" style="border: 1px solid black !important" id="deducted_salary">0</td>
            <input type="hidden" name="final_payamount" id="final_payamount" value="">
            <td colspan="4" style="border: 1px solid black !important" id="final_salary">0</td>
        </tr>
        <tr class="text-center">
            <input type="hidden" name="total_absence_val" id="total_absence_val" value="">
            <td colspan="9" style="border: 1px solid black !important" id="final_salary">Note: Un App.Delay <span id="un_approve_late_desc"></span> Day, Absence <span id="total_absence_unapr"></span> Day, After  Deduction Salary: <span id="salary_id"></span>/30*<span id="total_presence"></span>=<span id="ending_salary"></span></td>
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

$(document).ready(function () {
    var total_approve_late_in_time = $('.in_time_late_approve_class').length;
    var total_not_approve_late_in_time = $('.in_time_late_not_approve_class').length;
    var total_not_approve_absence = $('.absence_leave_id  ').length;
    var total_approve_absence = $('.not_approve_absence_leave_id').length;

    $('#approve_late').html(total_approve_late_in_time);
    $('#un_approve_late').html(total_not_approve_late_in_time);
    $('#total_un_approve_Delay').val(total_not_approve_late_in_time);
    $('#un_approve_leave').html(total_not_approve_absence);
    $('#approve_leave').html(total_approve_absence);
    $('#total_approve_leave').val(total_approve_absence);

    //Note description part
    $('#un_approve_late_desc').html(total_not_approve_late_in_time);

    var total_absence;
    if(total_not_approve_late_in_time >= 3)
    {
         total_absence = Math.floor(total_not_approve_late_in_time / 3);
    }else{
        total_absence = 0;
    }
 
    var grand_total_absence_late_not_approve_absence = 30 - (total_absence+total_not_approve_absence);
    
    $('#total_absence_unapr').html((total_absence+total_not_approve_absence));
    $('#total_absence_val').val((total_absence+total_not_approve_absence));
    $('#total_un_approve_leave').val(total_absence+total_not_approve_absence);
    $('#total_presence').html(grand_total_absence_late_not_approve_absence);


});

</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
