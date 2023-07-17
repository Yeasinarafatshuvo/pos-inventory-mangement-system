@extends('backend.layouts.app')
<link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<style>
input::-webkit-input-placeholder {
    font-size: 12px;
}
.toggle-off{
    padding-bottom: 10px;
}

.switch {
  position: relative;
  display: inline-block;
  width: 90px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ca2222;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2ab934;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(55px);
  -ms-transform: translateX(55px);
  transform: translateX(55px);
}

/*------ ADDED CSS ---------*/
.on
{
  display: none;
}

.on
{
  color: white;
  position: absolute;
  transform: translate(-50%,-50%);
  top: 50%;
  left: 36%;
  font-size: 10px;
  font-family: Verdana, sans-serif;
}

.off
{
  color: white;
  position: absolute;
  transform: translate(-50%,-50%);
  top: 50%;
  left: 50%;
  font-size: 10px;
  font-family: Verdana, sans-serif;
}

input:checked+ .slider .on
{display: block;}

input:checked + .slider .off
{display: none;}

/*--------- END --------*/

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>
@section('content')

    <div class="card">
        
        <form class="" id="sort_orders" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('Orders') }}</h5>
                </div>
                <div class="col-xl-2 col-md-3 ml-auto">
                    <select class="form-control aiz-selectpicker" name="payment_status" onchange="sort_orders()"
                        data-selected="{{ $payment_status }}">
                        <option value="">{{ translate('Filter by Payment Status') }}</option>
                        <option value="paid">{{ translate('Paid') }}</option>
                        <option value="unpaid">{{ translate('Unpaid') }}</option>
                    </select>
                </div>

                <div class="col-xl-2 col-md-3">
                    <select class="form-control aiz-selectpicker" name="delivery_status" onchange="sort_orders()"
                        data-selected="{{ $delivery_status }}">
                        <option value="">{{ translate('Filter by Deliver Status') }}</option>
                        <option value="order_placed">{{ translate('Order placed') }}</option>
                        <option value="confirmed">{{ translate('Confirmed') }}</option>
                        <option value="processed">{{ translate('Processed') }}</option>
                        <option value="shipped">{{ translate('Shipped') }}</option>
                        <option value="delivered">{{ translate('Delivered') }}</option>
                        <option value="cancelled">{{ translate('Cancelled') }}</option>
                    </select>
                </div>
                <div class="col-xl-2 col-md-3">
                   
                    <div class="input-group">
                        <label style="padding: 10px 3px 0px 0px;"><b>From</b> </label>
                        <input type="date" class="form-control"  name="start_date" @isset($start_date)
                            value="{{ $start_date }}" @endisset >
                    </div>
                </div>
                <div class="col-xl-2 col-md-3">
                    <div class="input-group">
                        <label style="padding: 10px 3px 0px 0px;"><b>To</b> </label>
                        <input type="date" class="form-control" name="end_date" @isset($end_date)
                            value="{{ $end_date }}" @endisset >
                    </div>
                </div>
                <div class="col-xl-2 col-md-3">
                    <div class="input-group">
                        <input style="padding: 5px;" type="text" class="form-control" id="search" name="search" @isset($sort_search)
                            value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Order code/Phone no start +88 & hit Enter') }}">
                    </div>
                </div>
            </div>
        </form>

        <div class="card-body" style=" overflow-x: scroll;">
            <table id="dtBasicExample" class="table  mb-0" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Order Code') }}</th>
                        <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th>
                        <th data-breakpoints="lg">{{ translate('Customer') }}</th>
                        <th data-breakpoints="lg">{{ translate('City') }}</th>
                        <th data-breakpoints="lg" style="text-align:center;">{{ translate('Phone') }}</th>
                        <th>{{ translate('Amount') }}</th>
                        @can('admin_approved')
                            <th>{{ translate('Approval Request') }}</th>
                        @endcan
                        <th data-breakpoints="lg">{{ translate('Delivery Status') }}</th>
                        <th data-breakpoints="lg">{{ translate('Payment Status') }}</th>
                        <th data-breakpoints="lg">{{ translate('Money Receipt') }}</th>
                        <th data-breakpoints="lg" style="text-align:center;">{{ translate('Date') }}</th>
                        <th data-breakpoints="lg" class="text-right" width="15%">{{ translate('options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                        <tr>
                            <td>
                                {{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}
                            </td>
                            <td>
                                @if(addon_is_activated('multi_vendor'))<div>{{ translate('Package') }} {{ $order->code }} {{ translate('of') }}</div>@endif
                                <div class="fw-600">{{ $order->combined_order->code ?? '' }}</div>
                            </td>
                            <td>
                                {{ count($order->orderDetails) }}
                            </td>
                            <td>
                                @if ($order->user != null)
                                    {{ $order->user->name }}
                                @else
                                    Guest ({{ $order->guest_id }})
                                @endif
                            </td>
                            @php
                                $customer_address_to_array  = json_decode($order->shipping_address);
                                try {
                                   $customer_city =   $customer_address_to_array->city;
                                } catch (\Throwable $th) {
                                    $customer_city = '';
                                }
                                
                               
                            @endphp
                            <td>
                                @if ($order->user != null)
                                    {{$customer_city}}
                                @endif
                            </td>
                            <td>
                            @if ($order->billing_address !== null  && !empty($order->billing_address))
                            @php
                            $user_info = json_decode($order->billing_address);
                            if(!empty($user_info->phone)){
                                echo $user_info->phone;
                            }else{
                                echo $order->user->phone;
                            }
                            @endphp
                            @else
                                {{ $order->user->phone}}
                            @endif
                            </td>
                            <td>
                                {{ format_price($order->grand_total) }}
                            </td>
                            @can('admin_approved')
                            <td>
                                @if ($order->is_approved == 1 || is_null($order->is_approved))
                                <label class="switch">
                                    <input checked  id="toggle-event{{$order->id}}" onchange="approved_order('<?php echo $order->id ?>')" type="checkbox" id="togBtn">
                                    <div class="slider round">
                                        <span class="on">Approved</span><span class="off">NA</span>
                                    </div>
                                </label>
                                @else
                                <label class="switch">
                                    <input id="toggle-event{{$order->id}}" onchange="approved_order('<?php echo $order->id ?>')" type="checkbox" id="togBtn">
                                    <div class="slider round">
                                        <span class="on">Approved</span><span class="off">NA</span>
                                    </div>
                                </label>
                                @endif
                            </td>
                            @endcan
                            
                            <td>
                                <span
                                    class="text-capitalize">{{ translate(str_replace('_', ' ', $order->delivery_status)) }}</span>
                            </td>
                            <td id="pyaments_status_change{{$order->id}}">
                                @if ($order->payment_status == 'paid')
                                    <span style="cursor: pointer" onclick="changePaid(<?php echo $order->id  ?>)" class=" badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                @else
                                    <span style="cursor: pointer" onclick="changeUnpaid(<?php echo $order->id  ?>)" class=" badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                @endif
                            </td>
                            @if (isset($order->combined_order->create_money_receipt))
                                @if ($order->combined_order->create_money_receipt == 1)
                                    <td>
                                        <a href="{{route('orders.money_receipt.create', $order->combined_order->code ?? '')}}" class="btn btn-primary btn-xs">Edit</a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{route('orders.money_receipt.create', $order->combined_order->code ?? '')}}" class="btn btn-primary btn-xs">create</a>
                                    </td>
                                @endif
                            @endif
                            
                            <td>
                                <span
                                    class="text-capitalize">{{ date('d-m-Y h:i s A', strtotime($order->created_at)) }}</span>
                            </td>
                            @php
                                $bool = 0;
                            @endphp
                            @can('admin_approved')
                            @php
                                $bool = 1;
                            @endphp
                            @endcan 
                            @if ($order->is_approved == 1 || is_null($order->is_approved) || $bool == 1)
                                <td class="text-right">
                                    @can('view_orders')
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('orders.show', $order->id) }}" title="{{ translate('View') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                    @endcan
                                    @can('invoice_download')
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                            title="{{ translate('Print Invoice') }}" href="javascript:void(0)"
                                            onclick="print_invoice('{{ route('orders.invoice.print', $order->id) }}')">
                                            <i class="las la-print"></i>
                                        </a>
                                    @endcan
                                    @can('invoice_download')
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                            title="{{ translate('Print Common Invoice') }}" href="javascript:void(0)"
                                            onclick="print_invoice('{{ route('orders.invoice.print', [$order->id,1]) }}')">
                                            <i class="la fab la-wpforms"></i>
                                        </a>
                                    @endcan
                                    @can('invoice_download')
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                            title="{{ translate('Print Challan') }}" href="javascript:void(0)"
                                            onclick="print_invoice('{{ route('orders.invoice.print', [$order->id,2]) }}')">
                                            <i class="la la-truck"></i>
                                        </a>
                                    @endcan
                                    @can('invoice_download')
                                        <!-- <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                            href="{{ route('orders.invoice.download', $order->id) }}"
                                            title="{{ translate('Download Invoice') }}">
                                            <i class="las la-download"></i>
                                        </a> -->
                                    @endcan
                                    @can('invoice_download')
                                        @if (isset($order->combined_order->create_money_receipt))
                                            @if ($order->combined_order->create_money_receipt == 1)
                                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                                title="{{ translate('Print Money receipt') }}" href="javascript:void(0)"
                                                onclick="print_invoice('{{ route('orders.money_receipt.print', $order->combined_order->code ?? '') }}')">
                                                <i class="la la-receipt"></i>
                                            </a>
                                            @endif
                                        @endif
                                        
                                    @endcan
                                    @can('delete_orders')
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('orders.destroy', $order->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @endcan
                                </td>
                                @else
                                <td>
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('orders.show', $order->id) }}" title="{{ translate('View') }}">
                                            <i class="las la-eye"></i>
                                    </a>
                                </td>
                            @endif
                            
                       
                        </tr>
                    @endforeach
                </tbody>
               
            </table>
            {{$orders->links()}}
        </div>
    </div>

@endsection

@section('modal')
    @include('backend.inc.delete_modal')
@endsection

@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#dtBasicExample').DataTable();
        $('.dataTables_length').addClass('bs-select');
    });
    $('#dtBasicExample').dataTable({
        "oLanguage": {
        "sSearch": "Search By city"
        }
    });

        function sort_orders(el) {
            $('#sort_orders').submit();
        }

        function print_invoice(url) {
            var h = $(window).height();
            var w = $(window).width();
            window.open(url, '_blank', 'height=' + h + ',width=' + w + ',scrollbars=yes,status=no');
        }

        $('body').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            // if($("#start_date").val() !== "" && $("#paytomentDate").val() !== ""){
                $( "#sort_orders" ).submit();
            // }else{
            //     alert("Please select Start & End Date");
            // }

        }
        });

        function changeUnpaid(order_id){
            Swal.fire({
                title: 'Want to Change It To to Paid?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
                }).then((result) => {
                if (result.isConfirmed) {
                    var data = {
                        order_number:order_id
                    }
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }); 
                   $.ajax({
                    type: "POST",
                    url: "{{route('order.change_payment_unpaid')}}",
                    data: data,
                    success: function (response) {
                        if(response.status == 200)
                        {
                            Swal.fire(
                            'Paid!',
                            'Payment status changed successfully.',
                            'success'
                            )
                              // id uses payments_status_change but in dom it's autometically add -detail
                            $('#pyaments_status_change'+order_id).html('<span style="cursor: pointer" onclick="changePaid('+order_id+')" class="span_status badge badge-inline badge-success">Paid</span>');
                        }                           
                    }
                   });
                }
            })

        }
        function changePaid(order_id)
        {
            Swal.fire({
                title: 'Want to Change It To Unpaid?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
                }).then((result) => {
                if (result.isConfirmed) {
                    var data = {
                        order_number:order_id
                    }
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }); 
                   $.ajax({
                    type: "POST",
                    url: "{{route('order.change_payment_paid')}}",
                    data: data,
                    success: function (response) {
                        if(response.status == 200)
                        {
                            Swal.fire(
                            'Unpaid!',
                            'Payment status changed successfully.',
                            'success'
                            )
                            // id uses payments_status_change but in dom it's autometically add -detail
                            $('#pyaments_status_change'+order_id).html('<span style="cursor: pointer" onclick="changeUnpaid('+order_id+')" class="span_status badge badge-inline badge-danger">Unpaid</span>');

                        }                           
                    }
                   });
                }
            })
        }

        //start order approval function 
        function approved_order(id)
        {
            
            var is_approved = $('#toggle-event'+id).prop('checked');
            if(is_approved){
                    is_approved = 1;
            }else{
                is_approved = 0;

            }
            var data = {
                approved_value: is_approved,
                order_id:id
            }
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{route('orders.approved_order')}}",
                data: data,
                success: function (response) {
                    
                    if(response.approved_val == 1){
                        Swal.fire({
                            icon: 'success',
                            title: 'Order Approved Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Order Un Approved Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
            });


        }



</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
