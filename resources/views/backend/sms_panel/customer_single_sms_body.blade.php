@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@section('content')
<style>



</style>
    <div class="container">
       <div class="card">
        @if (session()->has('empty_sms_body'))
            <div class=" notification alert alert-danger col-md-12 text-center">
                {{ session('empty_sms_body') }}
            </div>
        @endif
        <form action="{{route('single_sms.send')}}" method="POST">
            @csrf
            <h2 class="bg-primary text-center">SMS Body</h2>
            <p style="font-size: 20px; margin:0; padding:2px">To</p>
            <input type="hidden" value="{{$customer_mobile_no}}" name="customer_mobile_no">
            <p style="font-size: 20px; font-weight:bold; padding:2px">{{$customer_mobile_no}}</p>
            <textarea style="margin-bottom: 5px" name="sms_body"  id="" cols="30" rows="10" required class="form-control" placeholder="Write body"></textarea>
            <button style="width: 100%" class="btn btn-primary btn-sm text-center">Send SMS</button>
        </form>
       </div>
    </div>
@endsection




@section('script')

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



</script>
@endsection