@extends('backend.layouts.app')
@section('content')
<style>




</style>
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center bg-primary text-white">Product Purchase and Sale Report By Barcode </h3>
            </div>
        </div>
        @if (session()->has('not_purchase'))
            <div class=" notification alert alert-danger col-md-12 text-center">
                {{ session('not_purchase') }}
            </div>
        @endif
        
        <form action="{{route('report_by_barocde.match_serial')}}" method="POST">
            @csrf
            <div class="row pl-5 ml-2">
               
                    <div class="col-md-10" style="margin-right:0; padding-right:0; padding-left:0">
                        <input id="select_product" type="text" name="serial_number" class="form-control" placeholder="Enter your barcode"  value="">
                    </div>
                
                    <div class="col-md-2" style="padding-left: 5px">
                        <button class="btn btn-success" id="get_result_by_submit">Search</button>
                    </div>
            </div>
        </form>
    </div>
    
@endsection




@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript">




removeNotification();
function removeNotification(){
  setTimeout(() => {
    $('.notification').remove();
  }, 2000);
}









</script>
@endsection
