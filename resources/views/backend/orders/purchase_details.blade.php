@extends('backend.layouts.app')
@section('content')
<style>
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
    .table_border{
        border: 1px solid black !important;
    }
    

    .customer_border{
        border: 1px dotted #000000;
        padding-top: 5px;
        margin-bottom: 0px;
    }
    .customer_information li{
        list-style-type: none;
    }

    .submt_adv_pay{
        padding-top: 11px;
        padding-bottom: 11px;
        padding-right: 2px;
        padding-left: 2px;
        border-left-width: 0px;
        border-right-width: 0px;
        border-top-width: 0px;
        border-bottom-width: 0px;
        background-color: #6c50e1;
        border-radius: 5px;
        font-weight: bold;
        color: #ffffff;
    }
</style>
    <div class="container" style="display: block" >
        <div class="p-2">
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
                                       <span style="display: block; padding-bottom:10px"> 82/3 Laboratorry Road, Dhaka-1205 <br>Phone: 01888-012727</span>
                                        @if (!empty($purchase_order_details->created_by))
                                        Purchase By: {{getUserSoldPersonName($purchase_order_details->created_by)}}<br>
                                        @endif
                                        Invoice Number :{{$purchase_order_details->invoice_numbers}} <br>
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
                    <li>{{ translate('Supplier Name') }}: {{ $supplier_info->name}}</li>
                    <li>{{ translate('Supplier Address') }}: {{ $supplier_info->address}}</li>
                    <li >{{ translate('Supplier Phone') }}: {{ $supplier_info->phone}}</li>
                      
                  </ul>
                </div>
            </div>
            <div class="d-flex m-0 p-0" style="margin-bottom: 3px">
                <div class="pt-2" style="margin-left: 0" >
                    <ul style="list-style-type:none; margin:0;padding-left:0">
                        <li><b>Purchase Created</b></li>
                        <li >Date: {{$purchase_order_details->created_at->format('d-m-Y')}}</li>
                        <li>Time: {{$purchase_order_details->created_at->format('h:i:s')}}</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ">
                <table class="table table-bordered text-center ">
                    <thead class="table_head">
                        <tr >
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>SL</nobr></th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px">Product Name</th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>Serial Number</nobr></th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>QTY</nobr></th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>Price</nobr></th>
                            <th class="table_border font-weight-bold" style="color: #000000;font-size:15px;padding-bottom:0px;padding-top:0px"><nobr>Total(BDT)</nobr></th>                       
                        </tr>
                    </thead>
                        <tbody>
                            @foreach ($purchase_order_table_details as $key => $purchase_order_details)
                                @if ($purchase_order_details->product_id != null)
                                    <tr>
                                        <td class="table_border" style="border-bottom:1px solid #DEDEDE;font-size:12px;padding-left:20px;padding-bottom:0px;padding-top:0px">{{ $key + 1 }}</td>
                                        <td class="table_border" style="border-bottom:1px solid #DEDEDE;padding-bottom:0px;padding-top:0px">
                                            <span style="display: block; font-size:10px">{{ $purchase_order_details->product->name }}</span>
                                        </td>
                                        <td class="text-center table_border" style="border-bottom:1px solid #DEDEDE; font-size:10px;padding-bottom:0px;padding-top:0px;word-wrap: break-word;"><?php $i=1;$si_no = json_decode($purchase_order_details->serial_numbers);foreach($si_no as $s_no){echo $s_no.','."<br>";} ?></td>
                                        <td class="text-center table_border" style=" border-bottom:1px solid #DEDEDE; font-size:14px;padding-bottom:0px;padding-top:0px">
                                            {{ $purchase_order_details->product_qty }}</td>
                                        <td class="text-center table_border" style="border-bottom:1px solid #DEDEDE; font-size:14px;padding-bottom:0px;padding-top:0px">
                                                {{ format_price($purchase_order_details->purchase_price)}}</td>
                                        <td class="text-center bold table_border" style="border-bottom:1px solid #DEDEDE;padding-right:20px; font-size:12px;padding-bottom:0px;padding-top:0px">
                                            {{ format_price($purchase_order_details->purchase_price * $purchase_order_details->product_qty) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                </table>
                </div>
            </div>
            <div style="margin-top:0px;display:block">
                <div style="float: right; width:43%;padding:10px 5px;margin-top:0px">
                    <table class="text-right sm-padding" style="border-collapse:collapse">
                        <tbody>
                            <tr>
                                <td style="font-size:14px;line-height: 1px; margin-bottom:0px">
                                   <span style="border-bottom:1px dotted #B8B8B8;"> {{ translate('Sub Total') }}</span>
                                </td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                                    {{ format_price($transaction_details->payable) }}
                                </td>
                            </tr>
                            <tr>
                                <td  style="font-size:14px;line-height: 1px; margin-bottom:0px">
                                   <span style="border-bottom:1px dotted #B8B8B8;"> {{ translate('Total Paid') }}</span>
                                </td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                                    {{ format_price($transaction_details->paid) }}
                                </td>
                            </tr>
                            <tr>
                                <td  style="font-size:14px;line-height: 1px; margin-bottom:0px">
                                   <span style="border-bottom:1px dotted #B8B8B8;"> {{ translate('Total Due') }}</span>
                                </td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                                    {{ format_price($transaction_details->due) }}
                                </td>
                            </tr>
                            <tr>
                                <td  style="font-size:14px;line-height: 1px; margin-bottom:0px">
                                   <span style="border-bottom:1px dotted #B8B8B8;"> {{ translate('Payment Type') }}</span>
                                </td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                                    {{ $transaction_details->payment_type }}
                                </td>
                            </tr>
                            @if ($transaction_details->payment_type == 'bkash' ||$transaction_details->payment_type == 'nagad' || $transaction_details->payment_type == 'bank')
                            <tr>
                                <td  style="font-size:14px;line-height: 1px; margin-bottom:0px">
                                    <span style="border-bottom:1px dotted #B8B8B8;">{{ translate('Transaction Number') }}</span>
                                </td>
                                <td class="bold" style="border-bottom:1px dotted #B8B8B8;font-size:14px;line-height: 1px; margin-bottom:0px">
                                    {{ $transaction_details->transaction_id }}
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
@endsection




@section('script')
<script type="text/javascript">



</script>
@endsection
