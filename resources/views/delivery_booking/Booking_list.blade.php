@extends('backend.layouts.app')

    @section('content')
    <!-- csrf-token -->
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <main class="container mt-3">
            <div class="">
                <h5 class="text-center">Book Summmary</h5>

            </div>

            <table class="table text-center">
                <thead>

                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Delivery Man</th>
                    <th scope="col">Order Id</th>
                    <th scope="col">Delivery Date</th>
                    <th scope="col">Delivery Time</th>
                    <th scope="col">Action</th>
                </tr>

                </thead>
                    <tbody id="bodyData">

                    </tbody>
            </table>


        </main>
    @endsection

    @section('script')
        <script type="text/javascript">
            var increments = 1;
            var rowData="";
            $(document).ready(function() {
                var url = "{{URL('/admin/edit')}}";
                $.ajax({
                    url: "/admin/ajaxData",
                    type: "POST",
                    data:{
                        _token:'{{ csrf_token() }}'
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(dataResult){
                        var resultData = dataResult.data3;
                        console.log(resultData);
                        console.log('row length:',resultData.lenght);

                        var i=1;
                        $.each(resultData,function(index,row){
                            var editUrl = url+'/'+row.id;

                            rowData +='<tr id="data-row-id_customer" class="customer_row_'+increments+' customer_common">\
                            <td>'+i+'</td>\
                            <td>'+row.delivery_man+'</td>\
                            <td>'+row.order_id+'</td>\
                            <td>'+row.date+'</td>\
                            <td>'+row.slot+'</td>\
                            <td class="text-center">\
                                <a href="'+editUrl+'" class="text-center btn btn-sm btn-info" ><i class=" fa fa-edit"></i></a>\
                                <button class="text-center btn btn-sm btn-danger"  onclick="deleteConfirmation('+row.id+')"><i class="fa fa-trash-alt"></i></button>\
                            </td>\
                            </tr>';
                            i++;

                        })
                        $('#bodyData').html(rowData);


                    }
                });
            });

            //delete customer function
            function deleteConfirmation(id) {
                event.preventDefault();
                swal({
                title: "Delete?",
                text: "Please ensure and then confirm!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: !0
                }).then(function (e) {
                    if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                        type: 'POST',
                        url: "{{url('admin/delete')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                        if (results.success === true) {
                        swal("Done!", results.message, "success");
                        } else {
                        swal("Error!", results.message, "error");
                        }
                        }
                        });
                    } else {
                        e.dismiss;
                    }
                }, function (dismiss) {
                return false;
                })
                }



    </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    @endsection




