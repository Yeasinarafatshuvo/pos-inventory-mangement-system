@extends('backend.layouts.app')
@section('content')
<style>

</style>
<div class="container" style="padding-top:70px;font-weight:bold">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 offset-md-4">
                    <form action="{{route('email_system.user_email_info.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file" style="font-size: 20px;">Choose Excel File</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                        <input type="submit" class="btn btn-primary" value="Import User Info">
        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
   
@section('script')
<script type="text/javascript">



</script>
@endsection
