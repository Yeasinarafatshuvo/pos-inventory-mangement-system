@extends('backend.layouts.app')
@section('content')
<style>
    
</style>
<div class="container" style="font-weight:bold">
    <i class="fa fa-user-plus  ml-1 fa-2xl"  data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" aria-hidden="true" style="color:green; margin-top:14px; margin-bottom: 25px;cursor: pointer"></i><span style="padding: 10px;font-size:15px">Register Employee</span>
  
    <div class="card">
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 offset-md-4">
                    <form action="{{route('employee.automate_attendance_generate')}}" method="POST" >
                        @csrf
                        <div class="form-group">
                            <label for="country">Select Employee Name</label>
                            <select class="form-control aiz-selectpicker" id="employee_attendance_id" name="employee_attendance_id" data-live-search="true" required>
                                <option value="">Select Employee Name</option> 
                                @foreach ($get_all_employee as $employee)
                                    <option value="{{$employee->employee_id}}">{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="country">Select Month</label>
                            <select class="form-control aiz-selectpicker" id="attendance_month" name="attendance_month" data-live-search="true" required>
                                <option value="">Select Month</option> 
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="country">Select Year</label>
                            <select class="form-control aiz-selectpicker" id="attendance_year" name="attendance_year" data-live-search="true" required>
                                <option value="">Select Year</option> 
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Generate Report">
        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- start  modal for adding customer --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-success" id="exampleModalLabel">Register Employee</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
            <span id="close_customer_modal" aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="modal_form">
            
            <div class="form-group">
                <label for="customer-name" class="col-form-label">Employee Name:</label>
                <input type="text" class="form-control customer_name" id="customer-name" >
            </div>
            <div class="form-group">
                <label for="employee_designation"  class="col-form-label">Employee Designation:</label>
                <input type="text"  class="form-control employee_designation" id="employee_designation" >
            </div>
            <div class="form-group">
                <label for="customer-phone" class="col-form-label">Employee Phone:</label>
                <input type="text" class="form-control customre_phone" id="customer-phone" >
            </div>
            <div class="form-group">
                <label for="customer-phone" class="col-form-label">Employee Attendance ID:</label>
                <input type="text" class="form-control employee_id" id="employee_id" >
            </div>
            <div class="form-group">
                <label for="employee_salary" class="col-form-label">Employee Salary:</label>
                <input type="number" class="form-control employee_salary" id="employee_salary" >
            </div>
            <div class="form-group" style="display: none">
                <label for="customer_address"  class="col-form-label">Employee Address:</label>
                <input type="text" value="Dhaka" class="form-control customer_address" id="customer_address" >
            </div>
            <div class="form-group" style="display: none">
                <label for="customer_postal_code" class="col-form-label">Employee Postal Code:</label>
                <input type="text"  class="form-control customer_postal_code" id="customer_postal_code" >
            </div>
            <div class="form-group" style="display: none">
                <label for="country">Select State</label>
                <select class="form-control aiz-selectpicker" id="state" name="state" data-live-search="true">
                    <option value="">Select State</option> 
                    @foreach ($all_state as $state)
                    <option value="{{$state->id}}">{{$state->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="display: none">
                <label for="country">Select City</label>
                <select class="form-control aiz-selectpicker" id="city" name="city" data-live-search="true">
                    <option value="">Select City</option>
                    @foreach ($all_bd_cities as $key =>$city_of_bd)
                    <option value="{{$key}}">{{$city_of_bd}}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" id="add_customer" class="btn btn-primary">Submit</button>
            </form>
        </div>
        </div>
    </div>
    </div>
    {{-- end  modal for adding customer --}}

    

@endsection

@section('script')
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script type="text/javascript">

    
        //customer validation and add
        $('#add_customer').on('click', function (e) {
           e.preventDefault();
           var data = {
               'name' : $('.customer_name').val(),
               'phone' : $('.customre_phone').val(),
               'employee_id' : $('.employee_id').val(),
               'employee_salary' : $('.employee_salary').val(),
               'state' : $('#state').val(),
               'city' : $('#city').val(),
               'customer_address' : $('#customer_address').val(),
               'customer_postal_code' : $('#customer_postal_code').val(),
               'employee_designation' : $('#employee_designation').val(),
               
           }
           
           if(data.name.trim() == "")
           {

            $('.customer_name').addClass("border border-danger");
            $('.customer_name').focus();

           }else{

            $('.customer_name').removeClass("border border-danger");
            $('.customer_name').focusout();

           }
           if(data.phone.trim() == "")
           {
            $('.customre_phone').addClass("border border-danger");
            $('.customre_phone').focus();
           }else{

            $('.customre_phone').removeClass("border border-danger");
            $('.customre_phone').focusout();

           }

           if(data.employee_id.trim() == "")
           {

            $('.employee_id').addClass("border border-danger");
            $('.employee_id').focus();

           }else{

            $('.employee_id').removeClass("border border-danger");
            $('.employee_id').focusout();
            
           }

           if(data.employee_salary.trim() == "")
           {

            $('.employee_salary').addClass("border border-danger");
            $('.employee_salary').focus();

           }else{

            $('.employee_salary').removeClass("border border-danger");
            $('.employee_salary').focusout();

           }

           if(data.customer_address.trim() == "")
           {
            $('#customer_address').addClass("border border-danger");
            $('#customer_address').focus();

           }else{

             $('#customer_address').removeClass("border border-danger");
             $('#customer_address').focusout();

           }

           if(data.employee_designation.trim() == "")
           {
            $('#employee_designation').addClass("border border-danger");
            $('#employee_designation').focus();

           }else{

             $('#employee_designation').removeClass("border border-danger");
             $('#employee_designation').focusout();

           }
          
           if(data.name != "" && data.phone != "" && data.customer_address != "" && data.employee_id != "" && data.employee_salarys != "" && data.employee_designation != "")
           {
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            $.ajax({
                type: "POST",
                url: "{{route('pos.customer_store')}}",
                data: data,
                success: function (response) {
                    if(response.status == 200)
                    {
                        
                        $('#close_customer_modal').trigger('click');
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                            });
                       
                        $('.modal-body').find('input').val('');
                      
                        $('#exampleModal').on('hidden.bs.modal', function () {
                            // Reset the form fields
                            $('#modal_form')[0].reset();
                            
                            // Clear any validation errors
                            $('#modal_form').find('.is-invalid').removeClass('is-invalid');
                            $('#modal_form').find('.invalid-feedback').remove();
                        });


                        
                    }
                    else if(response.status == 403)
                    {
                        Swal.fire({
                            icon: 'error',
                            text: response.message
                        });
                    }

                }
            });
        }
           
        });

   


    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
