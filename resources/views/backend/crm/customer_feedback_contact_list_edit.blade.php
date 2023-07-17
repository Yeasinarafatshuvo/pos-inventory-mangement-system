@extends('backend.layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
   #suggesstion-box li{
      list-style-type: none;
      cursor: pointer;
      padding: 5px;
      padding-left: 30px;
    }
    #suggesstion-box li:hover{
      color: green;

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
   
    <div class="col-md-8 offset-md-2">
        
        <div class="container">
            <h2 class="bg-primary text-center text-white">Customer Feedback Status Edit</h2>
   
            <form action="{{ route('customer_crm_feedback.update',$customer[0]->customer_id) }}" method="POST">
                @csrf
                <input type="hidden" name="feedback_id" value="{{$customer[0]->id}}">
                <input type="hidden" name="customer_id" value="{{$customer[0]->customer_id}}">
                <div class="form-group">
                    <label for="feedback_status">Status</label>
                    <select name="feedback_status" class="form-control" id="feedback_status">
                        <option selected value="{{$customer[0]->feedback_status}}">{{$customer[0]->feedback_status}}</option>
                        <option value="Positive">Positive</option>
                        <option value="Negative">Negative</option>
                        <option value="Ordered">Ordered</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="feedback_details" style="font-size: 20px">Enter Customer Feedback Details</label>
                    <textarea class="form-control" name="feedback_details" id="feedback_details" cols="30" rows="10">{{$customer[0]->feedback_details}}</textarea>
                </div>
                <div class="form-group">
                    <input id="select_product" type="text" class="form-control"  placeholder="Search By Product Name">
                    <div id="suggesstion-box" class="search-box_popup"></div> 
                </div>
                <div class="form-group" id="append_div">

                </div>

                <button disabled class="btn btn-primary" style="width: 100%; font-size:20px">Submit Customer Feedback</button>
            </form>
        </div>
    </div>
</div>
  
@endsection



@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>


<script>

    $("#select_product").keyup(function(){
        var value = $("#select_product").val();
        $("#suggesstion-box").html("");

        $.ajax({
            type:"GET",
            url:"{{route('pos.search')}}",
            data:{'search':value,'id':""},
            success:function(response){
            var data ="";
            var p_length = response == ""?0:response.match_products.data.length;

            for(i=0;i<p_length;i++){
                data += '<li onClick="selectProduct('+ response.match_products.data[i]["id"]+ ')">'+ response.match_products.data[i]["name"]+'</li>';
            }
            $("#suggesstion-box").show();
            if(value !== ""){
                $("#suggesstion-box").html(data);
            }
            else{
                $("#suggesstion-box").html("");
            }

            }

        });


    });

    function selectProduct(id) {
      
        $.ajax({
            type:"GET",
            url:"{{route('pos.search')}}",
            data:{'search':"",'id':id},

            success:function(response){
                    var countRow = 1;
                var row = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong  value="${response.match_products.id}">${response.match_products.name}</strong>
                        <input class="check_duplicate" type="hidden" name="products[]" id="product_id" value="${response.match_products.id}"/>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>`;

                    //checking duplicate product
                    var newValue = 0;
                    $('.check_duplicate').each(function(){
                        if(this.value == response.match_products.id){
                            newValue++;
                        }
                    });

                    if(!(newValue == 1))
                    {  
                        $('#append_div').append(row);
                        $('#select_product').val('');
                        $("#suggesstion-box").html("");
                    }
                  
            }
        })
    };

    $('#feedback_details').mouseover(function (e) { 
        e.preventDefault();
        var feedback_details = this.value;
        if(feedback_details != ""){
            console.log(this.value);
            $(":submit").removeAttr("disabled");
        }else{
            $(":submit").attr("disabled", true);
        }
        
    });

        
</script>
@endsection
