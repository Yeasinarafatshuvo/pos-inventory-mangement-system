@extends('backend.layouts.app')
@section('content')
<style>



</style>
    <div class="container">
        @if (session()->has('status'))
            <div class=" notification alert alert-success col-md-12 text-center">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{route('customer_info_list_sms.view')}}" type="GET">
            @csrf
            <div class="card">
                <select class="form-control" name="choos_receiver" id="select_id">
                    <option selected value="0">Please Select a SMS Receiver</option>
                    <option value="customer">Customer</option>
                    <option value="corporate">Corporate</option>
                    <option value="dealer">Dealer</option>
                  </select>
                  
               </div>
               
        </form>
    </div>
@endsection




@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript">

$(document).ready(function () {
     //remove notification after save data to db
     removeNotification();
    function removeNotification(){
    setTimeout(() => {
        $('.notification').remove();
    }, 3000);
    }
});

$('#select_id').change(function (e) { 
    e.preventDefault();
    if(this.value != 0){

       if($('.countbtn').length < 1){
        $( "<button>Submit</button>" ).insertAfter( "#select_id" );
        $('button').addClass('btn btn-primary mt-2 countbtn');
       }
        
    }
    
});






</script>
@endsection
