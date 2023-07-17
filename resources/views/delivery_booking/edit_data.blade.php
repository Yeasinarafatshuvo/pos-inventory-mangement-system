@extends('backend.layouts.app')

@section('content')


    <style>
        header img  {
            display: block;
            margin: 0 auto;
        }
        header span{
            position: absolute;
            top: 36%;
            left: 18%;
            font-size: 28px;
            color: #fff;
        }
        .para{
            margin-left: 13%;
            width: 75%;
        }
        .btn-success{
            margin-left: 42%;
            margin-top: 2%;
            cursor: pointer;
        }
        h2{
            color: #218838;
        }
        section{
            margin-top:20px;
        }

    </style>

    <main class="container mt-3">

            <div class="">
                <h6 class="text-center">Rewrite Book Number Of {{$editData->id}}</h6>

            </div>

        <form action="{{url('admin/update/'.$editData->id)}}" method="POST">

            @csrf

            <div class="row mt-2">
                <div class="col-md-6">
                    <div id="delivery_man_select">

                    </div>

                </div>

                <div class="col-md-6">
                    <div id="select_product_code">

                    </div>
                </div>

            </div>


            <div class="row mt-2">
                <div class="col-md-6">
                    <div id="select_date">

                    </div>
                </div>

                <div class="col-md-6">
                    <div id="select_time_slot">

                    </div>

                </div>

            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-info btn-lg">Update Booking</button>
            </div>
    </form>




    </main>
@endsection


@section('script')

        <script type="text/javascript">
            var deliverymanData;
            var productData;
            var editData;
            //select deliveyman
            $(document).ready(function() {
                var url = "{{URL('/admin/ajaxdata')}}";
                $.ajax({
                    url: "/admin/ajaxData",
                    type: "POST",
                    data:{
                        _token:'{{ csrf_token() }}'
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(dataResult)
                    {
                        console.log(dataResult);
                        deliverymanData = dataResult.data;
                        editData = dataResult.data4;
                        console.log('edit data row:',editData)
                        console.log(' deliverymandata row:',deliverymanData)

                        var drop_down="";
                        if(deliverymanData){
                            $.each(deliverymanData,function(index,row){
                                drop_down += '<option  value="'+row.delivery_man+'">'+row.delivery_man+'</option>';

                                    var add_deliveryman ='<div id ="" class="form-group">' +
                                        '<label class="form-label">Select Delivery Man</label>'+
                                        '<select id="selectDeliveryman"  name="delivery_man" class="custom-select text-center deliveryman">' +
                                        '<option> select delivery man</option>'+
                                        drop_down+
                                        '</select>' +
                                    '</div>';

                                    $("#delivery_man_select").html(add_deliveryman);
                                //$('#selectDeliveryman option[value= row.delivery_man]').attr('selected','selected');
                            })
                        }
                    }
                });
            });


            //select product code
            $(document).ready(function() {
                var url = "{{URL('/admin/ajaxdata')}}";
                $.ajax({
                    url: "/admin/ajaxData",
                    type: "POST",
                    data:{
                        _token:'{{ csrf_token() }}'
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(dataResult)
                    {
                        productData = dataResult.data2;
                        var drop_down2="";
                        $.each(productData, function(index, row){
                            drop_down2 += '<option value="'+row.id+'">'+row.code+'</option>';

                        })

                        var code=
                        '<div id ="" class="form-group">' +
                            '<label class="form-label">Select Product Code</label>'+
                            '<select name="order_id" class="custom-select text-center">' +
                            '<optio selected> -- select an option -- </option>'+
                            drop_down2+
                            +'</select>' +
                        '</div>';

                        $("#select_product_code").html(code);
                    }
                });
            });

            //select date
            var html = '<div id ="student_id" class="form-group">' +
                '<label class="form-label">Select Date</label>'+
                '<div class="input-group date" >'+
                    '<input type="date" class="form-control text-center" name="date" value="{{$editData->date}}">'+
                '</div>'+

            '</div>';

            $("#select_date").append(html);

            //select tiem slot
            var html = '<div id ="student_id" class="form-group">' +
                '<label class="form-label">Select Time Slot</label>'+
                '<select name="slot" class="custom-select  text-center">' +
                    <?php
                                $time = strtotime('10:00');
                                $add_time=0;
                                $previous_time='10:00';
                                for($i=1;$i<15;$i++){
                                    $add_time +=30;
                                    if($i==1){
                                        $previous_time=$previous_time;
                                    }else{
                                        $previous_time=$startTime;
                                    }
                                    $startTime = date("h:i", strtotime('+'.$add_time.' minutes', $time));
                                    ?>
                                    '<option <?php if($editData->slot){echo "selected";} ?> value="{{$previous_time}} to {{$startTime}}">{{$previous_time}} to {{$startTime}}</option>'+
                                <?php
                                    }
                            ?>

                +'</select>' +

            '</div>';

            $("#select_time_slot").append(html);
        </script>
@endsection




