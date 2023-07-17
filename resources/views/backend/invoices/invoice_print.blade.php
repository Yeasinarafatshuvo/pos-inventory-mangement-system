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

    <div class="container">
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
                                   @if (!empty($order->orderDetails['0']->sold_by))
                                    Sold By: {{getUserSoldPersonName($order->orderDetails['0']->sold_by)}}<br>
                                    @endif
                                    @if ($order->is_pos == 1)
                                        @if (!empty($order->created_by))
                                        Prepared By: {{getUserSoldPersonName($order->created_by)}}<br>
                                        @endif 
                                    @else
                                        Online Order<br>
                                    @endif
                                    Approved By: Mr.Motiur Rahman<br>
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
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center"><nobr>SL</nobr></th>
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center">Product Name</th>
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center"><nobr>Serial Number</nobr></th>
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center"><nobr>QTY</nobr></th>
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center"><nobr>Price</nobr></th>
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center"><nobr>Discount Price</nobr></th>
                    <th class=" font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px;text-align:center"><nobr>Total(BDT)</nobr></th>                       
                </tr>
            </thead>
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
                            <td class="text-center">
                                <?php 
                                    $si_no = json_decode($orderDetail->prod_serial_num);
                                    $total_products_count =  count($si_no);
                                    if($total_products_count <62 && $total_products_count > 44){
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
                            <td> {{ $orderDetail->quantity }}</td>
                            <td> {{ $orderDetail->price}}</td>
                            <td class="text-center ">{{($order->orderDetails[$key]->product->highest_price > $orderDetail->price) ? format_price($orderDetail->price): ''}}</td>
                            <td class="text-center bold ">{{ format_price($orderDetail->price * $orderDetail->quantity) }}</td>

                        </tr>
                    @endif
                @endforeach
            </tbody>
          </table>
  

    <div style="margin-top:0px;">
        <div style="float: left; margin-left:0; padding-left:0">
            @if ($order->payment_status == 'paid')
                <div class="mt-5">
                    <img src="{{ static_asset('assets/img/paid_sticker.svg') }}">
                </div>
            @elseif($order->payment_type == 'cash_on_delivery')
                <div class="mt-5">
                    <img src="{{ static_asset('assets/img/cod_sticker.svg') }}">
                </div>
            @endif
        </div>
        {{-- <div style="width: 27%">

        </div> --}}
        <div style="float: right;padding:10px 5px;margin-top:0px; ">
            <table class="text-right sm-padding" style="border-collapse:collapse">
                <tbody>
             
                    @php
                        $subtotal = 0;
                            foreach ($order->orderDetails as $key => $item) {
                                if(empty($item->actual_price)){
                                    $subtotal += $order->orderDetails[$key]->product->highest_price * $item->quantity;
                                }else{
                                    $subtotal += $item->actual_price * $item->quantity;
                                }
                            } 

                        if($order->is_pos==1){
                            $offer_discount = $subtotal - ($order->grand_total-$order->orderDetails['0']->tax);
                        } else{
                            $offer_discount = ($subtotal - $order->grand_total);
                        }
                        
                        $total_discount =  100 - (($subtotal-$order->shipping_cost-$order->special_discount-$offer_discount) * 100) / $subtotal;  
                        
                    @endphp
                    <tr>
                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8;font-size:13px;line-height: 1px; margin-bottom:0px">
                            {{ translate('Without Discount Sub Total') }}
                        </td>
                        <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ format_price($subtotal) }}
                        </td>
                    
                    </tr>
                    <tr class="">
                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ translate('Total Tax') }}
                        </td>
                        <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ format_price( $order->orderDetails['0']->tax) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ translate('Shipping Cost') }}
                        </td>
                        <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ format_price($order->shipping_cost) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="border-bottom:1px dotted #B8B8B8;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ translate('Discount') }}</td>
                        <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:18px;line-height: 1px; margin-bottom:0px">
                        {{($order->is_pos==1) ? format_price(($subtotal - ($order->grand_total-$order->orderDetails['0']->tax-$order->shipping_cost))) : format_price(($subtotal - $order->grand_total)+$order->shipping_cost)}}
                        </td>
                    </tr>
                    <tr class="">
                        <td class="text-left" style="border-bottom:1px solid #DEDEDE;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ translate('Coupon Discount') }}
                        </td>
                        <td class="bold" style="border-bottom:1px solid #DEDEDE;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ format_price($order->coupon_discount) }}
                        </td>
                    </tr>
                    <tr class="">
                        <td class="text-left" style="border-bottom:1px solid #DEDEDE;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ translate('Special Discount') }}
                        </td>
                        <td class="bold" style="border-bottom:1px solid #DEDEDE;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ format_price($order->special_discount) }}
                        </td>
                    </tr>
                    <tr class="">
                        <td class="text-left" style="border-bottom:1px solid #DEDEDE;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ translate('Advance Payment') }}
                        </td>
                        <td class="bold" style="border-bottom:1px solid #DEDEDE;font-size:18px;line-height: 1px; margin-bottom:0px">
                           {{$order->advance_payment == null? format_price(0): format_price($order->advance_payment)}}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left bold" style="border-bottom:1px solid #DEDEDE;font-size:18px;line-height: 1px; margin-bottom:0px">{{ translate('Cash To Collect') }}</td>
                        <td class="bold" style="border-bottom:1px solid #DEDEDE;font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ format_price(($order->grand_total - $order->special_discount) - ($order->advance_payment == null? 0: $order->advance_payment)) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left bold" style="font-size:18px;line-height: 1px; margin-bottom:0px">{{ translate('Discount%') }}</td>
                        <td class="bold" style="font-size:18px;line-height: 1px; margin-bottom:0px">
                            {{ round($total_discount,2)}}%
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  
    <footer class="footer" style="  width: 100%;text-align: center;">
        <p style="border-top: 1px solid black; padding-bottom:0px;padding-top:10px; margin-bottom:0px; text-align:justify;font-size:15px;line-height:18px">
            Thank you for ordering from Maakview. We offer a 7-day return/refund policy for specific product only and a 1-day return/refund policy for non-warranty/guaranty product. If you have any complaints about this order, please call us at 01888-01 2727 or email us at support@maakview.com. 
        </p>
        <p style="padding-bottom: 0px;margin-bottom:0px; text-align:justify;font-size:15px;line-height:18px">
            *Total is inclusive of VAT (Calculated as per 6.3/Mushak/2021).This is a system generated invoice and no signature or seal is required.
        </p>
        <p style="margin-bottom: 0px; padding-bottom:0px; text-align:justify;font-size:15px;line-height:18px">
            *The warranty will be applicable as specified in the website's product description section.
        </p>
    </footer>
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