@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
@section('content')
<style>

.send-all{
    background: linear-gradient(to right, #4632d6, #a17bf1);
}

</style>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">Customer Mail Panel  List</h2>
                @if (session()->has('status'))
                <div class=" notification alert alert-success col-md-12 text-center">
                    {{ session('status') }}
                </div>
                @endif
                @if (session()->has('faild_mail'))
                <div class=" notification alert alert-danger col-md-12 text-center">
                    {{ session('faild_mail') }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="pull-right clearfix">

            </div>
        </div>
        <div class="card-body"> 
        <div class="row">
        <div class="col-md-12 pr-3">
            <form action="{{route('email_system.save_temporary_email')}}" method="POST">
            @csrf
                <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th scope="col">SL</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Customer Email</th>
                        <th scope="col">Created Date</th>
                        <th scope="col">Select Email</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer_information as $key => $customer_info)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$customer_info->name}}</td>
                            <td><span id="email{{$key}}"></span>{{$customer_info->email}}</td>
                            <td>{{$customer_info->updated_at->format('Y-m-d')}}</td>
                            <td>
                                <input onclick='add_email_input("<?php echo $key ?>", "<?php echo $customer_info->email ?>")' style="cursor: pointer; width:20%; padding-left:10%; margin-left:35%" type="checkbox" value="" class="form-control">
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{route('email_system.singlemail_write_body',$customer_info->email)}}">Write Mail</a>
                                <a class="btn btn-success btn-sm" href="{{route('email_system.template_single_body',$customer_info->email)}}">Template Mail</a>
                            </td>
                        </tr> 
                        @endforeach
                    </tbody>
                </table>
              <button style="width: 100%" class="btn btn-primary btn-sm text-center">SEND TO ALL</button>
            </form>
        </div>
    </div>
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

function add_email_input(key, email)
{
    $('#email'+key).html('<input type="hidden" name="customer_email[]" value="'+email+'">');

}

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
