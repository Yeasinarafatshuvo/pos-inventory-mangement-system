<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Document</title>
    <style>
        body{
            margin: 0;
            box-sizing: border-box;
        }

        table
            {
            border-collapse: separate;
            border-spacing: 0 8px;
            }
    
       
    </style>
</head>
<body>

    <div class="container" style="border: 1px dotted #3a3a3a; margin-top:30px; margin-bottom:75px">
        <button style="margin-left: 90%; margin-top:5px; border:none;background:#6A4FE0;color:#fff">Office Copy</button>
        <div class="logo" style="margin: auto; text-align:center">
            <img class="m-0 p-0" src="{{asset('logo')}}/maakview.png"  height="75" alt="Logo"> 
        </div>
        <div class="text-body" style="margin: auto; text-align:center">
            <p style="margin-bottom: 0;">Address: Rahima Plaza(6th floor), 82/3 Laboratory Road, Dhaka-1205, Email: maakview.info@gmail.com</p>
            <p style="margin-top: 0; padding-top:0">Website: www.maakview.com, Phone: +8801888012727, 01886-531777</p>
        </div>
        <div class="money_receipt_title" style="margin: auto; text-align:center">
            <button style="border: none; padding:10px; background-color:#6A4FE0; border-radius:6px; font-weight:bold; color:#fff; width:280px;font-size:20px">Money Payment</button>
        </div>
       

        <div class="main_contain" style="margin: auto;width:90%">
            <div style="display: flex; justify-content: space-between;">
                <div style="border: 1px solid black; width: 200px;padding:2px;font-size: 16px">
                    Invoice No: {{$supplier_money_payment_data->order_invoice}}
                </div>
                <div style="border: 1px solid black; width: 200px;padding:2px;font-size: 16px">
                    Date: <?php echo date("d-m-Y"); ?>
                </div>
            </div>
            
            <div class="main_body">
                <table style="width: 100%;">
                    <thead>
                        <tr style="padding-bottom:10px">
                            <td colspan="12" style="border-bottom: 1px dotted #3a3a3a;font-size:18px"><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">Payment  with thanks to :</span><span style="padding-left: 5px">{{$user_name}}</span></td>
                        </tr>
                        <tr>
                            <td colspan="12"  style="border-bottom: 1px dotted #3a3a3a;font-size:18px"><span style="font-weight:bold;padding-bottom: 3px;  border-bottom: 1px solid white">the sum of taka(in words) :</span><span style="padding-left: 5px">{{ucfirst($supplier_money_payment_data->total_payment_in_word)}}</span></td>
                        </tr>
                   
                        <tr>
                            <td colspan="12" style="border-bottom: 1px dotted #3a3a3a;font-size:18px"><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">on account of:</span><span style="padding-left: 5px">{{$supplier_money_payment_data->maakview_account_name}}</span></td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px dotted #3a3a3a;width:46%;font-size:18px" ><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">by cash/cheque no : </span><span style="padding-left: 5px">{{$supplier_money_payment_data->maakview_checque_number}}</span></td>
                            <td style="border-bottom: 1px dotted #3a3a3a;width:31%;font-size:18px" ><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">date : </span><span style="padding-left: 5px">{{$supplier_money_payment_data->maakview_cheque_date == null ? "":date("d-m-Y", strtotime($supplier_money_payment_data->maakview_cheque_date))}}</span></td>
                            <td style="border-bottom: 1px dotted #fff;width:22%;font-size:18px" ><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">advance/full payment</span></td>
                        </tr>
                    </thead>
                </table>
              
                <table style="width: 100%">
                     <thead> <tr>
                        <td style="border-bottom: 1px dotted #3a3a3a;width:75%;font-size:18px"><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">against the bill : </span><span style="padding-left: 5px">{{$supplier_money_payment_data->bill_info}}</span></td>
                        <td style="border-bottom: 1px dotted #3a3a3a;width:25%;font-size:18px"><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">dated:</span><span style="padding-left: 5px">{{date_format($purchase_created_date, 'd-m-Y')}}</span></td>
                    </tr></thead>
                </table>
            </div>
        

        <div style="display: flex; justify-content: space-between; margin-top:150px; margin-bottom:15px">
            <div style="border: 1px solid black;padding-left:10px;padding-right:20px;font-size: 18px">
                Taka:{{number_format((float)$supplier_money_payment_data->total_payment_in_number, 2, '.', '')}}
            </div>
            <div>
                <span style="text-decoration: overline;font-size: 18px">Customer Signature</span>
            </div>
            <div>
                <span style="text-decoration: overline;font-size: 15px"> For Maakview Ltd</span>
            </div>
        </div>
        </div>
    </div>
    <hr style="border-top: 1px dashed black;">

    {{-- customer copy --}}
    <div class="container" style="border: 1px dotted #3a3a3a; margin-top:75px; margin-bottom:0px">
        <button style="margin-left: 87%; margin-top:5px; border:none;background:#6A4FE0;color:#fff">Customer Copy</button>
        <div class="logo" style="margin: auto; text-align:center">
            <img class="m-0 p-0" src="{{asset('logo')}}/maakview.png"  height="75" alt="Logo"> 
        </div>
        <div class="text-body" style="margin: auto; text-align:center">
            <p style="margin-bottom: 0;">Address: Rahima Plaza(6th floor), 82/3 Laboratory Road, Dhaka-1205, Email: maakview.info@gmail.com</p>
            <p style="margin-top: 0; padding-top:0">Website: www.maakview.com, Phone: +8801888012727</p>
        </div>
        <div class="money_receipt_title" style="margin: auto; text-align:center">
            <button style="border: none; padding:10px; background-color:#6A4FE0; border-radius:6px; font-weight:bold; color:#fff; width:280px;font-size:20px">Money Payment</button>
        </div>
       

        <div class="main_contain" style="margin: auto;width:90%">
            <div style="display: flex; justify-content: space-between;">
                <div style="border: 1px solid black; width: 200px;padding:2px;font-size: 16px">
                    Invoice No: {{$supplier_money_payment_data->order_invoice}}
                </div>
                <div style="border: 1px solid black; width: 200px;padding:2px;font-size: 16px">
                    Date: <?php echo date("d-m-Y"); ?>
                </div>
            </div>
            <div class="main_body">
                <table style="width: 100%;">
                    <thead>
                        <tr style="padding-bottom:10px">
                            <td colspan="12" style="border-bottom: 1px dotted #3a3a3a;font-size:18px"><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">Payment with thanks to :</span><span style="padding-left: 5px">{{$user_name}}</span></td>
                        </tr>
                        <tr>
                            <td colspan="12"  style="border-bottom: 1px dotted #3a3a3a;font-size:18px"><span style="font-weight:bold;padding-bottom: 3px;  border-bottom: 1px solid white">the sum of taka(in words) :</span><span style="padding-left: 5px">{{ucfirst($supplier_money_payment_data->total_payment_in_word)}}</span></td>
                        </tr>
                   
                        <tr>
                            <td colspan="12" style="border-bottom: 1px dotted #3a3a3a;font-size:18px"><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">on account of :</span><span style="padding-left: 5px">{{$supplier_money_payment_data->maakview_account_name}}</span></td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px dotted #3a3a3a;width:40%;font-size:18px" ><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">by cash/cheque no : </span><span style="padding-left: 5px">{{$supplier_money_payment_data->maakview_checque_number}}</span></td>
                            <td style="border-bottom: 1px dotted #3a3a3a;width:30%;font-size:18px" ><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">date : </span><span style="padding-left: 5px">{{$supplier_money_payment_data->maakview_cheque_date == null ? "":date("d-m-Y", strtotime($supplier_money_payment_data->maakview_cheque_date))}}</span></td>
                            <td style="border-bottom: 1px dotted #fff;width:22%;font-size:18px" ><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">advance/full payment</span></td>
                        </tr>
                    </thead>
                </table>
              
                <table style="width: 100%">
                     <thead> <tr>
                        <td style="border-bottom: 1px dotted #3a3a3a;width:75%;font-size:18px"><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">against the bill : </span><span style="padding-left: 5px">{{$supplier_money_payment_data->bill_info}}</span></td>
                        <td style="border-bottom: 1px dotted #3a3a3a;width:25%;font-size:18px"><span style="font-weight: bold;padding-bottom: 3px;  border-bottom: 1px solid white">dated:</span><span style="padding-left: 5px">{{date_format($purchase_created_date, 'd-m-Y')}}</span></td>
                    </tr></thead>
                </table>
            </div>
        
            <div style="display: flex; justify-content: space-between; margin-top:150px; margin-bottom:15px">
                <div style="border: 1px solid black;padding-left:10px;padding-right:20px;font-size: 18px">
                    Taka:{{number_format((float)$supplier_money_payment_data->total_payment_in_number, 2, '.', '')}}
                </div>
                <div>
                    <span style="text-decoration: overline;font-size: 18px">Customer Signature</span>
                </div>
                <div>
                    <span style="text-decoration: overline;font-size: 15px"> For Maakview Ltd</span>
                </div>
            </div>
        </div>
    </div>
  



    











    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script type="text/javascript">
    try {
            
            if( $(document).height() > 1100 ){
                $('.footer').css('position','inherit');
                $('.footer').css('padding-top','50px');
                $('.footer').css('clear','both');
            }else{
                $('.footer').css('position','fixed');
                $('.footer').css('bottom','0');
                $('.footer').css('clear','both');
            }

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