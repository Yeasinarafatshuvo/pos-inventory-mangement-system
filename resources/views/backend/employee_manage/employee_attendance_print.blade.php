<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Employee Attendance Sheet</title>

    <style>
        table,thead, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        }

        .row_height tr{
            line-height: 20px;
        }
    </style>
</head>
<body>
    <body>
        <div id="print_area_of_div">
            <table style="width:100%" class="row_height">
                <tr class="text-center">
                    <th colspan="9" style="border: 1px solid black !important">For The Month Of {{date("M-Y",strtotime($employee_attendance_data[1]->date_attendance))}} (Individual Statement)</th>
                </tr>
                <tr class="text-center">
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
                        @if ($day !== 'Fri')
                            <td style="border: 1px solid black !important; text-align:center" class="p-2">{{$employee_attendance_item->date_attendance}}</td>
                            @else
                            <td style="border: 1px solid black !important; color:red; text-align:center" class="p-2" >Friday</td>
                        @endif
                        <td class="text-center" style="border: 1px solid black !important;color:black; background: {{strtotime($atttendance_time) > strtotime($office_time)? 'red':'white'}}">{{$employee_attendance_item->attendance_in_time}}</td>
                        <td style="border: 1px solid black !important; text-align:center">{{$employee_attendance_item->attendance_out_time == ""?'': date('h:i', strtotime($employee_attendance_item->attendance_out_time))}}</td>
                        
                        {{-- start un approved Delay --}}
                        @if (!empty($employee_attendance_item->un_approve_dellay))
                            <td style="border: 1px solid black !important; color:black;background-color:red; text-align:center" class="p-2" >{{$employee_attendance_item->un_approve_dellay}}</td>
                            @else
                            <td style="border: 1px solid black !important; text-align:center" class="p-2" >{{$employee_attendance_item->un_approve_dellay}}</td>
                        @endif
                        {{-- end un approved Delay --}}
        
                        {{-- start approved Delay --}}
                        @if ($employee_attendance_item->approve_dellay == 0)
                            <td style="border: 1px solid black !important; color:red; text-align:center" class="p-2" ></td>
                            @elseif ($employee_attendance_item->approve_dellay == 1)
                            <td style="border: 1px solid black !important;background-color:green;color:black; text-align:center" class="p-2" >Approved</td>
                        @endif
                        {{-- end approved Delay --}}
        
                        {{-- start leave not approve --}}
                        <td   style="border: 1px solid black !important; color:red" colspan="1" class="text-center">{{$employee_attendance_item->un_approve_leave}}</td>
                        {{-- end leave not  approve  --}}
        
                         {{-- leave approve start --}}
                         @if ($employee_attendance_item->approve_leave == 0)
                            <td   style="border: 1px solid black !important" colspan="1" class="text-center"></td>
                             @elseif ($employee_attendance_item->approve_leave == 1)
                             <td   style="border: 1px solid black !important; background-color:green; color:black" colspan="1" class="text-center">Approved</td>
                         @endif
                        {{-- leave approve end --}}
        
                        {{-- start comments input --}}
                        @if ($key == 0)
                         <td  style="border: 1px solid black !important; text-align:center">Prepared</td>
                        @endif
                        @if ($key == 1)
                        <td rowspan="10" style="border: 1px solid black !important; text-align:justify;padding-left:5px"><textarea style="border: none; text-align:justify" cols="30" rows="16">{{$employee_attendance_data[0]->comments_prepared}}</textarea></td>
                        @endif
                        @if ($key == 11)
                            <td  style="border: 1px solid black !important; text-align:center">Admin</td>
                        @endif
                        @if ($key == 12)
                            <td rowspan="10" style="border: 1px solid black !important;text-align:justify;padding-left:5px"><textarea style="border: none; text-align:justify" cols="30" rows="16">{{$employee_attendance_data[0]->comments_admin}}</textarea></td>
                        @endif
                        @if ($key == 22)
                            <td  style="border: 1px solid black !important; text-align:center">CEO</td>
                        @endif
                        @if ($key == 23)
                            <td rowspan="7" style="border: 1px solid black !important;padding-left:5px"><textarea style="border: none; text-align:justify" cols="30" rows="12">{{$employee_attendance_data[0]->comments_ceo}}</textarea></td>
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
                    <td  style="border: 1px solid black !important" >{{$employee_attendance_data[0]->total_un_approve_dellay}}</td>
                    <td  style="border: 1px solid black !important" >{{$employee_attendance_data[0]->total_approve_dellay}}</td>
                    <td  style="border: 1px solid black !important" >{{$employee_attendance_data[0]->total_un_approve_leave}}</td>
                    <td  style="border: 1px solid black !important" >{{$employee_attendance_data[0]->total_approve_leave}}</td>
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
                        {{$employee_attendance_data[0]->salary}}
                    </td>
                    <td colspan="1" style="border: 1px solid black !important" id="deducted_salary">{{$employee_attendance_data[0]->deduction_amount}}</td>
                    <td colspan="4" style="border: 1px solid black !important" id="final_salary">{{$employee_attendance_data[0]->final_payamount}}</td>
                </tr>
                <tr class="text-center">
                    <td colspan="9" style="border: 1px solid black !important" id="final_salary">Note: Un App.Delay <span >{{$employee_attendance_data[0]->total_un_approve_Delay}}</span> Day, Absence <span>{{$employee_attendance_data[0]->total_absence}}</span> Day, After  Deduction Salary: <span id="salary_id"></span> {{$employee_attendance_data[0]->salary}}/30*{{30 - $employee_attendance_data[0]->total_absence}}</span>=<span id="ending_salary">{{$employee_attendance_data[0]->final_payamount}}</span></td>
                </tr>
                
            </table>
            <div class=" mt-5 pt-3" >
                <span style="margin-right: 180px;font-weight:bold;font-size:16px"> {{$employee_attendance_data[0]->name}}</span><span style="margin-right: 180px;font-weight:bold;font-size:15px;display:inline-block;width:110px"> Prepared By</span><span style="margin-right: 180px;font-weight:bold;font-size:15px;display:inline-block;width:110px">Admin officer</span><span style="font-weight:bold;font-size:15px;width:110px;display:inline-block">Approved by  C.E.O</span>
            </div>
           
        </div> 
        
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>           
    <script type="text/javascript">
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