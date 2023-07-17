@extends('backend.layouts.app')
@section('content')
<style>
    
</style>
<div class="container" >
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
        <tr class="text-center">
            <th colspan="5" style="border: 1px solid black !important; font-size:18px">List Of Employee </th>
        </tr>
          <tr>
            <th class="text-center" scope="col">#</th>
            <th class="text-center" scope="col">Name</th>
            <th class="text-center" scope="col">ID</th>
            <th class="text-center" scope="col">Designation</th>
            <th class="text-center" scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($employee_data as $key => $employee_item)
        <tr style="font-size: 15px">
            <th class="text-center">{{$key + 1}}</th>
            <td class="text-center">{{$employee_item->name}}</td>
            <td class="text-center">{{$employee_item->employee_id }}</td>
            <td class="text-center">{{$employee_item->designation }}</td>
            <td class="text-center">
                <a href="{{route('employee.employee_panel.edit', $employee_item->id)}}" class="btn btn-sm btn-primary">Edit</a>
                
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
</div>

    

@endsection

@section('script')
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script type="text/javascript">

    $(document).ready(function() {
      // Display message
      $(".alert").show();

      // Set timer to remove message
      setTimeout(function() {
        $(".alert").fadeOut();
      }, 2000); // 2000 milliseconds = 2 seconds
    });



   


    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
