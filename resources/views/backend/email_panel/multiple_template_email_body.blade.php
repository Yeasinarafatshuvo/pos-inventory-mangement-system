@extends('backend.layouts.app')
@section('content')
<style>



</style>
    <div class="container">
       <div class="card">
        <form action="{{route('email_system.send_multiple_template_mail')}}" method="POST">
            @csrf
            <h2 class="bg-primary text-center">Template Email</h2>
            <p style="font-size: 20px; margin:0; padding:2px">To</p>
            <p style="font-size: 20px; font-weight:bold; padding:2px">Send To All Selected Mail</p>
            <input style="margin-bottom: 5px" name="email_subject" type="text" required class="form-control" placeholder="Write Subject">
            <input style="margin-bottom: 5px" name="template_name" type="text" required class="form-control" placeholder="Write Template Name">
            <button style="width: 100%" class="btn btn-primary btn-sm text-center">Send Template Mail</button>
        </form>
       </div>
    </div>
@endsection




@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript">





</script>
@endsection
