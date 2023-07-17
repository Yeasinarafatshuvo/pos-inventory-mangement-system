@extends('backend.layouts.app')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('content')
<style>
  tr{
    line-height: 1px;
  }

  

  

 
</style>

<div class="container">
    <h4 class="text-center" style="text-decoration: underline">PAYSLIP</h4>
    <div class="intro">
      <table class="table  table-borderless">
        <tr>
          <td>Date of Joining</td>
          <td>: {{$user_data->employe_date_of_joining}}</td>
          <td>Employee Name</td>
          <td>: {{$user_data->name}}</td>
        </tr>
        <tr>
          @php
            $input = $attendance_month; // Replace this with your input

            // Define an array mapping numbers to month names
            $months = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ];

            // Check if the input number is within the range of 1 to 12
            if ($input >= 1 && $input <= 12) {
                $salaryMonth = $months[$input];
              
            } else {
           
                $salaryMonth = "Invalid input!";
            }

          @endphp
          <td>Pay Period</td>
          <td>: {{$salaryMonth}} {{$attendance_year}} </td>
          <td>Designation</td>
          <td>: {{$user_data->designation}}</td>
          
        </tr>
        <tr>
          <td>Worked Days</td>
          <td>: {{$current_month_total_present_count}}</td>
          <td>Department</td>
          <td>: {{$user_data->employee_department}}</td>
        </tr>
        <tr>
          <td>Employment Status</td>
          <td>: {{ucfirst($user_data->employee_status)}}</td>
          <td>Permanent Date</td>
          <td>: {{$user_data->empl_permanent_dat}}</td>
        </tr>
      </table>
    </div>
    <form action="{{route('payslip.store')}}" method="POST">
      @csrf
      <div class="payslip-data">
          <table class="table table-bordered" style="border: 2px solid black !important;">
              <thead>
                <tr>
                  <th>Earnings</th>
                  <th>Amount</th>
                  <th>Deductions</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Basic Salary (Gross Salary 65%)</td>
                  <td class="text-center">{{round($current_month_employee_salary * 0.65)}}</td>
                  <td>Provident Fund</td>
                  <td><input type="number" id="provident_fund" name="provident_fund" value="0" class="form-control form-control-sm text-center"></td>
                </tr>
                <tr>
                  <td>House Rent Allowance (Gross Salary 15%)</td>
                  <td class="text-center">{{round($current_month_employee_salary * 0.15)}}</td>
                  <td>Delay</td>
                  <td><input type="number" id="delay" name="delay" value="0" class="form-control form-control-sm text-center"></td>
                </tr>
                <tr>
                  <td>Medical Allowance (Gross Salary 10%)</td>
                  <td class="text-center">{{round($current_month_employee_salary * 0.10)}}</td>
                  @php
                      $one_day_salary = round($current_month_employee_salary / 30);
                      $total_absence_deduction_salary = $one_day_salary * $current_month_total_un_approve_leave;

                  @endphp
                  <td>Absence</td>
                  <td><input type="number" value="{{$total_absence_deduction_salary}}" id="absence" name="absence" class="form-control form-control-sm text-center"></td>
                </tr>
                <tr>
                  <td>Transport Allowance (Gross Salary 10%)</td>
                  <td class="text-center">{{round($current_month_employee_salary * 0.10)}}</td>
                  <td>Loan Adjust</td>
                  <td><input type="number" id="loan_adjust" name="loan_adjust" value="0" class="form-control form-control-sm text-center"></td>
                </tr>
                <tr>
                  <td>Mobile Allowance</td>
                  <td><input type="number" id="mobile_allowance" name="mobile_allowance" value="0" class="form-control form-control-sm text-center"></td>
                  <td>Advance Adjust</td>
                  <td><input type="number" id="advance_adjust" name="advance_adjust" value="0" class="form-control form-control-sm text-center"></td>
                </tr>
                <tr>
                  <td>Lunch Allowance</td>
                  <td><input type="number" id="lunch_allowance" name="lunch_allowance" value="0" class="form-control form-control-sm text-center"></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>Festival Allowance</td>
                  <td><input type="number" id="festibal_allowance" name="festibal_allowance" value="0" class="form-control form-control-sm text-center"></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>Other's Allowances</td>
                  <td><input type="number" id="other_allowance" name="other_allowance" value="0" class="form-control form-control-sm text-center"></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>Total Earnings</td>
                  <input type="hidden" id="employee_salary" value="{{$current_month_employee_salary}}" class="form-control form-control-sm text-center">
                  <input type="hidden" name="automated_user_id" value="{{$user_data->employee_id}}">
                  <input type="hidden" name="attendance_year" value="{{$attendance_year}}">
                  <input type="hidden" name="attendance_month" value="{{$attendance_month}}">
                  <input type="hidden" id="total_earnings_value" name="total_earnings_value" value="{{$current_month_employee_salary}}">
                  <td id="total_earnings" class="text-center">
                    {{$current_month_employee_salary}}
                  </td>
                  <td>Total Deductions</td>
                  <input type="hidden" id="total_deduction" name="total_deduction" value="0" class="form-control form-control-sm text-center">
                  <td id="total_deduction_amount" class="text-center"></td>
                </tr>
                <tr>
                  <td colspan="3">Net Pay</td>
                  <input type="hidden" id="total_net_pay" name="total_net_pay">
                  <td id="net_pay"></td>
                </tr>
              </tbody>
            </table>
            <div class="payment_method">
              <div class="form-group">
                <label for="paymentMethod" style="font-weight: bold; font-size:20px">Payment Method</label>
                <select class="form-control" id="paymentMethod" name="paymentMethod" onchange="togglePaymentOption()">
                  <option value="cash">By cash</option>
                  <option value="check">By check</option>
                </select>
              </div>
            </div>
            <input type="text" style="display: none" class="form-control"  value="" id="check_details" name="check_details" placeholder="Enter check details">
            
      </div>
      <button type="submit" class="btn btn-primary text-center" style="margin-left: 50%">Submit</button>
    </form>  
