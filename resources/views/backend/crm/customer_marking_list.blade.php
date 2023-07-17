@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@section('content')
<style>

</style>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">MARKETING CONTACT LIST</h2>
                @if (session()->has('status'))
                <div class=" notification alert alert-success col-md-12">
                    {{ session('status') }}
                </div>
                @endif
                @if (session()->has('failed'))
                <div class=" notification alert alert-danger col-md-12">
                    {{ session('failed') }}
                </div>
                @endif
            </div>
        </div>
    </div>
      {{-- start  modal for adding customer --}}
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-success" id="exampleModalLabel">Add Customer</h5>
              <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                <span id="close_customer_modal" aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{route('customer_marketing_crm.add_customer')}}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="customer-name" class="col-form-label">Customer Name:</label>
                  <input type="text" required class="form-control customer_name" name="name" >
                </div>
                <div class="form-group">
                    <label for="selectcustomer_type">Select Customer Type</label>
                    <select name="customer_type" class="form-control" id="selectcustomer_type">
                      <option selected value="">Select customer</option>
                      <option value="1">Customer</option>
                      <option value="2">Dealer</option>
                      <option value="3">Corporate</option>
                    </select>
                  </div>
                <div class="form-group">
                    <label for="customer-phone" class="col-form-label">Customer Phone:</label>
                    <input type="text" class="form-control " name="phone" >
                </div>
                <div class="form-group">
                    <label for="customer_address"  class="col-form-label">Customer Address:</label>
                    <input type="text" required class="form-control " name="customer_address" >
                </div>
                <div class="form-group">
                    <label for="customer_address"  class="col-form-label">Customer Email:</label>
                    <input type="email"  class="form-control " name="customer_email" >
                </div>
                <div class="form-group">
                    <label for="customer_postal_code" class="col-form-label">Customer Postal Code:</label>
                    <input type="text" class="form-control " name="customer_postal_code" >
                </div>
                <div class="form-group">
                    <label for="country">Select State</label>
                    <select  class="form-control aiz-selectpicker" id="state" name="state" data-live-search="true">
                        <option value="">Select State</option> 
                        @foreach ($all_state as $state)
                            <option value="{{$state->id}}">{{$state->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="country">Select City</label>
                    <select class="form-control aiz-selectpicker" id="city" name="city" data-live-search="true">
                        <option value="">Select City</option>
                        @foreach ($all_bd_cities as $key =>$city_of_bd)
                            <option value="{{$key}}">{{$city_of_bd}}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" disabled class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
       {{-- end  modal for adding customer --}}



    <div class="card">
        <div class="card-header">
            <div class="pull-right clearfix">
                <button data-toggle="modal" data-target="#exampleModal" class="btn btn-info btn-sm my-3"><i class="fa-solid fa-user-plus"></i> Add Customer</button>
            </div>
        </div>
        <div class="card-body"> 
        <div class="row">
        <div class="col-md-12 pr-3">
            <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th scope="col">SL</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Customer Email</th>
                    <th scope="col">Customer Type</th>
                    <th scope="col">Mobile</th>
                    <th scope="col">Created Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($all_user_data as  $key => $customer_datas)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td class="text-center pt-4">{{$customer_datas->name}}</td>
                            <td class="text-center pt-4">{{$customer_datas->email}}</td>
                            <td class="text-center pt-4">{{$customer_datas->user_type}}</td>
                            <td class="text-center pt-4">{{$customer_datas->phone}}</td>
                            <td class="text-center pt-4">{{date('d-m-Y', strtotime($customer_datas->updated_at))}}</td>
                           

                            @if ($customer_datas->customer_feedback_status != null)
                                <td class="text-center pt-4">{{$customer_datas->customer_feedback_status}}</td>
                            @else
                                <td></td>
                            @endif

                            @if ($customer_datas->customer_feedback_status != null)
                                <td class="text-center pt-4">
                                    <a href="{{route('customer_crm_feedback.edit_view', $customer_datas->id)}}" class="btn btn-info btn-sm">Edit</a>
                                    
                                </td>
                            @else
                                <td class="text-center pt-4">
                                    <a href="{{route('customer_marketing_crm.add_status_view', $customer_datas->id)}}" class="btn btn-primary btn-sm">Add status</a>
                                </td>
                            @endif
                            
                        </tr>
                    @endforeach
                 
                </tbody>
              </table>
              {{ $all_user_data->links() }}
        </div>
    </div>
        </div>
    </div>
@endsection




@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');


//delete Confirmation code
$('.delete-confirm').click(function(event){
   event.preventDefault();
   var url = $(this).attr('href');
    swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover !",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
        window.location.href = url;
        swal("Your quotation has been deleted!", {
        icon: "success",
        });
    } else {
        swal("Your quotation is safe!");
    }
    });

});

});







//remove notification after save data to db
removeNotification();
function removeNotification(){
  setTimeout(() => {
    $('.notification').remove();
  }, 3000);
}

$('#selectcustomer_type').change(function (e) { 
    e.preventDefault();
    var customer_type = this.value;
    if(customer_type != ""){
        console.log(this.value);
        $(":submit").removeAttr("disabled");
    }else{
        $(":submit").attr("disabled", true);
    }
    
    
});





</script>
@endsection
