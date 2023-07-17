@extends('backend.layouts.app')
<style>
      * {
          color:#7F7F7F;
          font-family:Arial,sans-serif;
          font-size:12px;
          font-weight:normal;
      }    
      #config{
          overflow: auto;
          margin-bottom: 10px;
      }
      .config{
          float: left;
          width: 200px;
          height: 250px;
          border: 1px solid #000;
          margin-left: 10px;
      }
      .config .title{
          font-weight: bold;
          text-align: center;
      }
      #submit{
          clear: both;
      }
      #barcodeTarget,
      #canvasTarget{
        margin-top: 20px;
      }        
    </style>

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="mb-0 h6 ml-3">{{ translate('Generate Barcode') }} - {{$_product_details->name}}</h5>
</div>
    <div class="container-fluid" id="print_table">
    @foreach ($barcode_arr as $key => $item)
    <!-- <div class="col-md-12" style="margin-bottom:10px;">
        <br>
        <?php if($key%2 !== 1){?>
            <br>
        <?php }?>
        {!! DNS1D::getBarcodeHTML("$item",'C128',2,80) !!}
        <span style="margin-left:50px;color:black;">{{$item}}</span>
    </div> -->
    <div class="col-md-12" style="">
        <?php if($key%2 !== 1){?>
        <?php }?>
        <!-- <br> -->
        {!! DNS1D::getBarcodeHTML("$item",'C128',2,80) !!}
        <span style="margin-left:80px;color:black;">{{$item}}</span>
    </div>
       
    @endforeach
    </div> 
    <a style="color:White;" onclick="printDiv()" class="btn btn-primary">Print</a>          
</div>



<script>
function printDiv() {

        var divName= "print_table";
        //var divName= "barcodeTarget";

            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
    }


</script>
@endsection