</div>
  


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">

$('#mobile_allowance').keyup(function (e) { 
  e.preventDefault();
  var mobile_allowance = isNaN(parseInt(e.target.value)) ? 0: parseInt(e.target.value);
  var employee_salary = isNaN(parseInt($('#employee_salary').val())) ? 0 : parseInt($('#employee_salary').val());
  var lunch_allowance = isNaN(parseInt($('#lunch_allowance').val())) ? 0 : parseInt($('#lunch_allowance').val());
  var festibal_allowance = isNaN(parseInt($('#festibal_allowance').val())) ? 0 : parseInt($('#festibal_allowance').val());
  var others_allowance = isNaN(parseInt($('#other_allowance').val())) ? 0 : parseInt($('#other_allowance').val());

 
  var total_earnings = employee_salary + lunch_allowance + mobile_allowance + festibal_allowance+ others_allowance;

  $('#total_earnings').html(total_earnings);
  $('#total_earnings_value').val(total_earnings);


  var total_deduction = $('#total_deduction').val();

  var net_pay = parseInt(total_earnings) - parseInt(total_deduction);
  $('#net_pay').html(net_pay);
  $('#total_net_pay').val(net_pay);
  
  
});

$('#lunch_allowance').keyup(function (e) { 
  e.preventDefault();
  var lunch_allowance = isNaN(parseInt(e.target.value)) ? 0: parseInt(e.target.value);
  var employee_salary = isNaN(parseInt($('#employee_salary').val())) ? 0 : parseInt($('#employee_salary').val());
  var mobile_allowance = isNaN(parseInt($('#mobile_allowance').val())) ? 0 : parseInt($('#mobile_allowance').val());
  var festibal_allowance = isNaN(parseInt($('#festibal_allowance').val())) ? 0 : parseInt($('#festibal_allowance').val());
  var others_allowance = isNaN(parseInt($('#other_allowance').val())) ? 0 : parseInt($('#other_allowance').val());

 
  var total_earnings = employee_salary + mobile_allowance + lunch_allowance + festibal_allowance+ others_allowance;

  $('#total_earnings').html(total_earnings);
  $('#total_earnings_value').val(total_earnings);

  var total_deduction = $('#total_deduction').val();

  var net_pay = parseInt(total_earnings) - parseInt(total_deduction);
  $('#net_pay').html(net_pay);
  $('#total_net_pay').val(net_pay);
  
  
});

$('#festibal_allowance').keyup(function (e) { 
  e.preventDefault();
  var festibal_allowance = isNaN(parseInt(e.target.value)) ? 0: parseInt(e.target.value);
  var employee_salary = isNaN(parseInt($('#employee_salary').val())) ? 0 : parseInt($('#employee_salary').val());
  var mobile_allowance = isNaN(parseInt($('#mobile_allowance').val())) ? 0 : parseInt($('#mobile_allowance').val());
  var lunch_allowance = isNaN(parseInt($('#lunch_allowance').val())) ? 0 : parseInt($('#lunch_allowance').val());
  var others_allowance = isNaN(parseInt($('#other_allowance').val())) ? 0 : parseInt($('#other_allowance').val());

 
  var total_earnings = employee_salary + mobile_allowance + lunch_allowance + festibal_allowance+ others_allowance;

  $('#total_earnings').html(total_earnings);
  $('#total_earnings_value').val(total_earnings);

  var total_deduction = $('#total_deduction').val();

  var net_pay = parseInt(total_earnings) - parseInt(total_deduction);
  $('#net_pay').html(net_pay);
  $('#total_net_pay').val(net_pay);
  
  
});


$('#other_allowance').keyup(function (e) { 
  e.preventDefault();
  var others_allowance = isNaN(parseInt(e.target.value)) ? 0: parseInt(e.target.value);
  var employee_salary = isNaN(parseInt($('#employee_salary').val())) ? 0 : parseInt($('#employee_salary').val());
  var mobile_allowance = isNaN(parseInt($('#mobile_allowance').val())) ? 0 : parseInt($('#mobile_allowance').val());
  var lunch_allowance = isNaN(parseInt($('#lunch_allowance').val())) ? 0 : parseInt($('#lunch_allowance').val());
  var festibal_allowance = isNaN(parseInt($('#festibal_allowance').val())) ? 0 : parseInt($('#festibal_allowance').val());

 
  var total_earnings = employee_salary + mobile_allowance + lunch_allowance + festibal_allowance+ others_allowance;

  $('#total_earnings').html(total_earnings);
  $('#total_earnings_value').val(total_earnings);


  var total_deduction = $('#total_deduction').val();

  var net_pay = parseInt(total_earnings) - parseInt(total_deduction);
  $('#net_pay').html(net_pay);
  $('#total_net_pay').val(net_pay);
  
  
});

$('#provident_fund').keyup(function (e) { 

  var provident_fund = isNaN(parseInt(e.target.value)) ? 0 : parseInt(e.target.value);
  var delay = isNaN(parseInt($('#delay').val())) ? 0 : parseInt($('#delay').val());
  var absence = isNaN(parseInt($('#absence').val())) ? 0 : parseInt($('#absence').val());
  var loan_adjust = isNaN(parseInt($('#loan_adjust').val())) ? 0 : parseInt($('#loan_adjust').val());
  var advance_adjust = isNaN(parseInt($('#advance_adjust').val())) ? 0 : parseInt($('#advance_adjust').val());


    var total_deduction = provident_fund + delay + absence + loan_adjust + advance_adjust;
    $('#total_deduction').val(total_deduction);
    $('#total_deduction_amount').html(total_deduction);

    var total_earnings = $('#total_earnings_value').val();
    var net_pay = parseInt(total_earnings) - parseInt(total_deduction);
    $('#net_pay').html(net_pay);
    $('#total_net_pay').val(net_pay);

});


$('#delay').keyup(function (e) { 

  var delay = isNaN(parseInt(e.target.value)) ? 0 : parseInt(e.target.value);
  var provident_fund = isNaN(parseInt($('#provident_fund').val())) ? 0 : parseInt($('#provident_fund').val());
  var absence = isNaN(parseInt($('#absence').val())) ? 0 : parseInt($('#absence').val());
  var loan_adjust = isNaN(parseInt($('#loan_adjust').val())) ? 0 : parseInt($('#loan_adjust').val());
  var advance_adjust = isNaN(parseInt($('#advance_adjust').val())) ? 0 : parseInt($('#advance_adjust').val());


  var total_deduction = provident_fund + delay + absence + loan_adjust + advance_adjust;
  $('#total_deduction').val(total_deduction);
  $('#total_deduction_amount').html(total_deduction);

  var total_earnings = $('#total_earnings_value').val();
  var net_pay = parseInt(total_earnings) - parseInt(total_deduction);
  $('#net_pay').html(net_pay);
  $('#total_net_pay').val(net_pay);

});

