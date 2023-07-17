@extends('backend.layouts.app')
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
@section('content')
<style>
    
</style>

<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="text-center m-auto bg-primary text-white" style="text-decoration: underline;">Create Money Receipt</h4>
        </div>
            <div class="m-auto">
                <form action="{{route('orders.money_receipt.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 offset-md-2">
                            <div class="row">
                                <div class="col-md-2 col-form-label pl-4 font-weight-bold" style="font-size: 15px">
                                    Invoice Number:
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="order_invoice" readonly required class="form-control" value="{{$order_invoice_number}}">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-2 col-form-label pl-4 font-weight-bold" style="font-size: 15px">
                                    Total Payment:
                                </div>
                                <div class="col-md-6">
                                    <input type="number" name="total_payment_in_number" required  class="form-control" id="total_payment" value="">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-2 col-form-label pl-4 font-weight-bold" style="font-size: 15px">
                                    Customer Account name:
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="customer_account_name" required  class="form-control" id="total_payment" value="">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-2 col-form-label pl-4 font-weight-bold" style="font-size: 15px">
                                    Total Payment In Word:
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="total_payment_in_word" required  class="form-control" id="total_payment_in_word" value="" readonly>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-2 col-form-label pl-4 font-weight-bold" style="font-size: 15px">
                                    Checque No:
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="customer_checque_number" required  class="form-control" value="" >
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-2 col-form-label pl-4 font-weight-bold" style="font-size: 15px">
                                    Cheque Date:
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="customer_cheque_date"  id="datepicker"  class="form-control" value="" >
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-2 col-form-label pl-4 font-weight-bold" style="font-size: 15px">
                                Against Bill Info:
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="bill_info" required  class="form-control" value="" >
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-6 mt-1">
                                    <button style="width: 100%" class="btn btn-primary">submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    

@endsection

@section('script')
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script type="text/javascript">

    $( function() {
        $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
        
    });
    $( function() {
        $( "#datepickertwo" ).datepicker({ dateFormat: 'yy-mm-dd' });
    });

    $('#total_payment').keyup(function (e) { 

        var total_payment_value = this.value;
        if(!isNaN(total_payment_value.trim())){
            var number_to_word_string_val = numberToWords(total_payment_value);
            if(number_to_word_string_val === 'not a number'){
                $('#total_payment_in_word').val('');
            }else{
                $('#total_payment_in_word').val(number_to_word_string_val+ " taka only.");
            }
           
        }
    });

    function numberToWords(number) {  
        var digit = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];  
        var elevenSeries = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];  
        var countingByTens = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];  
        var shortScale = ['', 'thousand', 'million', 'billion', 'trillion'];  
  
        number = number.toString(); number = number.replace(/[\, ]/g, ''); if (number != parseFloat(number)) return 'not a number'; var x = number.indexOf('.'); if (x == -1) x = number.length; if (x > 15) return 'too big'; var n = number.split(''); var str = ''; var sk = 0; for (var i = 0; i < x; i++) { if ((x - i) % 3 == 2) { if (n[i] == '1') { str += elevenSeries[Number(n[i + 1])] + ' '; i++; sk = 1; } else if (n[i] != 0) { str += countingByTens[n[i] - 2] + ' '; sk = 1; } } else if (n[i] != 0) { str += digit[n[i]] + ' '; if ((x - i) % 3 == 0) str += 'hundred '; sk = 1; } if ((x - i) % 3 == 1) { if (sk) str += shortScale[(x - i - 1) / 3] + ' '; sk = 0; } } if (x != number.length) { var y = number.length; str += 'point '; for (var i = x + 1; i < y; i++) str += digit[n[i]] + ' '; } str = str.replace(/\number+/g, ' '); return str.trim();  
  
    }



    

        


    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
