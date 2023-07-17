<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Employee Money Receipt</title>

    <style>
      .signature-div {
          display: flex;
          justify-content: space-between;
          align-items: baseline;
      }
            
      .signature-div .signature {
          border-top: 1px solid black;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
      }

      .tablevalue {
          border-collapse: collapse;
          width: 100%;
          font-size: 20px;
          padding: 15px;
      }

      .tablevalue th, .tablevalue td {
          border: 2px solid black;
          padding: 8px;
          text-align: left;
      }

      .date {
            position: absolute;
            top: 0;
            right: 0;
        }

   

    </style>
</head>
<body>
  <div id="currentDate" class="date"></div>
  <img class="m-0 p-0" src="{{asset('logo')}}/maakview.png" width="200" height="50" alt="Logo"> 
  <div class="container" style="margin-top: 100px">
    <h4 class="text-center" style="text-decoration: underline">PAYSLIP</h4>
    <div class="intro">
      <table class="table  table-borderless" style="font-size: 20px">
        <tr>
          <td>Date of Joining</td>
          <td>: {{$user_data->employe_date_of_joining}}</td>
          <td>Employee Name</td>
          <td>: {{$user_data->name}}</td>
        </tr>
        <tr>
          <td>Pay Period</td>
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
      <div class="payslip-data">
        <table class="tablevalue">
          <tr>
              <th>Earnings</th>
              <th>Amount</th>
              <th>Deductions</th>
              <th>Amount</th>
          </tr>
          <tbody>
              <tr>
                  <td>Basic Salary (Gross Salary 65%)</td>
                  <td class="text-center">{{number_format(round($current_month_employee_salary * 0.65), 2, '.', '')}}</td>
                  <td>Provident Fund</td>
                  <td class="text-center">{{number_format($single_employee_payslip_data->provident_fund, 2, '.', '')}}</td>
              </tr>
              <tr>
                  <td>House Rent Allowance (Gross Salary 15%)</td>
                  <td class="text-center">{{number_format(round($current_month_employee_salary * 0.15), 2, '.', '')}}</td>
                  <td>Delay</td>
                  <td class="text-center">{{number_format($single_employee_payslip_data->delay, 2, '.', '')}}</td>
              </tr>
              <tr>
                  <td>Medical Allowance (Gross Salary 10%)</td>
                  <td class="text-center">{{number_format(round($current_month_employee_salary * 0.10), 2, '.', '')}}</td>
                  <td>Absence</td>
                  <td class="text-center">{{number_format($single_employee_payslip_data->absence, 2, '.', '')}}</td>
              </tr>
              <tr>
                  <td>Transport Allowance (Gross Salary 10%)</td>
                  <td class="text-center">{{number_format(round($current_month_employee_salary * 0.10), 2, '.', '')}}</td>
                  <td>Loan Adjust</td>
                  <td class="text-center">{{number_format($single_employee_payslip_data->loan_adjust, 2, '.', '')}}</td>
              </tr>
              <tr>
                  <td>Mobile Allowance</td>
                  <td class="text-center">{{number_format($single_employee_payslip_data->mobile_allowance, 2, '.', '')}}</td>
                  <td>Advance Adjust</td>
                  <td class="text-center">{{number_format($single_employee_payslip_data->advance_adjust, 2, '.', '')}}</td>
              </tr>
              <tr>
                  <td>Lunch Allowance</td>
                  <td class="text-center">{{number_format($single_employee_payslip_data->lunch_allowance, 2, '.', '')}}</td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                  <td>Festival Allowance</td>
                  <td class="text-center">{{number_format($single_employee_payslip_data->festibal_allowance, 2, '.', '')}}</td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                  <td>Other's Allowances</td>
                  <td class="text-center">{{number_format($single_employee_payslip_data->other_allowance, 2, '.', '')}}</td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                  <td>Total Earnings</td>
                  <td id="total_earnings" class="text-center">{{number_format($single_employee_payslip_data->total_earnings_value, 2, '.', '')}}</td>
                  <td>Total Deductions</td>
                  <td id="total_deduction_amount" class="text-center">{{number_format($single_employee_payslip_data->total_deduction, 2, '.', '')}}</td>
              </tr>
              <tr>
                  <td colspan="3" class="text-right">Net Pay</td>
                  <td id="net_pay" class="text-center">{{number_format($single_employee_payslip_data->total_net_pay, 2, '.', '')}}</td>
              </tr>
          </tbody>
      </table>
      </div>

      <div class="payment-term" style="margin-top: 50px">
        <p style="margin-bottom: 0; font-weight:bold; font-size:1.5rem">In Word Taka: <span id="inWordTaka"></span></p>
        <p style="font-weight: bold;font-size:1.5rem">Payment Method: {{ucfirst(!empty($single_employee_payslip_data->check_details)? $single_employee_payslip_data->check_details: $single_employee_payslip_data->paymentMethod)}}</span></p>
      </div>

      <div class="container" style="margin-top: 200px">
        <div class="signature-div">
            <div class="signature">
                <strong>Account Officer:</strong> 
            </div>
            <div class="signature">
                <strong>Employee Signature:</strong>
            </div>
        </div>
    </div>
     
</div>
        
        
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>           
    <script type="text/javascript">

    $(document).ready(function () {
      var number = $('#net_pay').html();
      var result = numberToWords(number);

      var capitalizedText = result.replace(/\b\w/g, function(match) {
        return match.toUpperCase();
      });

      $('#inWordTaka').html(capitalizedText.replace("Point Zero Zero", ' ') +' Only.');

      var formattedDate =  new Date().toLocaleDateString("en-BD", {
                    day: "numeric",
                    month: "long",
                    year: "numeric",
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true,
                    timeZone: 'Asia/Dhaka'
                });

    var dateElement = document.getElementById('currentDate');
    dateElement.textContent = formattedDate;


    });

    function numberToWords(number) {  
            var digit = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];  
            var elevenSeries = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];  
            var countingByTens = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];  
            var shortScale = ['', 'thousand', 'million', 'billion', 'trillion'];  
      
            number = number.toString(); number = number.replace(/[\, ]/g, ''); if (number != parseFloat(number)) return 'not a number'; var x = number.indexOf('.'); if (x == -1) x = number.length; if (x > 15) return 'too big'; var n = number.split(''); var str = ''; var sk = 0; for (var i = 0; i < x; i++) { if ((x - i) % 3 == 2) { if (n[i] == '1') { str += elevenSeries[Number(n[i + 1])] + ' '; i++; sk = 1; } else if (n[i] != 0) { str += countingByTens[n[i] - 2] + ' '; sk = 1; } } else if (n[i] != 0) { str += digit[n[i]] + ' '; if ((x - i) % 3 == 0) str += 'hundred '; sk = 1; } if ((x - i) % 3 == 1) { if (sk) str += shortScale[(x - i - 1) / 3] + ' '; sk = 0; } } if (x != number.length) { var y = number.length; str += 'point '; for (var i = x + 1; i < y; i++) str += digit[n[i]] + ' '; } str = str.replace(/\number+/g, ' '); return str.trim();  
      
        }
        try {
            this.print();
        } catch (e) {
            window.onload = window.print;
        }
        window.onbeforeprint = function() {
            setTimeout(function() {
                window.close();
            }, 1500);
        }
        
        
    </script>
</body>

</html>