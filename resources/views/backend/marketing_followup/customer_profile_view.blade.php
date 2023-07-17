@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<style>



</style>
@section('content')
<div class="row">
    <div class="col-md-4">
        
    </div>
    <div class="col-md-8">

    </div>
</div>
<div class="card mb-3">
  <div class="d-flex justify-content-center">
    <div class="row g-0">
      <div class="col-md-5">
        <img src="{{ asset('images/person_8x10_62.png') }}" class="img-fluid rounded-start img-thumbnail" alt="profile image">
      </div>
      <div class="col-md-7">
        <div class="card-body float-start">

        @foreach($user_data as $key => $user_data)

          <h5 class="card-title font-weight-bold">{{getUserName($user_data->id)}}</h5>
          <p class="card-text">{{$user_data->user_type}}</p>
          <p class="card-text">{{$user_data->phone}}</p>
          <p class="card-text">{{$user_data->email}}</p>

        @endforeach
        </div>
      </div>
    </div>
  </div>

</div>
<div class="card my-3"> {{-- Card Start --}}
  <div class="card-header">
      {{ translate('Orders of this customer') }}
  </div>
  <div class="card-body"> {{-- Card Body Start --}}
    <table id="dtBasicExample" class="table table-striped table-bordered table-hover text-center" style="width:100%">
          <thead>
              <tr>
                  <th>#</th>
                  <th>{{ translate('Order Code') }}</th>
                  <th>{{ translate('Amount') }}</th>
                  <th data-breakpoints="lg">{{ translate('Delivery Status') }}</th>
                  <th data-breakpoints="lg">{{ translate('Payment Status') }}</th>
                  </th>
              </tr>
          </thead>
          <tbody>
            @foreach($order_data as $key => $order)
              <tr>
                  <td>{{ ($key+1)}}</td>
                  <td>{{$order->code}}</td>
                  <td>{{$order->grand_total}}</td>
                  <td>{{$order->delivery_status}}</td>
                  <td>{{$order->payment_status}}</td>
              </tr>
            @endforeach
          </tbody>
      </table>
  </div>  {{-- Card Body End --}}
</div>{{-- Card End --}}
<div class="row">
  <div class="col-md-12">
    <div class="container">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#cart_product">Cart Product</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#whishlist_product">Whishlist Product</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#reviewed_product">Reviewd Product</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#followed_shop">Followed Shop</a>
        </li>
      </ul>
    
      <div class="tab-content">
        <div id="cart_product" class="tab-pane fade show active">
          <div class="row">
            <div class="col-md-12">
              <h3 class="text-center mt-3">Cart Product List</h3>
              <div class="card-body">
                <table id="dtBasicExample" class="table table-striped table-bordered table-hover text-center" style="width:100%">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>{{translate('Date')}}</th>
                              <th>{{translate('Product Name')}}</th>
                              <th>{{translate('Quantity')}}</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($cart_data as $key => $cart_data)
                              <tr>
                                  <td>{{ ($key+1)}}</td>
                                  <td>{{date("F j, Y", strtotime($cart_data->created_at))}}</td>
                                  <td class="text-info">{{getProductName($cart_data->product_id)}}</td>
                                  <td>{{$cart_data->quantity}}</td>
                              </tr>
                          @endforeach
          
                      </tbody>
                      <div class="aiz-pagination">
                      
                      </div>
                </table>
        
              </div>
            </div>
          </div>
        </div>
        <div id="whishlist_product" class="tab-pane fade">
          <div class="row">
            <div class="col-md-12">
              <h3 class="text-center mt-3">Whishlist Product</h3>
              <div class="card-body">
                <table id="dtBasicExample" class="table table-striped table-bordered table-hover text-center" style="width:100%">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>{{translate('Date')}}</th>
                              <th>{{translate('Product Name')}}</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($whishlist_data as $key => $whishlist_data)
                              <tr>
                                  <td>{{ ($key+1)}}</td>
                                  <td>{{date("F j, Y", strtotime($whishlist_data->created_at))}}</td>
                                  <td class="text-info">{{getProductName($whishlist_data->product_id)}}</td>
                              </tr>
                          @endforeach
          
                      </tbody>
                      <div class="aiz-pagination">
                      
                      </div>
                </table>
        
              </div>
            </div>
          </div>
        </div>
        <div id="reviewed_product" class="tab-pane fade">
          <div class="row">
            <div class="col-md-12">
              <h3 class="text-center mt-3">Reviewed Product List</h3>
              <div class="card-body">
                <table id="dtBasicExample" class="table table-striped table-bordered table-hover text-center" style="width:100%">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>{{translate('Date')}}</th>
                              <th>{{translate('Product Name')}}</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($review_data as $key => $review_data)
                              <tr>
                                  <td>{{ ($key+1)}}</td>
                                  <td>{{date("F j, Y", strtotime($review_data->created_at))}}</td>
                                  <td class="text-info">{{getProductName($review_data->product_id)}}</td>
                                  <td class="text-info">{{$review_data->comment}}</td>
                                  <td class="text-info">{{$review_data->rating}}</td>
                              </tr>
                          @endforeach
          
                      </tbody>
                      <div class="aiz-pagination">
                      
                      </div>
                </table>
        
              </div>
            </div>
          </div>
        </div>
        <div id="followed_shop" class="tab-pane fade">
          <div class="row">
            <div class="col-md-12">
              <h3 class="text-center mt-3">Followed Shop List</h3>
              <div class="card-body">
                <table id="dtBasicExample" class="table table-striped table-bordered table-hover text-center" style="width:100%">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>{{translate('Date')}}</th>
                              <th>{{translate('Shop Name')}}</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach($followed_shop_data as $key => $follow_shop)
                              <tr>
                                  <td>{{ ($key+1)}}</td>
                                  <td>{{date("F j, Y", strtotime($follow_shop->created_at))}}</td>
                                  <td class="text-info">{{$follow_shop->name}}</td>
                              </tr>
                          @endforeach
          
                      </tbody>
                      <div class="aiz-pagination">
                      
                      </div>
                </table>
        
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>






@endsection

@section('modal')
    @include('backend.inc.delete_modal')
@endsection

@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PrintArea/2.4.1/jquery.PrintArea.min.js" integrity="sha512-mPA/BA22QPGx1iuaMpZdSsXVsHUTr9OisxHDtdsYj73eDGWG2bTSTLTUOb4TG40JvUyjoTcLF+2srfRchwbodg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#dtBasicExample').DataTable({
        pageLength: 10,
        filter: true,
        deferRender: true,
        "searching": false,
    });

})

$('.toggle-button').on('click', function() {
  $('.left-sidebar').toggleClass('minimize'); 
});

$('.user-profile').on('click', function() {
  $('.left-sidebar').toggleClass('minimize'); 
});

$('.close-chat-btn').on('click', function() {
  $('.direct-messaging ').addClass('minimize'); 
});

$('.open-chat-btn').on('click', function() {
  $('.direct-messaging ').toggleClass('minimize'); 
});

$('.open-music-btn').on('click', function() {
  $('.music-player').toggleClass('show'); 
});

$('.open-timer-btn').on('click', function() {
  $('.timer-display').toggleClass('show'); 
});

</script>

@endsection
