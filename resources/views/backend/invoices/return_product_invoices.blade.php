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
        table {
            width: 100%;
        }

     

        table.padding th {
            padding: 0 .8rem;
        }

        table.padding td {
            padding: .8rem;
        }

        table.sm-padding td {
            padding: .5rem .7rem;
        }

        table.lg-padding td {
            padding: 1rem 1.2rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }


        .bold {
            font-weight: bold
        }
        
        .customer_border{
            border: 1px dotted #000000;
            padding-top: 5px;
            margin-bottom: 0px;
        }
        .customer_information li{
            list-style-type: none;
        }

    
       
    </style>
</head>
<body>

    <div>
        <div class="row">
            <div class="col-md-12 ">
                <table class="table p-0 m-0">
                    <tbody>
                        <tr>
                            <td class="m-0">
                                <img class="m-0 p-0" src="{{asset('logo')}}/maakview.png" width="200" height="50" alt="Logo"> 
                            </td>
                            <td class="ml-0">
                                <p class="office_address text-right" style="font-size: 15px">
                                    www.maakview.com <br>
                                    Rahima Plaza(6th Floor),<br>
                                   <span style="display: block; padding-bottom:10px"> 82/3 Laboratory Road, Dhaka-1205 <br>Phone: 01888-012727, 01886-531777</span>
                                   @if ($order->is_pos == 1)
                                        @if (!empty($order->created_by))
                                        Prepared By: {{getUserSoldPersonName($order->created_by)}}<br>
                                        @endif
                                    @else
                                       Online Order<br>
                                   @endif
                                    Invoice Number : {{$order->combined_order->code}}<br>
                                    Total Order: {{(!empty($total_user_order))? $total_user_order-1 : 0 }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="width:100%; margin-top:0;margin-bottom: 3px">
            <div class="customer_border mb-0 mt-0 pb-0  pt-0">
              <ul class="customer_information mb-0 mt-0  pb-0  pt-0" style="margin-left: 0px;padding-left: 5px">
                  <li>{{ translate('Customer Name') }}: {{ $order->user->name}}</li>
                   @if ($order->billing_address !== null  && !empty($order->billing_address))
                       @php
                       $user_info = json_decode($order->billing_address);
                       @endphp
                       <li >{{ translate('Customer Phone') }}: {{ $user_info->phone}}</li>
                       <li>{{ translate('Address') }}: {{ $user_info->address}},{{ $user_info->city}},{{ $user_info->state}}-{{ $user_info->postal_code}}</li> 
                   @else
                    <li >{{ translate('Customer Phone') }}: {{ $order->user->phone}}</li>
                    <li >{{ translate('Address') }}: @php if(!empty($user_info[0]->address_info)){echo $user_info[0]->address_info->address;} @endphp</li>
                  @endif
              </ul>
            </div>
        </div>


        <div class="d-flex m-0 p-0" style="margin-bottom: 3px">
            <div class="pt-2" style="margin-left: 0" >
                <ul style="list-style-type:none; margin:0;padding-left:0">
                    <li><b>Order Created</b></li>
                    <li >Date: {{$order->orderDetails['0']->created_at->format('d-m-Y')}}</li>
                    <li>Time: {{$order->orderDetails['0']->created_at->format('h:i:s')}}</li>
                </ul>
            </div>
        </div>


        <table class="table table-bordered">
            <thead>
                <tr >
                    <th class="table_border font-weight-bold text-center" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>SL</nobr></th>
                    <th class="table_border font-weight-bold text-center" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px">Product Name</th>
                    <th class="table_border font-weight-bold text-center" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>Serial Number</nobr></th>
                    <th class="table_border font-weight-bold text-center" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>QTY</nobr></th>
                    <th class="table_border font-weight-bold text-center" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>Price</nobr></th>
                    <th class="table_border font-weight-bold text-center" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>Total(BDT)</nobr></th>                       
                </tr>
            </thead>
            <tbody>
                @php
                    $total_return_product_value = 0;
                    foreach ($return_product_data as $key => $return_product_item) {
                        $total_return_product_value +=  floatval($return_product_item->price) * (int) $return_product_item->product_return_qty;
                    }
                @endphp
                @foreach ($return_product_data as $key => $orderDetail)
                        <tr>
                            <td class="text-center">{{$key + 1}}</td>
                            <td class="text-center"  style="padding-bottom:0px;padding-top:0px;text-align:center">
                                <span style="display: block; font-size:18px">{{getProductName($orderDetail->product_id) }}</span>
                            </td>
                            <td class="text-center">
                                <?php 
                                    $si_no = json_decode($orderDetail->serial_number);
                                    $total_products_count =  count($si_no);
                                    if($total_products_count <62 && $total_products_count > 46){
                                        $total_products_count = 62; 
                                    }
                                    for($i = 0; $i < $total_products_count; $i++ ){
                                        if(array_key_exists($i, $si_no)){
                                            echo $si_no[$i].','."<br>";
                                            
                                        }else{
                                            echo ''."<br>";
                                        }
                                       
                                    }
                                ?>
                            </td>
                            <td class="text-center"> {{ $orderDetail->product_return_qty }}</td>
                            <td class="text-center"> {{ $orderDetail->price}}</td>
                            <td class="text-center">{{ format_price($orderDetail->price * $orderDetail->product_return_qty) }}</td>
                        </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: right">Total Return Product Price</td>
                    <td class="text-center">{{format_price($total_return_product_value)}}</td>
                </tr>
            </tbody>
          </table>


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