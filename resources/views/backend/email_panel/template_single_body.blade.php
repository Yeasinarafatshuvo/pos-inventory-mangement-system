@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@section('content')
<style>



</style>
    <div class="container">
       <div class="card">
        <form action="{{route('email_system.send_template_single_body')}}" method="POST">
            @csrf
            <h2 class="bg-primary text-center">Template Email</h2>
            <p style="font-size: 20px; margin:0; padding:2px">To</p>
            <input type="hidden" value="{{$customer_email}}" name="customer_mail">
            <p style="font-size: 20px; font-weight:bold; padding:2px">{{$customer_email}}</p>
            <input style="margin-bottom: 5px" name="email_subject" type="text" required class="form-control" placeholder="Write Subject">
            <input style="margin-bottom: 5px" name="template_name" type="text" required class="form-control" placeholder="Write Template Name">
            <button style="width: 100%" class="btn btn-primary btn-sm text-center">Send Template Mail</button>
        </form>
       </div>
    </div>
@endsection




@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript">


$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');

    
    //remove notification after save data to db
    removeNotification();
    function removeNotification(){
    setTimeout(() => {
        $('.notification').remove();
    }, 3000);
    }


});

function send_mail(customer_mail)
{
    Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Send it!'
    }).then((result) => {
    if (result.isConfirmed) {

        var data = {
            customer_mail:customer_mail
        }
        $.ajax({
            type: "GET",
            url: "{{route('email_system.user_email_info.singlemail')}}",
            data: data,
            success: function (response) {
                console.log(response);
            }
        });
        Swal.fire(
        'Send!',
        'Your mail has been Send.',
        'success'
        )
    }
    })
}


</script>
@endsection
