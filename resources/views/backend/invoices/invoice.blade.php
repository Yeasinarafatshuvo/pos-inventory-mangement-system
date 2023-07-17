<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ translate('INVOICE') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <style media="all">
        @page {
            padding: 0;
            margin: 20px;
        }

        body {
            font-size: 0.75rem;
            font-family: '<?php echo $font_family; ?>';
            font-weight: normal;
            direction: <?php echo $direction; ?>;
            text-align: <?php echo $default_text_align; ?>;
            padding: 0;
            margin: 0;
            color: #232323;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
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


        #customers td, #customers th {
        border: 1px solid #ddd;
        padding: 8px;
        }
        #customers tr{
            border: 1px solid #ddd;
        }

        #customers th{background-color: #f2f2f2;}


        #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        font-weight: bold
        }
       

    </style>
</head>

<body>
    <div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th width="50%"></th>
                        <th width="50%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="margin: 0px"> 
                                            @if (get_setting('invoice_logo') !== null)
                                                <img src="{{ uploaded_asset(get_setting('invoice_logo')) }}"
                                                    height="30" style="display:inline-block;margin-bottom:10px">
                                            @else
                                                <img src="{{ static_asset('assets/img/logo.png') }}" height="30"
                                                    style="display:inline-block;margin-bottom:10px">
                                            @endif
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="text-right">
                                <tbody>
                                    <tr>
                                        <td class="">{{ get_setting('site_name') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ get_setting('invoice_address') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ get_setting('invoice_email') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Phone') }}:
                                             {{ get_setting('invoice_phone') }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                    </tr>
                                   <tr>
                                    @if ($order->is_pos == 1)
                                        @if (!empty($order->created_by))
                                            <td class="">{{ translate('Prepared By') }}:
                                                {{getUserSoldPersonName($order->created_by)}}
                                            </td>
                                        @endif
                                    @else
                                        <td class="">{{ translate('Online Order') }}</td>
                                    @endif
                                   </tr>
                                    <tr>
                                        <td class="">{{ translate(' Invoice Number') }}:
                                            {{$order->combined_order->code}}</td>
                                    </tr>
                                    <tr>
                                        <td class="">{{ translate('Total Order') }}:
                                            {{$total_user_order -1 }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin:8px 8px 0px 8px; clear:both">
            <div style="padding:10px 14px; border:1px solid #DEDEDE;border-radius:3px;float:left;">
                <table class="">
                    <tbody>
                    
                        <tr>
                            <td class="">{{ translate('Customer Name') }}: {{ $order->user->name}}</td>
                        </tr>
                        @if($order->billing_address !== null  && !empty($order->billing_address))
                            @php
                                $user_info = json_decode($order->billing_address);
                            @endphp
                                <tr>
                                    <td class="">{{ translate('Customer Phone') }}:
                                        {{ $order->user->phone}}</td>
                                </tr>
                                <tr>
                                    <td class="">{{ translate('Address') }}:
                                        {{ $user_info->address}},{{ $user_info->city}},{{ $user_info->state}}-{{ $user_info->postal_code}}</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="">{{ translate('Customer Phone') }}:
                                    {{ $order->user->phone}}</td>
                                    <td class="">{{ translate('Address') }}: @php if(!empty($user_info[0]->address_info)){echo $user_info[0]->address_info->address;} @endphp</td>
                                </tr>
                            @endif
                    </tbody>
                </table>
            </div>          
        </div>
        <div class="d-flex m-0 p-0" style="margin-bottom: 0px; margin-left:8px; margin-top:0px;">
            <div class="pt-2" style="margin-left: 0" >
                <ul style="list-style-type:none; margin:0;padding-left:0">
                    <li><b>Order Created</b></li>
                    <li >Date: {{$order->orderDetails['0']->created_at->format('d-m-Y')}}</li>
                    <li>Time: {{$order->orderDetails['0']->created_at->format('h:i:s')}}</li>
                </ul>
            </div>

            <div style="margin-left: auto" class="pt-2">
                <!--<ul style="list-style-type:none">-->
                <!--    <li><b>Preferred delivery</b></li>-->
                <!--    <li >Date: {{date('d-m-Y')}}</li>-->
                <!--    <li >Time: {{date('h:i:s')}}</li>-->
                <!--</ul>-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 ">
            <table class="table"  id="customers">
                <thead>
                    <tr >
                        <th class=" font-weight-bold text-center"><nobr>SL</nobr></th>
                        <th class=" font-weight-bold text-center">Product Name</th>
                        <th class=" font-weight-bold text-center"><nobr>Serial Number</nobr></th>
                        <th class=" font-weight-bold text-center"><nobr>QTY</nobr></th>
                        <th class=" font-weight-bold text-center"><nobr>Price</nobr></th>
                        <th class=" font-weight-bold text-center"><nobr>Discount Price</nobr></th>
                        <th class=" font-weight-bold text-center"><nobr>Total(BDT)</nobr></th>                       
                    </tr>
                </thead>
                    <tbody>
                        @foreach ($order->orderDetails as $key => $orderDetail)
                            @if ($orderDetail->product != null)
                                <tr>
                                    <td >{{ $key + 1 }}</td>
                                    <td>
                                        <span>{{ $orderDetail->product->name }}<?php echo '<br>' ?>{{$orderDetail->product->product_warranty != null ? '('.('Warranty:' .$orderDetail->product->product_warranty). ')':''}}</span>
                                        @if ($orderDetail->variation && $orderDetail->variation->combinations->count() > 0)
                                            @foreach ($orderDetail->variation->combinations as $combination)
                                                <span style="margin-right:10px">
                                                    <span class="">{{ $combination->attribute->getTranslation('name') }}</span>:
                                                    <span>{{ $combination->attribute_value->getTranslation('name') }}</span>
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-center" >
                                        <?php 
                                            $i=1;$si_no = json_decode($orderDetail->prod_serial_num);
                                            $total_serial_number = count(json_decode($orderDetail->prod_serial_num));
                                            for($i = 0; $i < count($si_no); $i++ ){
                                                echo $si_no[$i].','."<br>";
                                            }
                                        ?>
                                    </td>
                                    <td class="text-center" >
                                        {{ $orderDetail->quantity }}</td>
                                    <td class="text-center " >
                                        {{ (!empty($orderDetail->actual_price))? format_price($orderDetail->actual_price) : format_price($order->orderDetails[$key]->product->highest_price)}}</td>
                                    <td class="text-center " >
                                            {{($order->orderDetails[$key]->product->highest_price > $orderDetail->price) ? format_price($orderDetail->price): ''}}</td>
                                    <td class="text-center bold ">
                                        {{ format_price($orderDetail->price * $orderDetail->quantity) }}</td>
                                </tr>
                            @endif
                        @endforeach
                        @endforeach
                        @php
                            $subtotal_with_discount = 0;
                                foreach ($order->orderDetails as $key => $item) {
                                    $subtotal_with_discount += $item->price * $item->quantity;
                                } 
                        @endphp
                        <tr>
                            <td colspan="6" class="text-right bold table_border">With Discount Sub Total</td>
                            <td class="bold table_border">{{$subtotal_with_discount}}</td>
                        </tr>
                    </tbody>
            </table>
            </div>
        </div>
        <div>

        </div>
        <table>
            <tr>
                <th style="width: 50px"></th>
                <th>
                @if ($order->payment_status == 'paid')
                    <div class="mt-5">
                        <img src="{{ static_asset('assets/img/paid_sticker.svg') }}">
                    </div>
                @elseif($order->payment_type == 'cash_on_delivery')
                    <div class="mt-5">
                        <img src="{{ static_asset('assets/img/cod_sticker.svg') }}">
                    </div>
                @endif
                </th>
                <th style="width: 150px"></th>
                
                <th style="width: 250px">
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
                            
                            <tr style="border-bottom:1px solid #DEDEDE;">
                                <td class="text-left" style="font-size:13px;margin-bottom:0px;line-height: 1px;">
                                    <span > {{ translate('Without Discount Sub Total') }}</span>
                                </td>
                                <td class="bold" style="margin-bottom:0px;line-height: 1px;font-size:16px;">
                                    <span>{{ format_price($subtotal) }}</span>
                                </td>
                            
                            </tr>
                            <tr style="border-bottom:1px solid #DEDEDE;">
                                
                                <td class="text-left" style="font-size:15px;margin-bottom:0px;line-height: 1px">
                                    {{ translate('Total Tax') }}
                                </td>
                                <td class="bold" style="margin-bottom:0px;line-height: 1px;font-size:15px">
                                    {{ format_price( $order->orderDetails['0']->tax) }}
                                </td>
                            </tr>
                            <tr style="border-bottom:1px solid #DEDEDE;">
                                <td class="text-left" style="font-size:16px;margin-bottom:0px;line-height: 1px">
                                    {{ translate('Shipping Cost') }}
                                </td>
                                <td class="bold" style="margin-bottom:0px;line-height: 1px;font-size:15px">
                                    {{ format_price($order->shipping_cost) }}
                                </td>
                            </tr>
                            <tr style="border-bottom:1px solid #DEDEDE;">
                                <td class="text-left" style="font-size:16px;margin-bottom:0px;line-height: 1px">
                                    {{ translate('Discount') }}</td>
                                <td class="bold" style="margin-bottom:0px;line-height: 1px;font-size:15px">
                                {{($order->is_pos==1) ? format_price(($subtotal - ($order->grand_total-$order->orderDetails['0']->tax-$order->shipping_cost))) : format_price(($subtotal - $order->grand_total)+$order->shipping_cost)}}
                                </td>
                            </tr>
                            <tr style="border-bottom:1px solid #DEDEDE;">
                                <td class="text-left" style="font-size:16px;margin-bottom:0px;line-height: 1px">
                                    {{ translate('Coupon Discount') }}
                                </td>
                                <td class="bold" style="margin-bottom:0px;line-height: 1px;font-size:15px">
                                    {{ format_price($order->coupon_discount) }}
                                </td>
                            </tr>
                            <tr style="border-bottom:1px solid #DEDEDE;">
                                <td class="text-left" style="font-size:16px;margin-bottom:0px;line-height: 1px">
                                    {{ translate('Special Discount') }}
                                </td>
                                <td class="bold" style="margin-bottom:0px;line-height: 1px;font-size:15px">
                                    {{ format_price($order->special_discount) }}
                                </td>
                            </tr>
                            <tr style="border-bottom:1px solid #DEDEDE;">
                                <td class="text-left" style="font-size:16px;margin-bottom:0px;line-height: 1px">
                                    {{ translate('Advance Payment') }}
                                </td>
                                <td class="bold" style="margin-bottom:0px;line-height: 1px;font-size:15px">
                                    {{$order->advance_payment == null? format_price(0): format_price($order->advance_payment)}}
                                </td>
                            </tr>
                            <tr style="border-bottom:1px solid #DEDEDE;">
                                <td class="text-left bold" style="font-size:16px;margin-bottom:0px;line-height: 1px">{{ translate('Cash To Collect') }}</td>
                                <td class="bold" style="line-height: 1px;font-size:15px">
                                    {{ format_price(($order->grand_total - $order->special_discount) - ($order->advance_payment == null? 0: $order->advance_payment)) }}
                                </td>
                            </tr>
                            <tr style="border-bottom:1px solid #DEDEDE;">
                                <td class="text-left bold" style="font-size:16px;margin-bottom:0px;line-height: 1px">{{ translate('Discount%') }}</td>
                                <td class="bold" style="margin: 0line-height: 1px;line-height: 1px;font-size:15px">
                                    {{ round($total_discount,2)}}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                   
                </th>
            </tr>
        </table>
        
        

       
    </div>
    @if ($total_serial_number > 31 && $total_serial_number <=99)
    <footer class="footer" style="position: absolute;left: 0px;top:2030px; width: 100%;text-align: center;">
        <p style="border-top: 1px solid black; padding-bottom:0px; margin-bottom:0px; text-align:justify;font-size:10px;line-height:15px">
            Thank you for ordering from Maakview. We offer a 7-day return/refund policy for specific product only and a 1-day return/refund policy for non-warranty/guaranty product. If you have any complaints about this order, please call us at 01888-01 2727 or email us at support@maakview.com. 
        </p>
        <p style="padding-bottom: 0px;margin-bottom:0px; text-align:justify;font-size:10px;line-height:15px">
            *Total is inclusive of VAT (Calculated as per 6.3/Mushak/2021).This is a system generated invoice and no signature or seal is required.
        </p>
        <p style="margin-bottom: 0px; padding-bottom:0px; text-align:justify;font-size:10px;line-height:15px">
            *The warranty will be applicable as specified in the website's product description section.
        </p>
    </footer>
    @elseif ($total_serial_number >= 100)
    <footer class="footer" style="position: absolute;left: 0px;top:3075px; width: 100%;text-align: center;">
        <p style="border-top: 1px solid black; padding-bottom:0px; margin-bottom:0px; text-align:justify;font-size:10px;line-height:15px">
            Thank you for ordering from Maakview. We offer a 7-day return/refund policy for specific product only and a 1-day return/refund policy for non-warranty/guaranty product. If you have any complaints about this order, please call us at 01888-01 2727 or email us at support@maakview.com. 
        </p>
        <p style="padding-bottom: 0px;margin-bottom:0px; text-align:justify;font-size:10px;line-height:15px">
            *Total is inclusive of VAT (Calculated as per 6.3/Mushak/2021).This is a system generated invoice and no signature or seal is required.
        </p>
        <p style="margin-bottom: 0px; padding-bottom:0px; text-align:justify;font-size:10px;line-height:15px">
            *The warranty will be applicable as specified in the website's product description section.
        </p>
    </footer>
    @else
    <footer class="footer" style="display: block; position: fixed; left: 0;bottom: 0; width: 100%;text-align: center;">
        <p style="border-top: 1px solid black; padding-bottom:0px; margin-bottom:0px; text-align:justify;font-size:10px;line-height:15px">
            Thank you for ordering from Maakview. We offer a 7-day return/refund policy for specific product only and a 1-day return/refund policy for non-warranty/guaranty product. If you have any complaints about this order, please call us at 01888-01 2727 or email us at support@maakview.com. 
        </p>
        <p style="padding-bottom: 0px;margin-bottom:0px; text-align:justify;font-size:10px;line-height:15px">
            *Total is inclusive of VAT (Calculated as per 6.3/Mushak/2021).This is a system generated invoice and no signature or seal is required.
        </p>
        <p style="margin-bottom: 0px; padding-bottom:0px; text-align:justify;font-size:10px;line-height:15px">
            *The warranty will be applicable as specified in the website's product description section.
        </p>
    </footer>
    @endif
</body>

</html>
