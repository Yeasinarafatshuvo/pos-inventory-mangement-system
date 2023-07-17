@extends('backend.layouts.app')
@section('content')
<style>
   
</style>
<div id="print_area">
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h3 class="bg-primary  text-center" style="color:white;">WASTAGE PRODUCT DETAILS </h3>
            </div>
        </div>
    </div>
    <div class="logo" style="display: flex;justify-content: center;align-items: center;">
        <img class="m-0 p-0" src="{{asset('logo')}}/maakview.png" width="200" height="50" alt="Logo"> 
    </div>
    <div class="card">
        <div class="card-body"> 
        <div class="row">
        <div class="col-md-12 pr-3">
            <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th scope="col">PRODUCT NAME</th>
                    <th scope="col">QTY</th>
                    <th scope="col">SERIAL</th>
                    <th scope="col">DATE</th>
                    <th scope="col">CREATED BY</th>
                  </tr>
                </thead>
                <tbody>
                 <tr>
                    <td>{{$single_wastage_product_info->products->name}}</td>
                    <td>{{$single_wastage_product_info->product_wastage_qty}}</td>
                    <td>
                        @php
                            $serial_arr_values = json_decode($single_wastage_product_info->serial_number);
                            foreach ($serial_arr_values as $key => $wastage_serial_details) {
                               echo $wastage_serial_details .'<br>';
                            }
                        @endphp
                    </td>
                    <td>{{$single_wastage_product_info->created_at->format('Y-m-d')}}</td>
                    <td>{{$single_wastage_product_info->users->name}}</td>
                  </tr>
                </tbody>
              </table>
        </div>
        </div>
        </div>
    </div>
</div>
<div class="text-center">
    <button class="btn btn-primary btn-sm" onclick="printwastage_page('print_area')">print</button>
</div>

@endsection




@section('script')
<script type="text/javascript">
function printwastage_page(print_area){

    var css = '@page { size: portrait; }',
    head = document.head || document.getElementsByTagName('head')[0],
    style = document.createElement('style');

    style.type = 'text/css';
    style.media = 'print';

    if (style.styleSheet){
        style.styleSheet.cssText = css;
    } else {
        style.appendChild(document.createTextNode(css));
    }
    head.appendChild(style);
    var printContents = document.getElementById(print_area).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;

}



</script>

@endsection
