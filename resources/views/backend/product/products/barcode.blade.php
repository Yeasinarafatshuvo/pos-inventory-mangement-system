@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="mb-0 h6 ml-3">{{ translate('Generate Barcode') }}</h5>
</div>
<div class="container-fluid">
    <form action="{{route('product.create_barcode')}}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
                        {{$_product_details->name}}
                        <input type="hidden" name="product_id" value="{{$_product_details->id}}">
                    </th>
                    <th>
                        <input type="text" class="form-control" name="product_qty" placeholder="Enter Qty" value="" >
                    </th>
                    <th>
                        <button type="submit" class="btn btn-primary">Generate Barcode</button>
                    </th>
                </tr>
            </thead>
        </table>
    </form>

</div>
@endsection
