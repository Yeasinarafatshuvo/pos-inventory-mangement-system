@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/> 
@section('content')
<style>

</style>
<div class="container">
    <h2 class="bg-primary text-white">Purchase  Return Information</h2>
    <p style="font-size: 18px; text-align:center;border: 1px dotted;">Supplier Name: <span>{{getSupplierNameByPurchaseId($single_purchase_details[0]->purchase_invoices)}}</span></p>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
    
                <th>Purchase Invoices</th>
                <th>Product ID</th>
                <th>Product Price</th>
                <th>Purchase Return Product Serial</th>
                <th>Purchase Return Date</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($single_purchase_details as $item)
            <tr>
               <td>{{$item->purchase_invoices}}</td>
               <td>{{getProductName($item->product_id)}}</td>
               <td>{{$item->product_price}}</td>
               @php
                   
               @endphp
               <td>
                @php
                    $serial_aray = json_decode($item->purchas_retrn_prod_serial);
                
                    foreach ($serial_aray as $key => $value) {
                        echo $value.','."<br>";
                    }
                
                @endphp
                </td>
                <td>{{date('Y-m-d',strtotime($item->created_at))}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection




@section('script')
<script type="text/javascript">




</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
