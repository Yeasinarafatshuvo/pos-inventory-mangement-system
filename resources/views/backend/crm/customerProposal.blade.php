@extends('backend.layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #suggesstion-box li {
            list-style-type: none;
            cursor: pointer;
            padding: 5px;
            padding-left: 5px;
        }

        #suggesstion-box li:hover {
            color: green;
            padding-top: 5px;

        }
        #suggesstion-box, #suggesstion-box_customer li{
            list-style-type: none;
            cursor: pointer;
            padding-left: 30px;
        }
        #suggesstion-box_customer li{
            font-size: 15px;
            list-style-type: none;
            padding-top: 10px;
            cursor: pointer;
            padding-left: 30px;
        }
        #suggesstion-box, #suggesstion-box_customer li:hover{
            color: #3498db;

        }
        .serch_box_popup{
            position: absolute;
            z-index: 21000;
            top: 100;
            right: 0;
            left: 0;
            width: 50%;
            border-radius: 3px;
            background: #fff;
            margin-left: 35px;
            box-shadow: 0 10px 15px rgb(0 0 0 / 20%), 0 1px 0 rgb(0 0 0 / 5%) inset, 0 -5px 0 0 #fff;
        }

        .search-box_popup {
            position: absolute;
            z-index: 21000;
            top: 100;
            right: 0;
            left: 0;
            width: 50%;
            border-radius: 3px;
            background: #fff;
            margin-left: 35px;
            box-shadow: 0 10px 15px rgb(0 0 0 / 20%), 0 1px 0 rgb(0 0 0 / 5%) inset, 0 -5px 0 0 #fff;

        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <h2 class="bg-primary my-3  text-center" style="color:white;">List of Proposal </h2>
                </div>
            </div>
            <button data-toggle="modal" data-target="#addModel" class="btn btn-success btn-sm my-3"><i
                    class="fa-solid fa-plus"></i> Add Proposal</button>
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Client Name</th>
                        <th scope="col">Proposal Subject</th>
                        <th scope="col">Proposed Product Name</th>
                        <th scope="col">Proposed Product Quantity</th>
                        <th scope="col">Proposed Product Price</th>
                        <th scope="col">Proposed Expired Date</th>
                        <th scope="col">Proposed Status</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="alldata">

                </tbody>
            </table>
        </div>

        <!-- Add Proposal Modal -->
        <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Proposal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('customer_log_details_crm.proposal.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="client_title" class="form-label">Client Name</label>
                                <input type="text" name="client_title" class="form-control" id="customer_serch_id"
                                    placeholder="Search Client Name">
                                <input type="hidden" name="client_id" class="form-control" id="client_id">
                                <div id="suggesstion-box_customer" class="serch_box_popup"></div>
                            </div>
                            <div class="mb-3">
                                <label for="proposal_subject" class="form-label">Proposal Subject</label>
                                <input type="text" name="proposal_subject" class="form-control" id="proposal_subject">
                            </div>
                            <div class="mb-3">
                                <label for="proposed_product_name" class="form-label">Proposal Product Name</label>
                                <input id="proposed_product_name" type="text" class="form-control"
                                    placeholder="Search By Product Name">
                                <div id="suggesstion-box" class="search-box_popup"></div>
                            </div>
                            <div class="form-group" id="append_div">

                            </div>
                            <div class="mb-3">
                                <label for="proposed_expired_date" class="form-label">Proposed Expired Date</label>
                                <input type="date" name="proposed_expired_date" class="form-control"
                                    id="proposed_expired_date">
                            </div>
                            <div class="mb-3">
                                <select class="form-control form-select form-select-lg mb-3"
                                    aria-label="form-select-lg example" name="proposed_status" id="proposed_status">
                                    <option selected value="0">-----Select Status-----</option>
                                    <option value="1">Pending</option>
                                    <option value="2">Rejected</option>
                                    <option value="3">Approved</option>
                                    <option value="4">Expired</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="/js/jquery-1.2.6.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/js/all.min.js"
        integrity="sha512-rpLlll167T5LJHwp0waJCh3ZRf7pO6IT1+LZOhAyP6phAirwchClbTZV3iqL3BMrVxIYRbzGTpli4rfxsCK6Vw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        //search result pop up responsive
        $('#customer_serch_id').keyup(function(e) {
            $('#suggesstion-box_customer').css('marginLeft', '35px');
        });

           //code start for customer section
           $("#customer_serch_id").on('keyup', function () {
            $("#suggesstion-box_customer").html("");
            var input_customer_value = $(this).val();
            var search_id_value = $(this).attr('id');
            if(search_id_value == 'staff_serch_id')
            {
                hit_url = "{{route('pos.staff_search')}}";
            }else if(search_id_value == 'customer_serch_id'){
                hit_url = "{{route('pos.customer_search')}}";
            }
            
            $.ajax({
                type: "GET",
                url: hit_url,
                data: {'search':input_customer_value, 'id':""},
                success: function (response) {
                   
                   var data = "";
                   var search_result_data_length = response.data.length;
                   for (let i = 0; i <search_result_data_length; i++) {
                      var customer_name = response.data[i]["name"];
                     
                      data += '<li onClick="selectCustomerInfo('+response.data[i]["id"] +')">'+customer_name+'</li>';
                      

                   }

                   $("#suggesstion-box_customer").show();
                   if(input_customer_value !== "")
                   {
                    $("#suggesstion-box_customer").html(data);
                   }
                   else
                   {
                    $("#suggesstion-box_customer").html("");
                   }

                }
            });

        });


        var increments = 1;
        function selectCustomerInfo(id)
        {
            $.ajax({
                type: "GET",
                url: "{{route('pos.customer_search')}}",
                data:{'search':"",'id':id},
                success: function (response) {

                    var client_name = response.name;
                    var client_id = response.id;



                    if($('.customer_common').length <1){
                        $('#customer_serch_id').val(client_name);
                        $('#client_id').val(client_id);
                        
                    }

                    $("#suggesstion-box_customer").hide();


                    increments++;
                }
            });

        }

        $("#proposed_product_name").keyup(function() {
            var value = $("#proposed_product_name").val();
            $("#suggesstion-box").html("");

            $.ajax({
                type: "GET",
                url: "{{ route('pos.search') }}",
                data: {
                    'search': value,
                    'id': ""
                },
                success: function(response) {
                    var data = "";
                    var p_length = response == "" ? 0 : response.match_products.data.length;

                    for (i = 0; i < p_length; i++) {
                        data += '<li onClick="selectProduct(' + response.match_products.data[i]["id"] +
                            ')">' + response.match_products.data[i]["name"] + '</li>';
                    }
                    $("#suggesstion-box").show();
                    if (value !== "") {
                        $("#suggesstion-box").html(data);
                    } else {
                        $("#suggesstion-box").html("");
                    }

                }

            });


        });
        var product_details_array = [];
        var jsonData;

        function selectProduct(id) {
            $.ajax({
                type: "GET",
                url: "{{ route('pos.search') }}",
                data: {
                    'search': "",
                    'id': id
                },

                success: function(response) {
                    var countRow = 1;
                    var row = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong  value="${response.match_products.id}">${response.match_products.name}</strong>
                        <div class="row mt-3">
                            <input class="form-control" type="hidden" name="product_name[]" value="${response.match_products.name}"/>
                          <div class="col-md-6">
                            <label class="font-weight-bold text-primary" for="product_quantity">Product Quantity</label>
                            <input class="form-control" type="number" name="product_quantity[]" id="product_quantity" value="1"/>
                          </div>
                          <div class="col-md-6">
                            <label class="font-weight-bold text-primary" for="product_price">Product Price</label>
                            <input class="form-control" type="number" name="product_price[]" id="product_price" value="${response.match_products.highest_price}"/>
                          </div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>`;

                    //checking duplicate product
                    var newValue = 0;
                    $('.check_duplicate').each(function() {
                        if (this.value == response.match_products.id) {
                            newValue++;
                        }
                    });

                    if (!(newValue == 1)) {
                        $('#append_div').append(row);
                        $('#proposed_product_name').val('');
                        $("#suggesstion-box").html("");
                    }

                }
            })
        };




        show();
        // Fetching Information From Database start
        function show() {
            $.ajax({
                url: "{{ route('customer_log_details_crm.proposal.view') }}",
                type: "GET",
                dataType: "JSON",
                success: function(response) {
                    if (response.status == "success") {
                        var allData = "";
                        $.each(response.alldata, function(key, proposal) {
                            var serial_no = key + 1;
                            var status = proposal.proposed_status;
                            var backcolor = "";
                            var selected_value = "";
                            var null_value = "";

                            if (status == 1) {
                                status = "Pending";
                                backcolor = "orange"
                            }
                            if (status == 2) {
                                status = "Rejected";
                                backcolor = "red";
                            }
                            if (status == 3) {
                                status = "Approved";
                                backcolor = "green";

                            }
                            if (status == 4) {
                                status = "Expired";
                                backcolor = "brown";
                            }


                            let url = "{{ route('customer_log_details_crm_customer.view', ':id') }}";
                            url = url.replace(':id', proposal.id);
                            allData += `<tr>
                              <td>${serial_no}</td>
                              <td>${proposal.client_title}</td>
                              <td>${proposal.proposal_subject}</td>
                              <td>${proposal.proposed_product_name}</td>
                              <td>${proposal.proposed_product_quantity}</td>
                              <td>${proposal.proposed_product_price}</td>
                              <td>${proposal.proposed_expired_date}</td>
                              <td>
                                <select class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example" id="changeStatus${proposal.id}" style="background:${backcolor};color:white" onchange="changeStatus(${proposal.proposed_status}, ${proposal.id})">
                                  <option ${proposal.proposed_status == 1? "selected":""} value="1">Pending</option>
                                  <option ${proposal.proposed_status == 2? "selected":""} value="2">Rejected</option>
                                  <option ${proposal.proposed_status == 3? "selected":""} value="3">Approved</option>
                                  <option ${proposal.proposed_status == 4? "selected":""} value="4">Expired</option>
                                </select>
                              </td>
                              <td class="text-center">
                                <a href="${url}" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-eye btn btn-primary btn-sm" id=${proposal.id}></i></a>
                                <i class="fa-solid fa-pen-to-square btn btn-info btn-sm" id=${proposal.id}></i>
                                <i class="fa-solid fa-trash-can btn btn-danger btn-sm" onclick="delete_client(${proposal.id})" id=${proposal.id}></i>
                                <i class="fa-solid fa-print btn btn-warning btn-sm" id=${proposal.id}></i>
                              </td>
                              </tr>`;
                        });
                        jQuery(".alldata").html(allData);


                    } else if (result.status == "404") {
                        alert("Error 404: Data Not Found");
                    }
                }
            });
        }

        // function of stop change value in input (number) field on mouse wheel/scroll
        $('input[type=number]').on('mousewheel', function() {
            var el = $(this);
            el.blur();
            setTimeout(function() {
                el.focus();
            }, 10);
        });


        // Status Change Function
        function changeStatus(proposed_status, proposed_id) {
            var val = $('#changeStatus' + proposed_id).val();

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('customer_log_details_crm.proposal.changestatus') }}",
                        data: {
                            id: proposed_id,
                            proposed_status: val
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.result == "success") {
                                if (response.status == 1) {
                                    $('#changeStatus' + proposed_id).css("background-color", "orange");
                                    Swal.fire(
                                        'Updated!',
                                        'Your status has been updated.',
                                        'success'
                                    )
                                }
                                if (response.status == 2) {
                                    $('#changeStatus' + proposed_id).css("background-color", "red");
                                    Swal.fire(
                                        'Updated!',
                                        'Your status has been updated.',
                                        'success'
                                    )
                                }
                                if (response.status == 3) {
                                    $('#changeStatus' + proposed_id).css("background-color", "green");
                                    Swal.fire(
                                        'Updated!',
                                        'Your status has been updated.',
                                        'success'
                                    )
                                }
                                if (response.status == 4) {
                                    $('#changeStatus' + proposed_id).css("background-color", "brown");
                                    Swal.fire(
                                        'Updated!',
                                        'Your status has been updated.',
                                        'success'
                                    )
                                }
                            }
                        }
                    });
                }
            })
        }

        function delete_client(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('customer_log_details_crm.proposal.delete') }}",
                        type: 'GET',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            show();
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        }
                    });
                }
            })
        }
    </script>
@endsection