$('#absence').keyup(function (e) { 

  var absence = isNaN(parseInt(e.target.value)) ? 0 : parseInt(e.target.value);
  var provident_fund = isNaN(parseInt($('#provident_fund').val())) ? 0 : parseInt($('#provident_fund').val());
  var delay = isNaN(parseInt($('#delay').val())) ? 0 : parseInt($('#delay').val());
  var loan_adjust = isNaN(parseInt($('#loan_adjust').val())) ? 0 : parseInt($('#loan_adjust').val());
  var advance_adjust = isNaN(parseInt($('#advance_adjust').val())) ? 0 : parseInt($('#advance_adjust').val());


  var total_deduction = provident_fund + delay + absence + loan_adjust + advance_adjust;
  $('#total_deduction').val(total_deduction);
  $('#total_deduction_amount').html(total_deduction);

  var total_earnings = $('#total_earnings_value').val();
  var net_pay = parseInt(total_earnings) - parseInt(total_deduction);
  $('#net_pay').html(net_pay);
  $('#total_net_pay').val(net_pay);

});

$('#loan_adjust').keyup(function (e) { 

  var loan_adjust = isNaN(parseInt(e.target.value)) ? 0 : parseInt(e.target.value);
  var provident_fund = isNaN(parseInt($('#provident_fund').val())) ? 0 : parseInt($('#provident_fund').val());
  var delay = isNaN(parseInt($('#delay').val())) ? 0 : parseInt($('#delay').val());
  var absence = isNaN(parseInt($('#absence').val())) ? 0 : parseInt($('#absence').val());
  var advance_adjust = isNaN(parseInt($('#advance_adjust').val())) ? 0 : parseInt($('#advance_adjust').val());


  var total_deduction = provident_fund + delay + absence + loan_adjust + advance_adjust;
  $('#total_deduction').val(total_deduction);
  $('#total_deduction_amount').html(total_deduction);

  var total_earnings = $('#total_earnings_value').val();
  var net_pay = parseInt(total_earnings) - parseInt(total_deduction);
  $('#net_pay').html(net_pay);
  $('#total_net_pay').val(net_pay);

});


$('#advance_adjust').keyup(function (e) { 

  var advance_adjust = isNaN(parseInt(e.target.value)) ? 0 : parseInt(e.target.value);
  var provident_fund = isNaN(parseInt($('#provident_fund').val())) ? 0 : parseInt($('#provident_fund').val());
  var delay = isNaN(parseInt($('#delay').val())) ? 0 : parseInt($('#delay').val());
  var absence = isNaN(parseInt($('#absence').val())) ? 0 : parseInt($('#absence').val());
  var loan_adjust = isNaN(parseInt($('#loan_adjust').val())) ? 0 : parseInt($('#loan_adjust').val());


  var total_deduction = provident_fund + delay + absence + loan_adjust + advance_adjust;
  $('#total_deduction').val(total_deduction);
  $('#total_deduction_amount').html(total_deduction);

  var total_earnings = $('#total_earnings_value').val();
  var net_pay = parseInt(total_earnings) - parseInt(total_deduction);
  $('#net_pay').html(net_pay);
  $('#total_net_pay').val(net_pay);

});


$(document).ready(function () {
  var total_earnings = $('#total_earnings_value').val();

  var provident_fund = isNaN(parseInt($('#provident_fund').val())) ? 0 : parseInt($('#provident_fund').val());
  var delay = isNaN(parseInt($('#delay').val())) ? 0 : parseInt($('#delay').val());
  var absence = isNaN(parseInt($('#absence').val())) ? 0 : parseInt($('#absence').val());
  var loan_adjust = isNaN(parseInt($('#loan_adjust').val())) ? 0 : parseInt($('#loan_adjust').val());
  var advance_adjust = isNaN(parseInt($('#advance_adjust').val())) ? 0 : parseInt($('#advance_adjust').val());


  var total_deduction = provident_fund + delay + absence + loan_adjust + advance_adjust;
  $('#total_deduction').val(total_deduction);
  
  var total_deduction = $('#total_deduction').val();

  var net_pay = parseInt(total_earnings) - parseInt(total_deduction);
  $('#net_pay').html(net_pay);
  $('#total_net_pay').val(net_pay);

});

function togglePaymentOption()
{
  var payment_method = $('#paymentMethod').val();

  if(payment_method == 'check')
  {
    $('#check_details').show();
  }else{
    $('#check_details').hide();
  }
  console.log($('#paymentMethod').val());
}






</script>




@endsection
