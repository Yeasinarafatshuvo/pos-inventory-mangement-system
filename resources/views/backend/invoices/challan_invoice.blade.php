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

        .text-left {
            text-align: <?php echo $default_text_align; ?>;
        }

        .text-right {
            text-align: <?php echo $reverse_text_align; ?>;
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
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="width:100%; margin-top:0;margin-bottom: 3px">
            <div class="customer_border mb-0 mt-0 pb-0  pt-0 d-flex justify-content-between">
              <ul class="customer_information mb-0 mt-0  pb-0  pt-1 pb-1" style="margin-left: 0px;padding-left: 5px">
                  <li>Invoice Number : {{$order->combined_order->code}}</li>
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
              <div class="pt-1 pb-1 " style="margin-left: 0; padding-right: 5px" >
                  <ul style="list-style-type:none; margin:0;padding-left:0">
                      <li >Date: {{$order->orderDetails['0']->created_at->format('d-m-Y')}}</li>
                      @if (!empty($order->orderDetails['0']->sold_by))
                      <li>Sold By: {{getUserSoldPersonName($order->orderDetails['0']->sold_by)}}</li>
                      @endif
                      @if ($order->is_pos == 1)
                          @if (!empty($order->created_by))
                          <li>Prepared By: {{getUserSoldPersonName($order->created_by)}}</li>
                          @endif 
                      @else
                          Online Order
                      @endif
                      <li>Approved By: Mr.Motiur Rahman</li>
                  </ul>
              </div>
            </div>
            <h2 class="text-center" style="text-decoration: underline;">CHALLAN</h2>
          </div>

        <table class="table table-bordered">
            <thead>
                <tr >
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center"><nobr>SL</nobr></th>
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center">Product Name</th>
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center"><nobr>Serial Number</nobr></th>
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center"><nobr>QTY</nobr></th>                  
                </tr>
            </thead>
            @php
            $total_quantity_product_qty = 0;
                foreach ($order->orderDetails as $key => $quantity_value) {
                   $total_quantity_product_qty += $quantity_value->quantity;
                }
            @endphp
            <tbody>
                @foreach ($order->orderDetails as $key => $orderDetail)
                    @if ($orderDetail->product != null)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td  style="padding-bottom:0px;padding-top:0px;text-align:center">
                                <span style="display: block; font-size:18px">{{ $orderDetail->product->name }} <?php echo '<br>' ?>{{$orderDetail->product->product_warranty != null ? '('.('Warranty:' .$orderDetail->product->product_warranty). ')':''}}</span>
                                @if ($orderDetail->variation && $orderDetail->variation->combinations->count() > 0)
                                    @foreach ($orderDetail->variation->combinations as $combination)
                                        <span style="margin-right:10px">
                                            <span class="">{{ $combination->attribute->getTranslation('name') }}</span>:
                                            <span>{{ $combination->attribute_value->getTranslation('name') }}</span>
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                            <td style="text-align:center">
                                <?php 
                                    $i=1;$si_no = json_decode($orderDetail->prod_serial_num);
                                    $total_serial_number = count(json_decode($orderDetail->prod_serial_num));
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
                            <td class="text-center"> {{ $orderDetail->quantity }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td colspan="3" class="text-right  text-bold" style="font-size: 14px">TOTAL QUANTITY</td>
                    <td class="text-center table_border">{{$total_quantity_product_qty}}</td>
                </tr>
            </tbody>
          </table>
    </div>

    <footer class="footer" style=" width: 100%;text-align: center;">
        <div class="mb-0 mt-0 pb-0  pt-0 d-flex justify-content-between">
            <ul class="mb-2" style="margin-left: 0px;padding-left: 5px">
                <li>..........................................</li>
                <li>Customer Signatures</li>
            </ul>
            <div class="mb-2" style="margin-left: 0" >
                <ul style="list-style-type:none; margin:0;padding-left:0">
                    <li>.....................................</li>
                    <li>For Maakview</li>
                </ul>
            </div>
        </div>
        <div class="mb-0 ml-0 mt-0 pb-0  pt-0 d-flex justify-content-between" style="background: #553eda;">
            <ul  style="list-style-type:none; padding-top:10px; margin:0; padding-left:10;">
                <li class="text-white">82/3 Laboratory Road, Dhaka-1205.</li>
            </ul>
            <div class="pt-2" >
                <ul style="list-style-type:none; ">
                    <li class="text-white">PHONE: +8801888-012727</li>
                </ul>
            </div>
            <div class="pt-2  mr-1" >
                <ul style="list-style-type:none;padding-right:10px; ">
                    <li class="text-white">EMAIL: maakview.info@gmail.com</li>
                </ul>
            </div>
        </div>
    </footer>

    

    











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