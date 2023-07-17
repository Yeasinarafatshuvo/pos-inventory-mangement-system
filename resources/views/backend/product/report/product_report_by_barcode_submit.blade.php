@extends('backend.layouts.app')
@section('content')
<style>

</style>
<div id="print_area">
    
        <table class="table table-bordered">
            <thead>
                <th colspan="6" class="text-center pl-0 pr-0" style="font-size: 18px;">
                    <h3 class="bg-primary text-white m-0">Purchase History</h3>
                </th>
            </thead>
            <thead>
                <th class="text-center">Product Name</th>
                <th class="text-center">Product Purchase Price</th>
                <th class="text-center">Purchase Invoice Number</th>
                <th class="text-center">Supplier Name</th>
                <th class="text-center">Product Barcode</th>
                <th class="text-center">Purchase Date</th>
            </thead>
            <tbody>
                <td class="text-center">{{!empty($products_purchase_data['product_id']) ? getProductName($products_purchase_data['product_id']):""}}</td>
                <td class="text-center">{{!empty($products_purchase_data['purchase_price']) ? $products_purchase_data['purchase_price']:""}}</td>
                <td class="text-center">{{!empty($products_purchase_data['invoice_numbers']) ? $products_purchase_data['invoice_numbers']:""}}</td>
                <td class="text-center">{{!empty($products_purchase_data['supplier_id']) ? getSupplierName($products_purchase_data['supplier_id']):""}}</td>
                <td class="text-center">{{!empty($product_barcode_id) ? $product_barcode_id:""}}</td>
                <td class="text-center">{{!empty($products_purchase_data['created_at'])? date_format($products_purchase_data['created_at'],"Y-m-d H:i"):""}}</td>
            </tbody>
            <thead>
                <th colspan="6" class="text-center pl-0 pr-0 " style="font-size: 18px;">
                    <h3 class="bg-success text-white m-0">Sale History</h3>
                </th>
                <thead>
                    <th class="text-center">Sell Invoice Number</th>
                    <th class="text-center">Product Warranty</th>
                    <th class="text-center">Product Sell Price</th>
                    <th class="text-center">Customer Name</th>
                    <th class="text-center">Product Sold By</th>
                    <th class="text-center">Product Sale Date</th>
                </thead>
            </thead>
            <tbody>
                @if (!empty($products_sell_data['order_id']) && $products_sell_data['order_id'] != null)
                    <td class="text-center">{{!empty($products_sell_data['order_id']) ? getOrderInvoice($products_sell_data['order_id']):""}}</td>
                    <td class="text-center">{{!empty($products_sell_data['product_id']) ? getProductWarrantyInfo($products_sell_data['product_id']):""}}</td>
                    <td class="text-center">{{!empty($products_sell_data['product_sell_price']) ? $products_sell_data['product_sell_price']:""}}</td>
                    <td class="text-center">{{!empty($products_sell_data['order_id']) ? getCustomerNameOrderWise($products_sell_data['order_id']):""}}</td>
                    <td class="text-center">{{!empty($products_sell_data['product_sold_by']) ? getProduct_sold_by_name($products_sell_data['product_sold_by']):""}}</td>
                    <td class="text-center">{{!empty($products_sell_data['product_sell_date']) ? date_format($products_sell_data['product_sell_date'],"Y-m-d H:i"):""}}</td>
                @else
                <td class="text-center text-danger" style="font-weight: bold; font-size:15px" colspan="6">Sell Not Found</td>
                @endif
            </tbody>
            
        </table>
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
