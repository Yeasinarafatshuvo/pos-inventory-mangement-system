@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/> 

@section('content')
<style>

</style>
<div id="divName">
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
            <h2 class="bg-primary  text-center" style="color:white;">Supplier List </h2>
            </div>
            <div class="msg col-md-6 offset-md-3">

            </div>
        </div>
    </div>
    <div class="card">
     
        <div class="card-body"> 
        <div class="row">
        <div class="col-md-12 pr-3">
            <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col" colspan="3" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $key => $supplier)
                    <tr id="delete{{$supplier->id}}">
                        <td>{{ $key +1 }}</td>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>{{ $supplier->address }}</td>
                        <td>
                            <a href="" class="btn btn-soft-primary btn-sm open_modal" onclick="id_pass_function_view({{$supplier->id}})" value="" data-toggle="modal">View</a>
                        </td>
                        <td>
                            <a href="" class="btn btn-soft-info btn-sm" data-toggle="modal" onclick="id_pass_function_edit({{$supplier->id}})" data-target="editModal">Edit</a>
                        </td>
                        <td>
                            <button type="button" class="btn btn-soft-danger btn-sm"  onclick="sweet_delete({{$supplier->id}})">Delete</button>
                        </td>
                    </tr>
                 @endforeach
                </tbody>
              </table>
        </div>
        </div>
        </div>
    </div>
</div>
  
  <!-- View Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">View Supplier</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="col-md-10 offset-md-1">
                <form action="">
                  <div class="mb-3">
                      <label for="name" class="form-label">Name</label>
                      <input type="text" name="name" class="form-control" id="name" value="" aria-describedby="" readonly>
                  </div>
                  <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" value=""  id="email" readonly>
                  </div>
                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="phone" name="phone" class="form-control" value=""  id="phone" aria-describedby="" readonly>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value=""  id="address" readonly>
                </div>
              </form>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

    
  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Supplier</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <input type="hidden" name="supplier_id" id="supplier_id" value="">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="e_name" class="form-control" id="e_name" value="" aria-describedby="">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="e_email" class="form-control" value=""  id="e_email">
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="phone" name="e_phone" class="form-control" value=""  id="e_phone" aria-describedby="">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="e_address" class="form-control" value=""  id="e_address">
            </div>
            <button type="submit" class="btn btn-success update_btn" onclick="id_pass_function_update()">Update</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


@endsection

@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script type="text/javascript">

    function id_pass_function_view(id)
    {
      var data = {
        id:id
      }
      $.ajax({
        type: "GET",
        url: "{{route('get_specefic_supplier.data')}}",
        data: data,
        success: function (response) {
          if(response.status == 200){
            jQuery("#name").val(response.get_supplier_specefic_data.name);
            jQuery("#email").val(response.get_supplier_specefic_data.email);
            jQuery("#phone").val(response.get_supplier_specefic_data.phone);
            jQuery("#address").val(response.get_supplier_specefic_data.address);

            $('#viewModal').modal({
                show: true
            }); 

          }

        }
      });

    }
    function id_pass_function_edit(id)
    {
      var data = {
        id:id
      }
      $.ajax({
        type: "GET",
        url: "{{route('get_specefic_supplier_edit.data')}}",
        data: data,
        success: function (response) {
          if(response.status == 200){
            jQuery("#e_name").val(response.get_supplier_specefic_data_edit.name);
            jQuery("#e_email").val(response.get_supplier_specefic_data_edit.email);
            jQuery("#e_phone").val(response.get_supplier_specefic_data_edit.phone);
            jQuery("#e_address").val(response.get_supplier_specefic_data_edit.address);
            jQuery("#supplier_id").val(response.get_supplier_specefic_data_edit.id);
            $('#editModal').modal({
                show: true
            }); 
          }
        }
      });

    }

    function id_pass_function_update(id){
      var data = {
        id:$("#supplier_id").val(),
        name:$("#e_name").val(),
        email:$("#e_email").val(),
        phone:$("#e_phone").val(),
        address:$("#e_address").val()
      }

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $.ajax({
        type: "POST",
        url: "{{route('update_specefic_supplier.data')}}",
        data: data,
        success: function (response) {
          $('#editModal').modal({
                show: false
            });
        }
      });
  }
  function sweet_delete(id){
    
    Swal.fire({
      title: 'Are you sure?',
      background: '#080808',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#266e3b',
      cancelButtonColor: '#d1182f',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
           });
           var d_id = null;
           var d_name = null;
           $.ajax({
            type: "POST",
            dataType: "JSON",
            url: "{{route('delete_specefic_supplier.data')}}",
            data: {id:id},
            success: function (response) {
              var d_id =response.supplier.id;
              var d_name =response.supplier.name;
              $("#delete"+d_id).remove();
              Swal.fire(
                'Deleted!',
                d_name+' has been deleted.',
                'success'
              );
            }
          });
      }
    })
  }

</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection