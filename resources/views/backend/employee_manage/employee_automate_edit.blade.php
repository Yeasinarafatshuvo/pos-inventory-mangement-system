@extends('backend.layouts.app')
@section('content')
<style>
    
</style>

<div class="container" >
    <h4 class="text-center">Edit Employee</h4>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 offset-md-4">
                    <form method="POST" action="{{route('employee.employee_panel.update')}}">
                        @csrf
                        <input type="hidden" name="employee_user_id" value="{{$user_data->id}}">
                        <div class="form-group">
                            <label for="customer-name" class="col-form-label">Employee Name:</label>
                            <input  type="text" class="form-control customer_name @error('employee_name') is-invalid @enderror" name="employee_name" id="customer-name" value="{{ old('employee_name', $user_data->name) }}" >
                            @error('employee_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="customer-phone" class="col-form-label">Employee Phone:</label>
                            <input  type="text" class="form-control customre_phone @error('employee_phone') is-invalid @enderror" name="employee_phone" id="customer-phone"  value="{{ old('employee_phone', str_replace("+88", "", $user_data->phone)) }}">
                            @error('employee_phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="customer-phone" class="col-form-label">Employee Attendance ID:</label>
                                <input  type="text" class="form-control employee_id @error('employee_id') is-invalid @enderror" name="employee_id" id="employee_id" value="{{ old('employee_id', $user_data->employee_id) }}">
                                @error('employee_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="employee_salary" class="col-form-label">Employee Salary:</label>
                                <input  type="number" class="form-control employee_salary @error('employee_salary') is-invalid @enderror" name="employee_salary" id="employee_salary" value="{{ old('employee_salary', $user_data->employee_salary) }}">
                                @error('employee_salary')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="employee_designation" class="col-form-label">Employee Designation:</label>
                                <input  type="text" class="form-control employee_designation @error('employee_designation') is-invalid @enderror" name="employee_designation" id="employee_designation" value="{{ old('employee_designation', $user_data->designation) }}">
                                @error('employee_designation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="employee_department" class="col-form-label">Employee Department:</label>
                                <input  type="text" class="form-control employee_department @error('employee_department') is-invalid @enderror" name="employee_department" id="employee_department" value="{{ old('employee_department', $user_data->employee_department) }}">
                                @error('employee_department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="employee_status">Employee Status:</label>
                                <select class="form-control selectpicker @error('employee_status') is-invalid @enderror" id="employee_status" name="employee_status" data-live-search="true" required>
                                    <option value="" selected disabled>Select Employee Status</option>
                                    <option value="intern" @if(old('employee_status', $user_data->employee_status) == 'intern') selected @endif>Intern</option>
                                    <option value="probation" @if(old('employee_status', $user_data->employee_status) == 'probation') selected @endif>Probation</option>
                                    <option value="permanent" @if(old('employee_status', $user_data->employee_status) == 'permanent') selected @endif>Permanent</option>
                                </select>
                                @error('employee_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="employe_date_of_joining" class="col-form-label">Employee Joining Date:</label>
                                <input  type="date" class="form-control employe_date_of_joining @error('employe_date_of_joining') is-invalid @enderror" name="employe_date_of_joining" id="employe_date_of_joining" value="{{ old('employe_date_of_joining', $user_data->employe_date_of_joining) }}">
                                @error('employe_date_of_joining')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="empl_permanent_dat" class="col-form-label">Employee Permanent Date:</label>
                                <input  type="date" class="form-control empl_permanent_dat @error('empl_permanent_dat') is-invalid @enderror" name="empl_permanent_dat" id="empl_permanent_dat" value="{{ old('empl_permanent_dat', $user_data->empl_permanent_dat) }}">
                                @error('empl_permanent_dat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                                                        
                            <button type="submit" id="add_customer" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


    

@endsection

@section('script')
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script type="text/javascript">


   


    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
