@extends('backend.layouts.app')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('All Customers')}}</h1>
	</div>
</div>
<script>
function sweet_alert_customer_registration(){

Swal.fire(
'Good job!',
'You clicked the button!',
'success',
)
}


</script>
@if(session()->has('status'))
    <div class="">
      <script>sweet_alert_customer_registration();</script>
    </div>
@endif


<div class="card">
    <div class="card-header hide_class">
        <h5 class="mb-0 h6">{{translate('Customers')}}</h5>
        <div class="row" style="text-align: center" id="all_search_btn">
            <div class="col-md-12"><button class="btn btn-primary" id="date">Search By Date</button></div>
        </div>
        
        <button data-toggle="modal" data-target="#exampleModal" class="btn btn-info"><i class="fa-solid fa-user-plus"></i> Add Customer</button>
       <div class="pull-right clearfix">
            <form class="" id="sort_customers" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type email or name & Enter') }}">
                    </div>
                </div>
            </form>
        </div>

    </div>
    <div class="card" style="display: none" id="search_date">
        <form  method="GET" action="{{ route('customer_crm.search_by_date') }}">
            @csrf
            <div class="row" style="background-color: #F2F2F2;margin-left:0; margin-right:0">
                <div class="col-md-2 pt-3 font-weight-bold" style="font-size: 20px">Filter By Date</div>
                <div class="col-md-1 pt-3 font-weight-bold" style="padding-right: 0px;">Start Date:</div>
                <div class="col-md-2 pt-2" style="padding-left:0"><input type="text" id="datepicker" name="start_date" required class="form-control"></div>
                <div class="col-md-1 pt-3 font-weight-bold" style="padding-right: 0px;">End Date:</div>
                <div class="col-md-2 pt-2" style="padding-left:0"><input type="text" id="datepickertwo" name="end_date" required class="form-control"></div>
                <div class="col-md-2 pt-2"><button class="btn btn-primary btn-md">Search By Date</button></div>
                <div class="col-md-2 pt-2"><button type="button" onclick="delete_date_search()" class="btn btn-success btn-md"><i class="material-icons">&#xe8ba;</i></button></div>
            </div>
        </form>

    </div>

    <div class="card-body" style=" overflow-x: scroll;">
        <table id="dtBasicExample" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Date')}}</th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Email Address')}}</th>
                    <th data-breakpoints="lg">{{translate('Phone')}}</th>
                    <th data-breakpoints="lg">{{translate('Registered By')}}</th>
                    <th data-breakpoints="lg">{{translate('Number of Orders')}}</th>
                    <th class="text-right" data-breakpoints="lg">{{translate('Options')}}</th>
                </tr>
            </thead>
             
            {{-- Checking Registration By --}}
            <?php
                        function registeredCheckingFunction($id){
                            switch ($id) {
                            case 1:
                                echo "Website";
                                break;
                            case 2:
                                echo "Over Phone Call";
                                break;
                            case 3:
                                echo "Facebook";
                                break;
                            case 4:
                                echo "Whatsapp";
                                break;
                            case 5:
                                echo "Linkedin";
                                break;
                            default:
                                echo "Default Case";
                            }
                        } 
                        ?>
            <tbody>

                @foreach($customers as $key => $user)
                    <tr>
                        <td>{{ ($key+1) + ($customers->currentPage() - 1)*$customers->perPage() }}</td>
                        <td><nobr>{{date("d-m-Y", strtotime($user->created_at))}}</nobr></td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->phone}}</td>
                        <td>{{ registeredCheckingFunction($user->registered_by) }}</td>
                        <td>{{ getUserTotalOrders($user->id) }}</td>
                 
                        <td class="text-right">
                            @can('view_customers')
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" onclick="view_each_customer({{ $user->id }})"   title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            @endcan
                            @can('edit_customers')
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('customers.edit', $user->id)}}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            @endcan
                            @can('delete_customers')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('customers.destroy', $user->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
             
      </table>
      <div class="aiz-pagination">
        {{ $customers->appends(request()->input())->links() }}
    </div>

    </div>
</div>


<div class="modal fade" id="confirm-ban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Do you really want to ban this Customer?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                <a type="button" id="confirmation" class="btn btn-primary">{{translate('Proceed!')}}</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-unban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Do you really want to unban this Customer?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                <a type="button" id="confirmationunban" class="btn btn-primary">{{translate('Proceed!')}}</a>
            </div>
        </div>
    </div>
</div>

      {{-- start  modal for adding customer --}}
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-success" id="exampleModalLabel">Add Customer</h5>
              <button type="button" id="modal_close_button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                <span id="close_customer_modal" aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{route('customer_marketing_crm.add_customer')}}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="customer_name" class="col-form-label">Customer Name:</label>
                  <input type="text" id="customer_name" required class="form-control customer_name close_all_info" name="name" >
                </div>
                <div class="form-group">
                    <label for="selectcustomer_type">Select Customer Type</label>
                    <select name="customer_type" class="form-control close_all_info" id="selectcustomer_type">
                      <option selected value="">Select customer</option>
                      <option value="1">General</option>
                      <option value="2">Dealer</option>
                      <option value="3">Corporate</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="registered_by">Registered By</label>
                    <select name="registered_by" class="form-control close_all_info" id="registered_by">
                      <option selected value="">Select Registered By</option>
                      <option value="1">Website</option>
                      <option value="2">Over Phone Call</option>
                      <option value="3">Facebook</option>
                      <option value="4">Whatsapp</option>
                      <option value="5">LinkedIn</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="customer_phone" class="col-form-label">Customer Phone:</label>
                    <input type="text" id="customer_phone" class="form-control close_all_info" name="phone" >
                </div>
                <div class="form-group">
                    <label for="customer_address"  class="col-form-label">Customer Address:</label>
                    <input type="text" required class="form-control close_all_info" name="customer_address" >
                </div>
                <div class="form-group">
                    <label for="customer_email"  class="col-form-label">Customer Email:</label>
                    <input type="email"  class="form-control close_all_info" name="customer_email" >
                </div>
                <div class="form-group">
                    <label for="customer_postal_code" class="col-form-label">Customer Postal Code:</label>
                    <input type="text" class="form-control close_all_info" id="" name="customer_postal_code" >
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-form-label dealer_seller d-none"><h6>Contact Person Information</h6></label>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary dealer_seller d-none" id="add_contact_person"><i class="fa-solid fa-user-plus"></i></button>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group dealer_seller d-none">
                            <label for="contact_person_name" class="col-form-label">Name:</label>
                            <input type="text" id="contact_person_name" class="form-control close_all_info" name="contact_person_name[]" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group dealer_seller d-none">
                            <label for="contact_person_phone_number" class="col-form-label">Phone Number:</label>
                            <input type="text" id="contact_person_phone_number" class="form-control close_all_info" name="contact_person_phone_number[]" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group dealer_seller d-none">
                            <label for="contact_person_email"class="col-form-label">Email:</label>
                            <input type="text" id="contact_person_email" class="form-control close_all_info" name="contact_person_email[]">
                        </div>
                    </div>
                    <table class="">
                        <tbody id="incerase_contact_person">

                        </tbody>
                     </table>

                </div>

                <div class="form-group dealer_seller d-none">
                    <label for="reference_by"class="col-form-label">Reference By:</label>
                    <input type="text" id="reference_by" class="form-control close_all_info" name="reference_by">
                </div>
                <div class="form-group dealer_seller d-none">
                    <label for="bank_information"class="col-form-label">Bank Information:</label>
                    <textarea name="bank_information" class="form-control close_all_info" id="bank_information" cols="30" rows="3"></textarea>
                </div>
                <div class="form-group dealer_seller d-none">
                    <label for="trade_licence"class="col-form-label">Trade Licence Number:</label>
                    <input type="text" id="trade_licence" class="form-control close_all_info" name="trade_licence">
                </div>
                <div class="form-group dealer_seller d-none">
                    <label for="tin_number"class="col-form-label">TIN Number:</label>
                    <input type="text" id="tin_number" class="form-control close_all_info" name="tin_number">
                </div>
                <div class="form-group dealer_seller d-none">
                    <label for="bin_number"class="col-form-label">Bin Number:</label>
                    <input type="text" id="bin_number" class="form-control close_all_info" name="bin_number">
                </div>
                <div class="form-group">
                    <label for="state">Select State</label>
                    <select  class="form-control aiz-selectpicker close_all_info" id="state" name="state" data-live-search="true">
                        <option value="">Select State</option> 
                        @foreach ($all_state as $state)
                            <option value="{{$state->id}}">{{$state->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="city">Select City</label>
                    <select class="form-control aiz-selectpicker close_all_info" id="city" name="city" data-live-search="true">
                        <option value="">Select City</option>
                        @foreach ($all_bd_cities as $key =>$city_of_bd)
                            <option value="{{$key}}">{{$city_of_bd}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group dealer_seller d-none">
                    {{-- <label for="extra_field" contenteditable="true">Field Name</label>
                    <input type="text" class="form-control close_all_info" name="extra_field"> --}}
                    <button class="btn btn-primary mb-3" id="add_extra_field"><i class="fa-solid fa-circle-plus"></i> Extra Field</button>
                    <table class="">
                        <tbody id="incerase_extra_field">

                        </tbody>
                     </table>
                </div>
                <button type="submit" onclick="" disabled class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
       {{-- end  modal for adding customer --}}
       
@endsection

@section('modal')
    @include('backend.inc.delete_modal')
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/PrintArea/2.4.1/jquery.PrintArea.min.js" integrity="sha512-mPA/BA22QPGx1iuaMpZdSsXVsHUTr9OisxHDtdsYj73eDGWG2bTSTLTUOb4TG40JvUyjoTcLF+2srfRchwbodg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">

    $(document).ready(function() {
      

        var row = "";
        var row_id = 1;
        $("#add_contact_person").click(function (e) {
            e.preventDefault();


            row = `<tr class="tabel_row" id=row_${row_id}>
                        <td class="col-md-4">
                            <label for="contact_person_name" style="font-size: 80%" class="col-form-label">Name:</label>
                            <input type="text" id="contact_person_name" class="form-control close_all_info" name="contact_person_name[]" >
                        </td>
                        <td class="col-md-4">
                            <label for="contact_person_phone_number" style="font-size: 80%" class="col-form-label">Phone Number:</label>
                            <input type="text" id="contact_person_phone_number" class="form-control close_all_info" name="contact_person_phone_number[]" >
                        </td>
                        <td class="col-md-4">
                            <label for="contact_person_email" style="font-size: 80%" class="col-form-label">Email:</label>
                            <input type="text" id="contact_person_email" class="form-control close_all_info" name="contact_person_email[]">
                        </td>
                        <td><button style="margin-top: 32px" class="btn btn-danger" onclick="remove_contact_person(${row_id})"><i class="fa-solid fa-xmark text-white"></i></button></td>
                        
                    </tr>`;
                    row_id = row_id+1;
                $("#incerase_contact_person").append(row);


        });


      
        var name;
        $("#customer_name").keyup(function() {
            name = ($(this).val());
            
        });

        $('#selectcustomer_type').change(function (e) { 
        e.preventDefault();

        var customer_type = this.value;
        if(customer_type != "" && name != ""){
            console.log(this.value);
            $(":submit").removeAttr("disabled");
        }else{
            $(":submit").attr("disabled", true);
        }
        if(customer_type > 1){
            $('.dealer_seller').removeClass("d-none");
            $(".dealer_seller").addClass("d-block");
        }
        else{
            $('.dealer_seller').removeClass("d-block");
            $(".dealer_seller").addClass("d-none");
            $(".tabel_row").remove();
        }
        
    });

    $("#modal_close_button").click(function (e) { 
        e.preventDefault();
        $(".close_all_info").val('');
        $('.dealer_seller').removeClass("d-block");
        $(".dealer_seller").addClass("d-none");
        $(".tabel_row").empty();
    });

        var extra_field_row = "";
        var extra_field_row_id = 1;

        $("#add_extra_field").click(function (e) {
            e.preventDefault();


            extra_field_row = `<tr class="tabel_row mb-3" id=row_${extra_field_row_id}>
                        <td class="col-md-6">
                            <input type="text" class="form-control close_all_info" name="extra_field_name[]" placeholder="Field Name">
                        </td>
                        <td class="col-md-6">
                            <input type="text" class="form-control close_all_info" placeholder="Field Value" name="extra_field_value[]">
                        </td>
                        <td><button style="" class="btn btn-danger" onclick="remove_extra_field(${extra_field_row_id})"><i class="fa-solid fa-xmark text-white"></i></button></td>
                        
                    </tr>`;

                    extra_field_row_id = extra_field_row_id+1;
                    $("#incerase_extra_field").append(extra_field_row);



        });





    })


        function sort_customers(el){
            $('#sort_customers').submit();
        }
        function confirm_ban(url)
        {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href' , url);
        }

        function confirm_unban(url)
        {
            $('#confirm-unban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationunban').setAttribute('href' , url);
        }

        // deleting row from the registration form
        function remove_contact_person(a) {
            
            $("#row_"+a).remove();
            console.log(a);
        }
        // deleting Extra field row from the registration form
        function remove_extra_field(extra_field_row_id) {
            
            $("#row_"+extra_field_row_id).remove();
            console.log(extra_field_row_id);
        }

        function view_each_customer(customer_id){

            var customer_id = customer_id

            $.ajax({
                type: "GET",
                url: "{{route('customer_crm.check_customer')}}",
                data: {
                    customer_id:customer_id
                },
                success: function (response) {
                    var id = customer_id;
                    var url = "{{ route('customers.show', ['customer' => ':id']) }}";
                    url = url.replace(':id', customer_id);
                    window.location.replace(url);
                }
            });
        }

        // Search by Date

        $( function() {
            $.datepicker.setDefaults(
            {
            showOn: "focus",
            dateFormat: "yy-mm-dd"
            });

            $("#datepicker").datepicker();

        });

        $("#datepicker").focusin(function(){
            $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
        });

        $( function() {
            $( "#datepickertwo" ).datepicker({ dateFormat: 'yy-mm-dd' });
        })

        $('#date').click(function (e) {
            e.preventDefault();
            $('#search_date').show();
            $('.hide_class').hide();

        });


        function delete_date_search()
        {
            $('#search_date').hide();
            $('.hide_class').show();
            $("#datepicker").val("");
            $("#datepickertwo").val("");
            
        }

        // $(".card-body").click(function (e) { 
        //     e.preventDefault();
        //     $('#ui-datepicker-div').css('display','none'); 
        // });

       


//  window.onload = function(){
//   var divToHide = document.getElementById('ui-datepicker-div');
//   document.onclick = function(e){
//     if(e.target.id !== 'divToHide'){
//       //element clicked wasn't the div; hide the div
//       divToHide.style.display = 'none';
//     }
//   };
// };

    </script>

@endsection
