@extends('backend.layouts.app')
@section('content')
<style>




</style>
    <div class="container">
       <div class="card">
        <select class="form-control" id="choos_email">
            <option selected value="0">Please Select a Email Type</option>
            <option value="formal">Formal Email</option>
            <option value="template">Template Email</option>
          </select>
       </div>
    </div>
@endsection




@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript">

$('#choos_email').change(function (e) { 
    e.preventDefault();

    if(this.value != 0){
        var data = {
            choose_type: this.value
        }
        if(this.value == 'formal'){
            window.location = '{{ route('email_system.multiple_write_body') }}'
        }else if(this.value == 'template'){
            window.location = '{{ route('email_system.multiple_template_mail_write_body') }}'
        }


    }
    
});






</script>
@endsection
