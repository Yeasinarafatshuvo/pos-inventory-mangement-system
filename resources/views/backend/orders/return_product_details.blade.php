@extends('backend.layouts.app')
@section('content')
<style>
    .customer_border{
        border: 1px dotted #000000;
        padding-top: 5px;
        margin-bottom: 0px;
    }
    .customer_information li{
        list-style-type: none;
    }
</style>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">RETURN PRODUCT LIST DETAILS</h2>
            </div>
        </div>
    </div>
    <div id="print_area" >
    <div class="row">
        <div class="col-md-12 ">
            <table class="table p-0 m-0">
                <tbody>
                    <tr>
                        <td class="m-0">
                            <img class="m-0 p-0" src="{{asset('logo')}}/maakview.png" height="50" alt="Logo"> 
                        </td>
                        <td class="ml-0">
                            <p class="office_address text-right" style="font-size: 15px">
                                www.maakview.com <br>
                                Product Return Date: {{$return_product_details[0]->created_at->format('d-m-Y')}} -
                                Order Invoice: {{$return_product_details[0]->invoice_number}}
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
              @endif
          </ul>
        </div>
    </div>
    <div class="card">
     
        <div class="card-body"> 
        <div class="row">
        <div class="col-md-12 pr-3">
            <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th scope="col">SL</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Quantity</th>
                    <th scope="col">Product Serial Number</th>
                  </tr>
                </thead>
                <tbody>
                 @foreach ($return_product_details as $key => $return_item)
                 <tr>
                    <th scope="row">{{$key +1}}</th>
                    <td>{{$return_item->product->name}}</td>
                    <td>{{$return_item->product_return_qty}}</td>
                    <td>
                        <?php
                            $i = 1; 
                            $si_no = json_decode($return_item->serial_number);
                            foreach ($si_no as $key => $si_id) {
                                echo $si_id.','."<br>";
                            }     
                        ?>
                    </td>
                    

                  </tr>
                 @endforeach
                </tbody>
              </table>
        </div>
        </div>
        </div>
    </div>
</div>
<div class="text-center" id="button_id">
    <button class="btn btn-primary btn-sm" onclick="printpage('print_area')">print</button>
</div>

@endsection




@section('script')
<script type="text/javascript">
function printpage(print_area){

var printContents = document.getElementById(print_area).innerHTML;
var originalContents = document.body.innerHTML;

document.body.innerHTML = printContents;

window.print();

document.body.innerHTML = originalContents;

}


</script>
@endsection
