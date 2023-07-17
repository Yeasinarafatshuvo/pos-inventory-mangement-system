@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<style>
    .shadow_class{
        box-shadow: 0 0 50px #ccc;
    }
</style>
@section('content')


@if (session()->has('success'))
    <div class="alert alert-success" id="success-alert">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(function() {
            $('#success-alert').fadeOut('slow');
        }, 1000);
    </script>
@endif

    <div class="row">
        {{-- <div class="col-lg-3">
            <div class="card">
                <div class="card-body text-center">
                    <span class="avatar avatar-xxl mb-3">
                        @if ($user->avatar != null)
                            <img src="{{ uploaded_asset($user->avatar) }}">
                        @else
                            <img src="{{ my_asset('assets/frontend/default/img/avatar-place.png') }}0"
                                onerror="this.onerror=null;this.src='{{ static_asset('/assets/img/avatar-place.png') }}';">
                        @endif
                    </span>
                    <h1 class="h5 mb-1">{{ $user->name }}</h1>
                    <div class="text-left mt-5">
                        <h6 class="separator mb-4 text-left"><span
                                class="bg-white pr-3">{{ translate('Account Information') }}</span></h6>
                        <p class="text-muted">
                            <strong>{{ translate('Full Name') }} :</strong>
                            <span class="ml-2">{{ $user->name }}</span>
                        </p>
                        <p class="text-muted"><strong>{{ translate('Email') }} :</strong>
                            <span class="ml-2">
                                {{ $user->email }}
                            </span>
                        </p>
                        <p class="text-muted"><strong>{{ translate('Address') }} :</strong>
                            <span class="ml-2">
                                {{ getCustomerAddress($user->id) }}
                            </span>
                        </p>
                        <p class="text-muted"><strong>{{ translate('Phone') }} :</strong>
                            <span class="ml-2">
                                {{ $user->phone }}
                            </span>
                        </p>
                        <p class="text-muted"><strong>{{ translate('Registration Date') }} :</strong>
                            <span class="ml-2">
                                {{ $user->created_at }}
                            </span>
                        </p>
                        <p class="text-muted"><strong>{{ translate('Balance') }} :</strong>
                            <span class="ml-2">
                                {{ format_price($user->balance) }}
                            </span>
                        </p>
                    </div>
                    <div class="text-left mt-5">
                        <h6 class="separator mb-4 text-left">
                            <span class="bg-white pr-3">{{ translate('Others Information') }}
                            </span>
                        </h6>
                        <p class="text-muted">
                            <strong>{{ translate('Number of Orders') }} :</strong>
                            <span class="ml-2">{{ $user->orders()->count() }}</span>
                        </p>
                        <p class="text-muted">
                            <strong>{{ translate('Ordered Amount') }} :</strong>
                            <span class="ml-2">{{ format_price($user->orders()->sum('grand_total')) }}</span>
                        </p>
                        <p class="text-muted">
                            <strong>{{ translate('Number of items in cart') }} :</strong>
                            <span class="ml-2">{{ $user->carts()->count() }}</span>
                        </p>
                        <p class="text-muted">
                            <strong>{{ translate('Number of items in wishlist') }} :</strong>
                            <span class="ml-2">{{ $user->wishlists()->count() }}</span>
                        </p>
                        <p class="text-muted">
                            <strong>{{ translate('Total reviewed products') }} :</strong>
                            <span class="ml-2">{{ $user->reviews()->count() }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-11 offset-lg-1">
            <div class="row">
                <div class="col-md-12">
                        <div class="horizontal-tabs">
                           <ul class="nav nav-tabs" role="tablist">
                              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#dashboard_h" role="tab" aria-controls="dashboard">Dashboard</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#home-h" role="tab" aria-controls="home">General</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#comments_h" role="tab" aria-controls="comments">Comments</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reminder_h" role="tab" aria-controls="reminder" id="reminder_tab">Reminder</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#assign_to_h" role="tab" aria-controls="assign_to">Assign To</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#order_list_h" role="tab" aria-controls="order_list">Order List</a></li>
                           </ul>
                           <div class="tab-content">
                            {{-- Dashboard Section --}}
                            <div class="tab-pane" id="dashboard_h" role="tabpanel">
                                <div class="sv-tab-panel">
                                    <div class="row my-3">
                                        <div class="col-md-12">
                                            <div class="tab-content" id="v-pills-tabContent">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                              <div class="tab-pane active" id="home-h" role="tabpanel">
                                {{-- <div class="sv-tab-panel">Home Panel</div> --}}
                                <div class="row my-3">
                                    <div class="col-md-3">
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active" id="v-pills-basic-tab" data-toggle="pill" href="#v-pills-basic" role="tab" aria-controls="v-pills-basic" aria-selected="true">Basic</a>
                                            <a class="nav-link" id="v-pills-documents-tab" data-toggle="pill" href="#v-pills-documents" role="tab" aria-controls="v-pills-documents" aria-selected="false">Documents</a>
                                            <a class="nav-link" id="v-pills-contact-tab" data-toggle="pill" href="#v-pills-contact" role="tab" aria-controls="v-pills-contact" aria-selected="false">Contact Person</a>
                                            <a class="nav-link" id="v-pills-address-tab" data-toggle="pill" href="#v-pills-address" role="tab" aria-controls="v-pills-address" aria-selected="false">Address</a>
                                            <a class="nav-link" id="v-pills-reference-tab" data-toggle="pill" href="#v-pills-reference" role="tab" aria-controls="v-pills-reference" aria-selected="false">Reference</a>
                                            <a class="nav-link" id="v-pills-bank_account-tab" data-toggle="pill" href="#v-pills-bank_account" role="tab" aria-controls="v-pills-bank_account" aria-selected="false">Bank Accounts</a>
                                          </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            <div class="tab-pane fade show active" id="v-pills-basic" role="tabpanel" aria-labelledby="v-pills-basic-tab"> 
                                                <div class="row">
                                                    <div class="col-md-12 my-3">
                                                        <img src="{{ asset('images/person_8x10_62.png') }}" class="rounded float-left img-thumbnail" alt="profile image">
                                                    </div>
                                                        <div class="row">
                                                            <div class="col-md-6 my-2">
                                                            @foreach($user_data as $key => $user_info)
                                                                <label for="name">Name</label>
                                                                <input type="text" id="name" class="form-control" placeholder="Name" value="{{ $user_info->name }}">
                                                            </div>
                                                            <div class="col-md-6 my-2">
                                                                <label for="phone_number">Phone Number</label>
                                                                <input type="text" id="phone_number" class="form-control" placeholder="Phone Number"  value="{{ $user_info->phone }}">
                                                            </div>
                                                            <div class="col-md-6 my-2">
                                                                <label for="email_address">Email Address</label>
                                                                <input type="text" id="email_address" class="form-control" placeholder="Email Address"  value="{{ $user_info->email }}">
                                                            </div>
                                                            <div class="col-md-6 my-2">
                                                                <label for="client_type">Client Type</label>
                                                                <select id="client_type" class="form-control" name="client_type">
                                                                    <option value="1" <?php if($user_info->customer_type == 1){ echo 'selected'; } ?>>Customer</option>
                                                                    <option value="2" <?php if($user_info->customer_type == 2){ echo 'selected'; } ?>>Dealer</option>
                                                                    <option value="3" <?php if($user_info->customer_type == 3){ echo 'selected'; } ?>>Corporate</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 my-2 text-center">
                                                                <button class="btn btn-info" onclick="basic_update_function({{ $user_info->id }})">Update</button>
                                                            </div>

                                                          @endforeach
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-documents" role="tabpanel" aria-labelledby="v-pills-documents-tab"> 
                                                @foreach($user_data as $key => $user_info)
                                                    <form action="{{ route('customer_profile.document_update') }}" enctype="multipart/form-data" method="post">
                                                        <div class="row">
                                                            @csrf
                                                            <div class="col-md-6 my-2">
                                                                <label for="#document_name">Document Name <span class="text-danger">*</span></label>
                                                                <input type="text" id="document_name" name="document_name" class="form-control" placeholder="Enter Document Name" value="">
                                                            </div>
                                                            <div class="col-md-6 my-2">
                                                                <label for="#document_file">File <span class="text-danger">*</span></label>
                                                                <input type="file" class="form-control" id="document_file" name="document_file" value="">
                                                            </div>
                                                            <input type="hidden" name="user_id" value="{{ $user_info->id }}">
                                                            <div class="col-md-4 my-2 offset-md-4">
                                                                <button class="btn btn-primary form-control">Create</button>
                                                            </div>
                                                            
                                                        </div>
                                                    </form>
                                                @endforeach
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-contact" role="tabpanel" aria-labelledby="v-pills-contact-tab"> 
                                                @foreach($user_data as $key => $user_info)
                                                    <form action="{{ route('customer_profile.phone_update') }}" method="post">
                                                        <div class="row">
                                                            @csrf
                                                            <div class="col-md-8 my-2">
                                                                <label for="#phone_number">Phone Number</label>
                                                                <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Enter Phone Number" value="{{ $user_info->phone }}">
                                                            </div>
                                                            <input type="hidden" name="user_id" value="{{ $user_info->id }}">
                                                            <div class="col-md-8 my-2">
                                                                <button class="btn btn-primary">Update</button>
                                                            </div>
                                                            
                                                        </div>
                                                    </form>
                                                @endforeach
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-address" role="tabpanel" aria-labelledby="v-pills-address-tab"> 
                                                <form action="{{ route('customer_profile.address_update') }}" method="post">
                                                    @csrf
                                                    <div class="my-3">
                                                        <label for="office_address" class="form-label">Office Address <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="office_address" id="office_address" rows="3">{{ $user_address->address }}</textarea>
                                                        <input type="hidden" name="user_id" value="{{ $user_info->id }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="state">Select State</label>
                                                        <select  class="form-control aiz-selectpicker close_all_info" id="state" name="state" data-live-search="true">
                                                            <option value="">Select State</option> 
                                                            @foreach($states as $state)
                                                                <option value="{{ $state->id }}" {{ $user_address->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="city">Select City</label>
                                                        <select class="form-control aiz-selectpicker close_all_info" id="city" name="city" data-live-search="true">
                                                            <option value="">Select City</option>
                                                            @foreach($cities as $city)
                                                            <option value="{{ $city->id }}" {{ $user_address->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                                        @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-reference" role="tabpanel" aria-labelledby="v-pills-reference-tab"> 
                                                <form action="{{ route('customer_profile.reference_update') }}" method="post">
                                                    <div class="row">
                                                        @csrf
                                                        <div class="col-md-8 my-2">
                                                            <label for="#reference_name">Name</label>
                                                            <input type="text" id="reference_name" name="reference_name" class="form-control" placeholder="Enter Name" value="{{ $references->reference_by }}">
                                                        </div>
                                                        <input type="hidden" name="user_id" value="{{ $user_info->id }}">
                                                        <div class="col-md-8 my-2">
                                                            <button class="btn btn-primary">Update</button>
                                                        </div>
                                                        
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-bank_account" role="tabpanel" aria-labelledby="v-pills-bank_account-tab"> 
                                                <form action="{{ route('customer_profile.bank_info_update') }}" method="post">
                                                    <div class="row">
                                                        @csrf
                                                        <div class="col-md-8 my-2">
                                                            <label for="#bank_info">Bank Information</label>
                                                            <textarea id="bank_info" name="bank_info" class="form-control" placeholder="Enter Bank Information">{{ $bank_info->bank_information }}</textarea>
                                                        </div>
                                                        <input type="hidden" name="user_id" value="{{ $user_info->id }}">
                                                        <div class="col-md-8 my-2">
                                                            <button class="btn btn-primary">Update</button>
                                                        </div>
                                                        
                                                    </div>
                                                </form>
                                            </div>
                                          </div>
                                    </div>
                                </div>
                            </div>
                                {{-- Comments Section --}}
                            <div class="tab-pane" id="comments_h" role="tabpanel">
                                <div class="sv-tab-panel">
                                    <div class="row my-3">
                                        <div class="col-md-3">
                                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                <a class="nav-link" id="v-pills-comments-tab" data-toggle="pill" href="#v-pills-comments" role="tab" aria-controls="v-pills-comments" aria-selected="false">Comments</a>
                                                <a class="nav-link active" id="v-pills-add-comments-tab" data-toggle="pill" href="#v-pills-add-comments" role="tab" aria-controls="v-pills-add-comments" aria-selected="false">Add Comments</a>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="tab-content" id="v-pills-tabContent">
                                                <div class="tab-pane fade" id="v-pills-comments" role="tabpanel" aria-labelledby="v-pills-comments-tab"> 
                                                    {{-- <table id="dtBasicExample" class="table table-striped table-bordered table-sm p-2 text-center" cellspacing="0" width="100%"> --}}
                                                        <table id="dtBasicExample" class="table table-striped table-bordered" style="width:100%">
                                                            <thead>
                                                            <tr>
                                                                <th scope="col">SL</th>
                                                                <th scope="col">Date</th>
                                                                <th scope="col">Comment</th>
                                                                <th scope="col">Product List</th>
                                                                <th scope="col">Added By</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="crm_view_id">
                                                            </tbody>
                                                      </table>
                                                </div>
                                                <div class="tab-pane fade show active" id="v-pills-add-comments" role="tabpanel" aria-labelledby="v-pills-add-comments-tab">
                                                    <label for="#comment">Comment <span class="text-danger">*</span></label>
                                                    <textarea id="add_comment" class="aiz-text-editor add_comment_vanish" name="description"></textarea>
                                                    <input type="hidden" id="customer_id" value="{{ $user->id }}">
                                                    <table>
                                                        <tbody class="search_product_add">

                                                        </tbody>
                                                    </table>
                                                    <label for="#product_name_search">Interested Product</label>
                                                    <input type="text" class="form-control mb-3 product_name_search" value="" placeholder="product search here" id="product_name_search_id">
                                                    <input type="text" hidden class="form-control mb-3" id="product_search_result" value="" >
                                                    <table class="table" id="product_show_table">
                                                        <tbody class="product_search_result shadow_class">
                                                        </tbody>
                                                    </table>
                                                    <input type="button" id="add_comment_function" class="btn btn-primary" value="Add Comment">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Reminder Section --}}
                              <div class="tab-pane" id="reminder_h" role="tabpanel">
                                <div class="sv-tab-panel">
                                    <div class="row my-3">
                                        <div class="col-md-3">
                                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                <a class="nav-link active" id="v-pills-reminders-tab" data-toggle="pill" href="#v-pills-reminders" role="tab" aria-controls="v-pills-reminders" aria-selected="false">Reminders</a>
                                                <a class="nav-link" id="v-pills-add-reminders-tab" data-toggle="pill" href="#v-pills-add-reminders" role="tab" aria-controls="v-pills-add-reminders" aria-selected="false">Add Reminders</a>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="tab-content" id="v-pills-tabContent">
                                                <div class="tab-pane fade show active" id="v-pills-reminders" role="tabpanel" aria-labelledby="v-pills-reminders-tab"> 
                                                    <table id="dtBasicExample1" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">SL</th>
                                                            <th scope="col">Note</th>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Time</th>
                                                            <th scope="col">Added By</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($reminder_rows as $key => $reminder_row)
                                                            <tr>
                                                                <td class="text-center pt-4">{{ $key+1 }}</td>
                                                                <td class="text-center pt-4">{{filter_var($reminder_row->note, FILTER_SANITIZE_STRING)}}</td>
                                                                <td class="text-center pt-4">{{ date('d/m/y', strtotime($reminder_row->date))}}</td>
                                                                <td class="text-center pt-4">{{date('h:i A', strtotime($reminder_row->time))}}</td>
                                                                <td class="text-center pt-4">{{getUserName($reminder_row->assign_by)}}</td>
                                                                <td class="text-center pt-4">{{$reminder_row->status}}</td>
                                                                <td class="text-center pt-4">
                                                                    <button type="button" class="btn btn-primary" onclick="reminder_edit({{$reminder_row->id}})" data-toggle="modal" data-target="#edit_reminder">Edit</button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        
                                                        </tbody>
                                                  </table>
                                                </div>
                                                <div class="tab-pane fade" id="v-pills-add-reminders" role="tabpanel" aria-labelledby="v-pills-add-reminders-tab"> 
                                                    <label for="#date_time_picker">Date Timer Picker <span class="text-danger">*</span></label>
                                                    <input type="datetime-local" id="date_time_picker" class="form-control mb-3" placeholder="Select DateTime">
                                                    <label for="#reminder">Reminder Note <span class="text-danger">*</span></label>
                                                    <textarea id="reminder_text" class="aiz-text-editor" name="description"></textarea>
                                                    <table>
                                                        <tbody class="search_product_add_reminder">

                                                        </tbody>
                                                    </table>
                                                    
                                                    <label for="">Interested Product</label>
                                                    <input type="text" class="form-control mb-3 product_search_reminder" oninput="reminderProductSearch()" placeholder="product search here" id="product_search_reminder_id">

                                                    <table class="table" id="product_show_reminder_table">
                                                        <tbody class="product_search_result_reminder shadow_class">
                                                        </tbody>
                                                    </table>

                                                    <label for="">Assign To</label>
                                                    <input type="text" class="form-control mb-3 user_search_reminder" oninput="reminderAssignSearch()" placeholder="Search Assigned to Person">
                                                    <table>
                                                        <tbody class="search_user_add_reminder">

                                                        </tbody>
                                                    </table>

                                                    <table class="table" id="user_show_reminder_table">
                                                        <tbody class="user_search_result_reminder shadow_class">
                                                        </tbody>
                                                    </table>

                                                    <select class="form-select form-control mb-3 reminder_status" aria-label="Default select example">
                                                        <option selected value="0">Select Status</option>
                                                        <option value="1">Pending</option>
                                                        <option value="2">Confirm</option>
                                                        <option value="3">Rejected</option>
                                                    </select>
                                                    <input type="hidden" id="user_id" value="{{ Auth::user()->id }}">
                                                    <input type="hidden" id="customer_id" value="{{ $user->id }}">
                                                    <input type="button" id="reminder_submit" class="btn btn-primary" value="Add Reminder">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Assign To sectoin --}}
                            <div class="tab-pane" id="assign_to_h" role="tabpanel">
                                <div class="sv-tab-panel">
                                    <div class="row my-3">
                                        <div class="col-md-3">
                                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                <a class="nav-link" id="v-pills-assign-tab" onclick="assignToFunction({{ $user->id }},{{ Auth::user()->id }},'{{ Auth::user()->user_type }}')" data-toggle="pill" href="#v-pills-assign" role="tab" aria-controls="v-pills-assign" aria-selected="false">Assign To</a>
                                                <a class="nav-link active" id="v-pills-add-assign-tab" data-toggle="pill" href="#v-pills-add-assign" role="tab" aria-controls="v-pills-add-assign" aria-selected="false">Add Assign To</a>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="tab-content" id="v-pills-tabContent">
                                                <div class="tab-pane fade" id="v-pills-assign" role="tabpanel" aria-labelledby="v-pills-assign-tab"> 
                                                    <table id="dtBasicExample1" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">SL</th>
                                                                <th scope="col">Customer Name</th>
                                                                <th scope="col">Assign To</th>
                                                                <th scope="col">Assign By</th>
                                                                <th scope="col">Date</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="assign_row">

                                                        </tbody>
                                                  </table>
                                                </div>
                                                <div class="tab-pane fade show active" id="v-pills-add-assign" role="tabpanel" aria-labelledby="v-pills-add-assign-tab"> 
                                                    <div class="col-md-8 offset-md-2 text-center">
                                                        <label for="#assign_to_add">Assign To <span class="text-danger">*</span></label>
                                                        <input type="text" id="assign_to_add" class="form-control" oninput="assign_user_search(this)" placeholder="assign to name">
                                                        <input type="hidden" id="user_id_assign_to" value="{{ Auth::user()->id }}">
                                                        <input type="hidden" id="customer_id_assign_to" value="{{ $user->id }}">
                                                    </div>
                                                    <div class="col-md-8 offset-md-2 mt-3">
                                                        <table class="col-12 w-100">
                                                            <tbody class="user_search_add_assignto">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-8 offset-md-2">
                                                        <table class="table" id="user_show_assignto_table">
                                                            <tbody class="user_search_result_assignto shadow_class">
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="col-md-8 offset-md-2">
                                                        <input type="submit" class="form-control btn btn-primary assign_to_add_submit">
                                                    </div>
                                                        
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Order List Section --}}
                              <div class="tab-pane" id="order_list_h" role="tabpanel">
                                <div class="sv-tab-panel">
                                    <div class="card my-3"> {{-- Card Start --}}
                                        <div class="card-header">
                                            {{ translate('Orders of this customer') }}
                                        </div>
                                        <div class="card-body"> {{-- Card Body Start --}}
                                            <table class="table aiz-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ translate('Order Code') }}</th>
                                                        <th>{{ translate('Amount') }}</th>
                                                        <th data-breakpoints="lg">{{ translate('Delivery Status') }}</th>
                                                        <th data-breakpoints="lg">{{ translate('Payment Status') }}</th>
                                                        <th data-breakpoints="lg" class="text-right" width="15%">{{ translate('options') }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($user->orders()->latest()->get()
                                as $key => $order)
                                                        <tr>
                                                            <td>
                                                                {{ $key + 1 }}
                                                            </td>
                                                            <td>
                                                                {{ $order->code }}
                                                            </td>
                                                            <td>
                                                                {{ format_price($order->grand_total) }}
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="text-capitalize">{{ translate(str_replace('_', ' ', $order->delivery_status)) }}</span>
                                                            </td>
                                                            <td>
                                                                @if ($order->payment_status == 'paid')
                                                                    <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-right">
                                                                @can('view_orders')
                                                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                                        href="{{ route('orders.show', $order->id) }}"
                                                                        title="{{ translate('View') }}">
                                                                        <i class="las la-eye"></i>
                                                                    </a>
                                                                @endcan
                                                                @can('invoice_download')
                                                                    <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                                                        href="{{ route('orders.invoice.download', $order->id) }}"
                                                                        title="{{ translate('Download Invoice') }}">
                                                                        <i class="las la-download"></i>
                                                                    </a>
                                                                @endcan
                                                                @can('delete_orders')
                                                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                                        data-href="{{ route('orders.destroy', $order->id) }}"
                                                                        title="{{ translate('Delete') }}">
                                                                        <i class="las la-trash"></i>
                                                                    </a>
                                                                @endcan
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>  {{-- Card Body End --}}
                                    </div>{{-- Card End --}}
                                </div>
                            </div>
                           </div>
                        </div>
                </div>
            </div>
           

        </div>

        <!-- Modal for view product-->
        <div class="modal fade" id="product_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product list of <span id="customer_name_set_modal" val=""></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="modal_close_button">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="modal_product_view">


                    </ul>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal_close_button" data-dismiss="modal">Close</button>

                </div>
            </div>
            </div>
        </div>

        <!-- Modal for Edit Comment-->
        <div class="modal fade" id="edit_comment" tabindex="-1" role="dialog" aria-labelledby="editCommentLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editCommentLabel">Edit Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="close_button_comment()">
                    <span aria-hidden="true" class="modal_close_button_comment">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="modal_comment_view">
                        <label for="date_picker">Date Picker <span class="text-danger">*</span></label>
                        <input type="date" id="date_picker_reminder" class="form-control mb-3" placeholder="Select Date">
                        <label for="#comment">Comment <span class="text-danger">*</span></label>
                        <textarea id="edit_comment_view" class="aiz-text-editor edit_comment_vanish" name="description"></textarea>

                        <input type="hidden" id="customer_id" value="{{ $user->id }}">
                        <table>
                            <tbody class="search_product_edit">

                            </tbody>
                        </table>
                        <label for="#product_name_search">Interested Product</label>
                        <input type="text" class="form-control mb-3 product_name_search_edit_comment" onkeypress="productSearchComment(event)" value="" placeholder="product search here" id="product_name_search_id_edit_comment">
                        <input type="text" hidden class="form-control mb-3" id="product_search_result" value="" >
                        <table class="table" id="product_show_table">
                            <tbody class="product_search_result_comment shadow_class">
                            </tbody>
                        </table>
                    </ul>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="update_comment()">Update</button>
                <button type="button" class="btn btn-secondary modal_close_button_comment" data-dismiss="modal" onclick="close_button_comment()">Close</button>

                </div>
            </div>
            </div>
        </div>

        <!-- Modal for Reminder Edit-->
        <div class="modal fade" id="edit_reminder" tabindex="-1" role="dialog" aria-labelledby="editReminderLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editReminderLabel">Edit Reminder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="close_button_reminder()">
                    <span aria-hidden="true" class="modal_close_button_comment">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="modal_comment_view">
                        <label for="#date_time_picker">Date Timer Picker <span class="text-danger">*</span></label>
                        <input type="datetime-local" id="date_time_picker_reminder" class="form-control mb-3" placeholder="Select DateTime">

                        <label for="#reminder">Reminder <span class="text-danger">*</span></label>
                        <textarea id="edit_reminder_view" class="aiz-text-editor edit_comment_vanish" name="description"></textarea>

                        <input type="hidden" id="customer_id" value="{{ $user->id }}">
                        <table>
                            <tbody class="search_product_add_reminder">

                            </tbody>
                        </table>
                        <label for="#product_name_search">Interested Product</label>
                        <input type="text" class="form-control mb-3 product_name_search_edit_comment" onkeypress="productSearchReminder(event)" value="" placeholder="product search here" id="product_name_search_id_edit_comment">
                        <input type="text" hidden class="form-control mb-3" id="product_search_result" value="" >
                        <table class="table" id="product_show_table">
                            <tbody class="product_search_result_reminder_update shadow_class">
                            </tbody>
                        </table>
                        <label for="">Assign To</label>
                        <input type="text" class="form-control mb-3 user_search_reminder_edit d-none" oninput="" placeholder="Search Assigned to Person">
                        <div class="alert alert-success alert-dismissible">
                            <a href="#" class="close close_button_reminder" data-dismiss="alert" aria-label="close">&times;</a>
                            <input class="check_user_id_reminder" type="hidden" name="added_product_id" id="" value="">
                            <strong class="reminder_name_show"></strong>
                        </div>
                        <select class="form-select form-control mb-3 reminder_status_edit" aria-label="Default select example">
                            <option selected value="0">Select Status</option>
                            <option value="1">Pending</option>
                            <option value="2">Confirm</option>
                            <option value="3">Rejected</option>
                        </select>
                        <input type="hidden" id="user_id_reminder" value="{{ Auth::user()->id }}">
                        <input type="hidden" id="customer_id_reminder" value="{{ $user->id }}">
                        <input type="hidden" id="reminder_row_id" value="">

                    </ul>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="update_reminder()">Update</button>
                <button type="button" class="btn btn-secondary modal_close_button_comment" data-dismiss="modal" onclick="close_button_reminder()">Close</button>

                </div>
            </div>
            </div>
        </div>


        <!-- Modal for Edit Assignto-->
        <div class="modal fade" id="edit_assignto" tabindex="-1" role="dialog" aria-labelledby="editAssignToLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editAssignToLabel">Edit Here Information</h5>
                <button type="button" class="close modal_close_button_assignto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="modal_close_button_assignto">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="modal_assignto_view">

                        <input type="hidden" id="customer_id_assignto" value="{{ $user->id }}">
                        <input type="hidden" id="assign_by_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" class="assignto_row" value="">
                    </ul>
                    <label for="#assign_to_edit">Assign To <span class="text-danger">*</span></label>
                    <input type="text" id="assign_to_edit" class="form-control d-none" oninput="assign_user_edit_search(this)" placeholder="assign to name">

                    <div class="mt-3">
                        <table class="col-12">
                            <tbody class="user_search_edit_assignto">

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-8 offset-md-2">
                        <table class="table" id="user_show_assignto_edit_table">
                            <tbody class="user_search_result_assignto_edit shadow_class">
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="update_assignto()">Update</button>
                <button type="button" class="btn btn-secondary modal_close_button_assignto" data-dismiss="modal">Close</button>

                </div>
            </div>
            </div>
        </div>

        

    </div>
@endsection

@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript">


var onKeyup = function(evt) {
    $(".product_search_result").html("");
    product_show();

};
var input = document.getElementById('product_name_search_id');
input.addEventListener('input', onKeyup, false);

function product_show(){
    setTimeout(function() {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var product_name = $('.product_name_search').val();

        $.ajax({
        type: "GET",
        url: "{{route('customer_crm.product_search')}}",
        data: {
            product_name:product_name
        },
        success: function (response) {
            
            var serial = 1;
            if(response == null){
                $("#product_search_result").html("");
            }else{
                var row = "";
                $.each(response.data, function (index, value) { 

                    
                 row += `<tr class="tabel_row mb-3" id=row_>
                        <td class="pt-4"><a href="javascript:void(0)" onclick="product_add_function(${value.id}, '${value.name.replace(/["']/g, "")}')">${value.name}</a></td>
                    </tr>`;
                    serial = serial+1;
                

                });
                $(".product_search_result").html(row);
            }


        }
    });


    }, 50);
}

$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');
    $('#dtBasicExample1').DataTable();
    $('#dtBasicExample2').DataTable();
    $('#dtBasicExample3').DataTable();
    $('#dtBasicExample4').DataTable();
    $('#dtBasicExample5').DataTable();
    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
      });


    // erase modal data using close button

$("#product_view").on("hidden.bs.modal", function () {
    
    $('#modal_product_view').empty();
});




$("#v-pills-comments-tab").click(function (e) { 
    e.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var customer_id = $("#customer_id").val();
    $.ajax({
        type: "GET",
        url: "{{route('customer_crm.view_comment')}}",
        data: {
            customer_id:customer_id
        },
        success: function (response) {

            console.log(response.data);

            var crm_comment_serial = 1;
            var date = "";
            // echo  getProductName(5) 
            $.each(response.data, function(index, value) {

                var date = moment(value.created_at).format('DD/MM/YYYY');
                    crm_comments_row = `<tr class="tabel_row mb-3" id=row_${value.id}>
                        <td class="text-center pt-4">${crm_comment_serial}</td>
                        <td class="text-center pt-4">${date}</td>
                        <td class="text-center pt-4">${value.comments}</td>
                        <td class="text-center pt-4">
                            <button class="btn btn-soft-primary btn-icon btn-circle btn-sm product_view" data-toggle="modal" data-target="#product_view" onclick='product_view_function(${value.product_ids}, ${value.crm_id})' id="product_view_id_${value.id}"><i class="fas fa-eye"></i></button>
                        </td>
                        <td class="text-center pt-4">${value.name}</td>
                        <td class="text-center pt-4"><button type="button" onclick='comment_edit_function(${value.crm_id}, ${value.id})' class="btn btn-primary" data-toggle="modal" data-target="#edit_comment">Edit</button></td>
                    </tr>`;
                    
                    crm_comment_serial = crm_comment_serial+1;
                    $("#crm_view_id").append(crm_comments_row);
            });

            if(response.status=="success"){
                $(".odd").remove();
            }
            
        }
    });
    
});

}); // end of ready function


$("#add_comment_function").click(function (e) { 
    e.preventDefault();


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var comment = $("#add_comment").val();
    var customer_id = $("#customer_id").val();
    var filtered_comment = $(comment).text();

    var product_added_array = [];
    $(".search_product_add").find("input").each(function(){ product_added_array.push(this.id); });
    

    $.ajax({
        type: "POST",
        url: "{{route('customer_crm.add_comment')}}",
        data: {
            comment:filtered_comment,
            customer_id:customer_id,
            product_added_array:product_added_array
        },
        success: function (response) {
            if(response.status=="success"){
                Swal.fire(
                'Good job!',
                'You clicked the button!',
                'success'
                )
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                })
            }

            //$('.add_comment_vanish').val('');
            $(".card-block").html('');
            $('.remove_row').remove();
        }
    });
    
});

// reminder submit
$("#reminder_submit").click(function (e) {
    e.preventDefault();
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var date = $("#date_time_picker").val();
    var reminder_text = $("#reminder_text").val();
    var reminder_text = $(reminder_text).text();
    var customer_id = $("#customer_id").val();
    var user_id = $("#user_id").val();
    var reminder_status = $('.reminder_status').val();
    var assign_to_id = $(".assign_to_name").val();


    var product_added_array = [];
    $(".search_product_add_reminder").find("input").each(function(){ product_added_array.push(this.id); });

    
    $.ajax({
        type: "POST",
        url: "{{route('customer_crm.add_reminder')}}",
        data: {
            customer_id:customer_id,
            reminder_text:reminder_text,
            date:date,
            user_id:user_id,
            reminder_status:reminder_status,
            assign_to_id:assign_to_id,
            product_added_array:product_added_array

        },
        success: function (response) {

            
            if(response.status=="success"){
                Swal.fire(
                'Good job!',
                'You clicked the button!',
                'success'
                )
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                })
            }
            $('.reminder_status').val('0');
            $('#date_time_picker').val('');
            var url = "http://127.0.0.1:8000/admin/customers/"+response.customer_id;
            location.href = url;
            
            // $("#date_time_picker").datepicker("option", "defaultDate", null);
            // $("#date_time_picker").datepicker();

        }
    });


});



// $("input[type=datetime-local]").flatpickr(config);

$("#reminder_button").click(function (e) { 
    e.preventDefault();
    console.log("ok");
});



// Product Add Section in Comment
function product_add_function(product_id, product_name){

    console.log(product_id);

    product_row = `<div class="alert alert-success alert-dismissible remove_row">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <input class="check_product_id" type="hidden" name="added_product_id" id="${product_id}" value="${product_id}">
        <strong>${product_name}</strong>
    </div>`;

    

        
    var check = 0;
    var present_id = product_id;
    $('.check_product_id').each(function(e){
        if($(this).val() == present_id){
            check++;
        }
        console.log($(this).val());
    })
    if(check > 0){
        Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Duplicate Product!',
                })
    }
    if(check == 0 ){

        $(".search_product_add").append(product_row);
    }

}

function comment_edit_function(customer_id, comment_id){

    $.ajax({
        type: "GET",
        url: "{{route('customer_crm.getting_comments_modal')}}",
        data: {
            comment_id:comment_id,
            customer_id: customer_id
        },
        success: function (response) {

            var comments = response.data[0].comments;
            var editor = $('#edit_comment_view');
            editor.summernote('code', comments);

            for (var i = 0; i < response.product_name_array.length; i++) {
            var obj = response.product_name_array[i];
                for (var key in obj) {
                    var value = obj[key];

                    product_row = `<div class="alert alert-success alert-dismissible remove_row">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <input class="check_product_id_comment" name="product_id_commnet[]" type="hidden" id="${key}" value="${key}">
                        <strong>${value}</strong>
                        <input class="comment_id" type="hidden" id="${response.comment_id}" value="${response.comment_id}">
                    </div>`;

                    $(".search_product_edit").append(product_row);
                }
            }

        }
    });
}


function product_view_function(product_ids, customer_id){

    $.ajax({
        type: "GET",
        url: "{{route('customer_crm.getting_product_name_modal')}}",
        data: {
            product_ids:product_ids,
            customer_id: customer_id
        },
        success: function (response) {
        console.log(response);

        $.each(response.data, function(index, item) {
            
            var row = `<li class="list-group-item align-items-center d-flex">
                <a class="btn text-white btn-floating m-1 me-3"
                style="background-color: hsla(124, 100%, 16%, 1);">
                <i class="fas fa-check-circle"></i>
                </a>
                ${item}
            </li>`;
            $("#modal_product_view").append(row);

    
        });
        $("#customer_name_set_modal").text(response.customer_name);


        }
    });
}

// Search Product on Reminder

function reminderProductSearch(){
$(".product_search_result_reminder").html("");

setTimeout(function() {
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var product_name = $('.product_search_reminder').val();
    var name_length = product_name.length;



    $.ajax({
    type: "GET",
    url: "{{route('customer_crm.product_search')}}",
    data: {
        product_name:product_name,
        name_length:name_length
    },
    success: function (response) {
        var serial = 1;

            var row = "";
            if(response.name_length == 0){
                $("#product_show_reminder_table").html("");
            }

            $.each(response.data, function (index, value) { 

                
             row += `<tr class="tabel_row mb-3" id=row_>
                    <td class="pt-4"><a href="javascript:void(0)" onclick="product_add_function_reminder(${value.id}, '${value.name.replace(/["']/g, "")}')">${value.name}</a></td>
                </tr>`;
                serial = serial+1;
            

            });
            $(".product_search_result_reminder").html(row);
        


    }
});

}, 50);
}

// Product Add Section in Comment
function product_add_function_reminder(product_id, product_name){

    product_row = `<div class="alert alert-success alert-dismissible remove_row">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <input class="check_product_id_reminder" type="hidden" name="added_product_id" id="${product_id}" value="${product_id}">
        <strong>${product_name}</strong>
    </div>`;

    console.log(product_name);


    
var check = 0;
var present_id = product_id;

    $('.check_product_id_reminder').each(function(e){
        if($(this).val() == present_id){
            check++;
        }
        console.log($(this).val());
    })
    if(check > 0){
            Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Duplicate Product!',
                    })
    }
    if(check == 0 ){

        $(".search_product_add_reminder").append(product_row);
    }

}


function reminderAssignSearch(){
$(".product_search_result_reminder").html("");

setTimeout(function() {
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var user_name = $('.user_search_reminder').val();
    var name_length = user_name.length;

    $.ajax({
    type: "GET",
    url: "{{route('customer_crm.user_search')}}",
    data: {
        user_name:user_name,
        name_length:name_length
    },
    success: function (response) {
  
        var serial = 1;

            var row = "";
            if(response.name_length == 0){
                $("#user_show_reminder_table").html("");
            }

            $.each(response.data, function (index, value) { 

                
             row += `<tr class="tabel_row mb-3" id=row_>
                    <td class="pt-4"><a href="javascript:void(0)" onclick="user_add_function_reminder(${value.id}, '${value.name}')">${value.name}</a>
                        <input type="hidden" class="assign_to_name" value="${value.id}" name="">
                    </td>
                </tr>`;
                serial = serial+1;
            

            });
            $(".user_search_result_reminder").html(row);
        


    }
});



}, 50);
}


// User Add Section in Comment
function user_add_function_reminder(user_id, user_name){

//console.log(user_id);

    user_row = `<tr class="remove_row">
        <td>
            <div class="alert alert-success alert-dismissible">
                <a href="#" class="close close_button" data-dismiss="alert" aria-label="close">&times;</a>
                <input class="check_user_id" type="hidden" name="added_product_id" id="${user_id}" value="${user_name}">
                <strong>${user_name}</strong>
            </div>
        </td>
     </tr>`;



    
var check = 0;
var present_id = user_id;
$('.check_user_id').each(function(e){
    if($(this).val() == present_id){
        check++;
    }
    console.log($(this).val());
})
if(check == 0 ){

    $(".search_user_add_reminder").append(user_row);
    $(".user_search_reminder").addClass("d-none");
    $("#user_show_reminder_table").addClass("d-none");

    $(".close_button").click(function (e) { 
        e.preventDefault();
        $(".user_search_reminder").removeClass("d-none");
        $("#user_show_reminder_table").removeClass("d-none");
        $(".user_search_reminder").focus();

    });

}

}

$("#reminder_tab").click(function (e) { 
    e.preventDefault();
    console.log("ok");
});


function basic_update_function(id){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var name = $("#name").val();
    var phone_number = $("#phone_number").val();
    var email_address = $("#email_address").val();
    var client_type = $("#client_type").val();
    var customer_id = "";
    var url = "";

    $.ajax({
    type: "POST",
    url: "{{route('customer_profile.update')}}",
    data: {
        id:id,
        name:name,
        phone_number:phone_number,
        email_address:email_address,
        client_type:client_type
    },
    success: function (response) {
        Swal.fire(
        'Good job!',
        'You clicked the button!',
        'success'
        )
        var id = response.customer_id;
        var url = "{{ route('customers.show', ['customer' => ':id']) }}";
        url = url.replace(':id', id);
        window.location.replace(url);

    }
});

}

function document_create_function(user_id){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var document_name = $("#document_name").val();
    var document_file = $("#document_file").val();

    var data = {
        "document_name": document_name,
        "document_file_path": document_file
    };
    var document_data = JSON.stringify(data);

    $.ajax({
    type: "POST",
    url: "{{route('customer_profile.document_update')}}",
    data: {
        user_id:user_id,
        document_data:document_data
    },
    success: function (response) {

        console.log(response);

    }
});

}

function productSearchComment(event){
    $(".product_search_result_comment").html("");
    var comment_id = $(".comment_id").val();

    setTimeout(function() {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var product_name = $("#product_name_search_id_edit_comment").val();

        
        if (product_name.length == 1 && (event.keyCode === 8 || event.keyCode === 46)) {
            console.log("length is less than 1");
        } else if (product_name.length > 1) {
            console.log("length is greater than 1");
        }

        $.ajax({
        type: "GET",
        url: "{{route('customer_crm.product_search')}}",
        data: {
            product_name:product_name
        },
        success: function (response) {
            
            var serial = 1;
            if(response == null){
                $(".product_search_result_comment").html("");
                $(".product_search_result_comment").empty();
            }else{
                var row = "";
                $.each(response.data, function (index, value) { 

                    
                 row += `<tr class="tabel_row mb-3" id=row_>
                        <td class="pt-4"><a href="javascript:void(0)" onclick="product_add_function_comment(${value.id}, ${comment_id}, '${value.name.replace(/["']/g, "")}')">${value.name}</a></td>
                    </tr>`;
                    serial = serial+1;
                

                });
                $(".product_search_result_comment").html(row);
            }


        }
    });


    }, 50);
}

// Product Add Section in Comment
function product_add_function_comment(product_id, comment_id, product_name){

    product_row = `<div class="alert alert-success alert-dismissible remove_row">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <input class="check_product_id_comment" name="product_id_commnet[]" type="hidden" id="${product_id}" value="${product_id}">
        <strong>${product_name}</strong>
        <input class="comment_id" type="hidden" id="${comment_id}">
    </div>`;

    var check = 0;
    var present_id = product_id;

    $('.check_product_id_comment').each(function(e){
        if($(this).val() == present_id){
            check++;
        }
        console.log($(this).val());
    })
    if(check > 0){
        Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Duplicate Product!',
                })
    }
    if(check == 0 ){

        $(".search_product_edit").append(product_row);
    }

}


function close_button_comment(){
    $(".search_product_edit").empty();
    $(".product_search_result_comment").empty();
    $("#product_name_search_id_edit_comment").val("");
}

function update_comment() {
  var all_product_id = [];
  $(".search_product_edit .check_product_id_comment").each(function() {
    all_product_id.push($(this).val());
  });

  var comment_id = $(".search_product_edit .comment_id").val();
  var comment = $("#edit_comment_view").val();

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    $.ajax({
        type: "POST",
        url: "{{route('customer_profile.comment_update')}}",
        data: {
            comment:comment,
            comment_id:comment_id,
            all_product_id:all_product_id
        },
        success: function (response) {

            console.log(response);
            close_button_comment();
            $('#edit_comment').modal('hide');

        }
    });

}

function reminder_edit(reminder_id) {

$.ajax({
  type: "GET",
  url: "{{ route('customer_profile.reminder_view') }}",
  data: {
    reminder_id: reminder_id
  },
  success: function(response) {
    var product_name_list = response.product_name_list;

    var product_row = "";
    var myString = response.data.interested_product;
    var myArray = $.makeArray(myString.split(","));
    var newArray = myArray.map(function(element) {
        return element.replace(/[^0-9]/g, '');
    });
 

    $.each(newArray, function(index, product_id) {
        var product_name = product_name_list[index];
        product_row += `<div class="alert alert-success alert-dismissible remove_row">
            <a href="#" class="close" onclick="reminder_product_close_button()" data-dismiss="alert" aria-label="close">&times;</a>
            <input class="check_product_id_reminder" type="hidden" name="check_product_id_reminder" id="${product_id}" value="${product_id}">
            <strong>${product_name}</strong>
        </div>`;
    });

    $(".search_product_add_reminder").append(product_row);
    $("#reminder_row_id").val(response.data.id)

    $('.reminder_status_edit').val(response.data.status);
    var date = response.data.date;
    var time = response.data.time;
    var dateTime = date + 'T' + time;
    $('#date_time_picker_reminder').val(dateTime);

    var note = response.data.note;
    var editor_reminder = $('#edit_reminder_view');
    editor_reminder.summernote('code', note);
    
    var assignTo = response.data.assign_to;
    $(".check_user_id_reminder").val(assignTo);

    $.ajax({
      type: "GET",
      url: "{{ route('get_user_name') }}",
      data: {
        id: assignTo
      },
      success: function(response) {

        $(".reminder_name_show").text(response.userName);
        $(".close_button_reminder").click(function (e) { 
            e.preventDefault();
            $(".user_search_reminder_edit").removeClass("d-none");
        });
      }
    });

  }
});

}

function update_reminder(){
    var all_product_id = [];
    $(".search_product_add_reminder .check_product_id_reminder").each(function() {
        all_product_id.push($(this).val());
    });

    // Remove duplicates from the array
    all_product_id = all_product_id.filter(function(item, index) {
        return all_product_id.indexOf(item) === index;
    });

    var date = $("#date_time_picker_reminder").val();
    var reminder_note = $("#edit_reminder_view").val();
    var assign_to = $(".check_user_id_reminder").val();
    var status = $(".reminder_status_edit").val();
    var user_id = $("#user_id_reminder").val();
    var customer_id = $("#customer_id_reminder").val();
    var reminder_id = $("#reminder_row_id").val();
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    $.ajax({
        type: "POST",
        url: "{{route('customer_profile.reminder_update')}}",
        data: {
            all_product_id:all_product_id,
            date:date,
            reminder_note:reminder_note,
            assign_to:assign_to,
            status:status,
            user_id:user_id,
            customer_id:customer_id,
            reminder_id:reminder_id
        },
        success: function (response) {
            close_button_reminder();
            $('#edit_reminder').modal('hide');
            console.log(response);
            

        }
    });

    
}

function productSearchReminder(event){

    $(".product_search_result_reminder_update").html("");
    var reminder_id = $("#reminder_row_id").val();
    
    setTimeout(function() {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var product_name = event.key;


        $.ajax({
        type: "GET",
        url: "{{route('customer_crm.product_search')}}",
        data: {
            product_name:product_name
        },
        success: function (response) {
            
            var serial = 1;
            if(response == null){
                $(".product_search_result_reminder_update").html("");
                $(".product_search_result_reminder_update").empty();
            }else{
                var row = "";
                $.each(response.data, function (index, value) { 

                    
                 row += `<tr class="tabel_row mb-3" id=row_>
                        <td class="pt-4"><a href="javascript:void(0)" onclick="product_add_function_reminder_dd(${value.id}, ${reminder_id}, '${value.name.replace(/["']/g, "")}')">${value.name}</a></td>
                    </tr>`;
                    serial = serial+1;
                

                });
                $(".product_search_result_reminder_update").html(row); //1
            }


        }
    });


    }, 50);
}

// Product Add Section in Reminder
function product_add_function_reminder_dd(product_id, reminder_id, product_name){

    product_row = `<div class="alert alert-success alert-dismissible remove_row">
        <a href="#" class="close" onclick="reminder_product_close_button(${product_id})" data-dismiss="alert" aria-label="close">&times;</a>
        <input class="check_product_id_reminder" name="product_id_commnet[]" type="hidden" id="${product_id}" value="${product_id}">
        <strong>${product_name}</strong>
        <input class="reminder_id" type="hidden" id="${reminder_id}">
    </div>`;

    console.log(product_name);

    var check = 0;
    var present_id = product_id;

    $('.check_product_id_reminder').each(function(e){
        if($(this).val() == present_id){
            check++;
        }
        console.log($(this).val());
    });
    
    if(check > 0){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Duplicate Product!',
        });
    } else {
        $(".search_product_add_reminder").append(product_row);
    }


}


function reminder_product_close_button(product_id){
    $(".search_product_add_reminder").find(`div #${product_id}`).closest('.remove_row').remove();

    // remove the hidden input field with specific product_id
    $(".check_product_id_reminder").each(function() {
        if($(this).val() == product_id) {
            $(this).remove();
        }
    });
}


function close_button_reminder(){
    $(".search_product_add_reminder").empty();
    $(".product_search_result_reminder_update").empty();
}

$(".assign_to_add_submit").click(function (e) { 
    
    var assign_to_add = $(".added_user_id").val();
    var assign_by_add = $("#user_id_assign_to").val();
    var customer_id_assign_to = $("#customer_id_assign_to").val();


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "{{route('customer_profile.add_assignto')}}",
        data: {
            assign_to_add:assign_to_add,
            assign_by_add:assign_by_add,
            customer_id_assign_to:customer_id_assign_to
        },
        success: function (response) {
            $(".user_search_add_assignto").html("");
            $("#assign_to_add").removeClass("d-none");
            $("#user_show_assignto_table").removeClass("d-none");
            $("#assign_to_add").focus();

            if(response.status == "success"){
                Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Added Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $(".user_search_result_assignto").addClass("d-none");
                    $(".user_search_result_assignto").removeClass("d-block");

                    var id = customer_id_assign_to;
                    var url = "{{ route('customers.show', ['customer' => ':id']) }}";
                    url = url.replace(':id', customer_id_assign_to);
                    window.location.replace(url);
            }
            else{
                Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    })
            }
            
        }
    });

});

function assign_user_search(inputField){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var user_name = inputField.value;
    var name_length = user_name.length;

    if(name_length>0){
        $.ajax({
            type: "GET",
            url: "{{route('customer_crm.user_search')}}",
            data: {
                user_name:user_name,
                name_length:name_length
            },
            success: function (response) {

                var row = "";
                $.each(response.data, function (index, value) { 
                row += `<tr class="tabel_row mb-3" id=row_>
                        <td class="pt-4"><a href="javascript:void(0)" onclick="user_add_function_assignto(${value.id}, '${value.name}')">${value.name}</a>
                            <input type="hidden" class="assign_to_name" value="${value.id}" name="">
                        </td>
                    </tr>`;

                });
                $(".user_search_result_assignto").html(row);

            }
        });
    }
    else{
        $(".user_search_result_assignto").html("");
    }
}



// Product Add Section in Assign To
function user_add_function_assignto(user_id, user_name){

//console.log(user_id);

    user_row = `<tr class="remove_row">
        <td>
            <div class="alert alert-success alert-dismissible">
                <a href="#" class="close close_button" data-dismiss="alert" aria-label="close">&times;</a>
                <input class="form-control check_user_id added_user_id" type="hidden" id="${user_id}" value="${user_id}">
                <strong>${user_name}</strong>
            </div>
        </td>
     </tr>`;



    
var check = 0;
var present_id = user_id;
$('.check_user_id').each(function(e){
    if($(this).val() == present_id){
        check++;
    }
    console.log($(this).val());
})
if(check == 0 ){

    $(".user_search_add_assignto").append(user_row);
    $("#assign_to_add").addClass("d-none");
    $("#user_show_assignto_table").addClass("d-none");

    $(".close_button").click(function (e) {
        e.preventDefault();
        $("#assign_to_add").removeClass("d-none");
        $("#user_show_assignto_table").removeClass("d-none");
        $("#assign_to_add").focus();

    });

}

}

function getUserName(id) {
    return $.ajax({
        type: "GET",
        url: "{{ route('get_user_name') }}",
        data: {
            id: id
        }
    });
}

function assignToFunction(customer_id, user_id, user_type) {

    $.ajax({
        type: "GET",
        url: "{{route('customer_profile.assignto_view')}}",
        data: {
            customer_id: customer_id,
            user_id: user_id,
            user_type:user_type
        },
        success: async function (response) {
            var assign_row = "";
            var serial = 1;
            for (var i = 0; i < response.data.length; i++) {
                var id = response.data[i].id;
                var customer_id = response.data[i].customer_id;
                var assign_to = response.data[i].assign_to;
                var assign_by = response.data[i].assign_by;
                var date = moment(response.data[i].created_at).format('DD-MM-YYYY');

                var customerPromise = getUserName(customer_id);
                var assignToPromise = getUserName(assign_to);
                var assignByPromise = getUserName(assign_by);

                var customerNameResponse = await customerPromise;
                var assignToNameResponse = await assignToPromise;
                var assignByNameResponse = await assignByPromise;

                var customerName = customerNameResponse.user_name;
                var assignToName = assignToNameResponse.user_name;
                var assignByName = assignByNameResponse.user_name;

                var rowData = `
                    <tr>
                        <td class="text-center pt-4">${serial}</td>
                        <td class="text-center pt-4">${customerName}</td>
                        <td class="text-center pt-4">${assignToName}</td>
                        <td class="text-center pt-4">${assignByName}</td>
                        <td class="text-center pt-4">${date}</td>
                        <td class="text-center pt-4"><button type="button" value="${id}" onclick="assignToEdit(${id}, ${customer_id})"  data-toggle="modal" data-target="#edit_assignto" class="btn btn-primary btn-sm">Edit</button><button type="button" onclick="assignToDelete(${id}, ${customer_id})" class="btn btn-danger btn-sm">Delete</button></td>
                    </tr>`;
                assign_row += rowData;
                serial++;
            }
            $(".assign_row").empty();
            $(".assign_row").append(assign_row);
        }
    });
}

function assignToEdit(assign_id) {
    $.ajax({
        type: "GET",
        url: "{{route('customer_profile.edit_assignto')}}",
        data: {
            assign_id: assign_id
        },
        success: async function(response) {

            var assign_row = response.data.id;
            var customer_id = response.data.customer_id;
            var assign_to = response.data.assign_to;
            var assign_by = response.data.assign_by;
            var user_row = "";
            try {
                var [customerName, assignToName, assignByName] = await Promise.all([
                    getUserName(customer_id),
                    getUserName(assign_to),
                    getUserName(assign_by)
                ]);

                user_row = `<tr class="remove_row">
                    <td>
                        <div class="alert alert-success alert-dismissible">
                            <a href="#" class="close close_button" onclick="close_button_assign_user()" data-dismiss="alert" aria-label="close">&times;</a>
                            <input class="form-control added_user_id_edit" type="hidden" id="${assign_to}" value="${assign_to}">
                            <strong>${assignToName.user_name}</strong>
                        </div>
                    </td>
                </tr>`;

                $(".user_search_edit_assignto").append(user_row);

                $(".modal_close_button_assignto").click(function (e) { 
                    e.preventDefault();
                    $(".user_search_edit_assignto").html("");
                    $("#assign_to_edit").addClass("d-none");
                    $("#assign_to_edit").removeClass("d-block");
                });

                $(".assignto_row").val(assign_row)


                
            } catch (error) {
                console.log(error);
            }
            $('#edit_assignto').modal('show');
            
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function close_button_assignto(){
    console.log("ok");
}

function assignToDelete(id, customer_id) {
    var capturedCustomerId = customer_id; // Create a separate variable to capture the value

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
            $.ajax({
                type: "POST",
                url: "{{route('customer_profile.delete_assignto')}}",
                data: {
                    id: id
                },
                success: function (response) {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );

                    var id = capturedCustomerId;
                    var url = "{{ route('customers.show', ['customer' => ':id']) }}";
                    url = url.replace(':id', capturedCustomerId);
                    window.location.replace(url);

                }
            });
        }
    });
}



function close_button_assign_user(){

    $("#assign_to_edit").addClass("d-block");
    $("#assign_to_edit").removeClass("d-none");

}

function assign_user_edit_search(inputField){
    // $(".user_search_result_assignto_edit").addClass("d-block");
    // $(".user_search_result_assignto_edit").removeClass("d-none");
    
    var user_name = inputField.value;
    var name_length = user_name.length


    if(name_length>0){
        $.ajax({
            type: "GET",
            url: "{{route('customer_crm.user_search')}}",
            data: {
                user_name:user_name,
                name_length:name_length
            },
            success: function (response) {

                var row = "";
                $.each(response.data, function (index, value) { 
                row += `<tr class="tabel_row mb-3" id=row_>
                        <td class="pt-4"><a href="javascript:void(0)" onclick="user_add_function_assignto_edit(${value.id}, '${value.name}')">${value.name}</a>
                            <input type="hidden" class="assign_to_name" value="${value.id}" name="">
                        </td>
                    </tr>`;
                    console.log(value.name);
                });
                $(".user_search_result_assignto_edit").html(row);
                

            }
        });
    }
    else{
        $(".user_search_result_assignto_edit").html("");
    }

}


$(document).on('click', function(event) {
    if ($(event.target).closest('.modal-content').length === 0 && $(event.target).hasClass('modal_close_button_assignto') === false) {
        $(".user_search_edit_assignto").html("");
    }
});

function update_assignto(){
    var assignto_user = $(".added_user_id_edit").val();
    var customer_id = $("#customer_id_assignto").val();
    var assignto_row = $(".assignto_row").val();
    var assign_by_id = $("#assign_by_id").val();
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
            type: "POST",
            url: "{{route('customer_profile.update_assignto')}}",
            data: {
                assignto_user:assignto_user,
                customer_id:customer_id,
                assignto_row:assignto_row,
                assign_by_id:assign_by_id
            },
            success: function (response) {
                console.log(response.status);
                if(response.status == 'success'){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Updated Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $('#edit_assignto').modal('hide');

                    var id = response.customer;
                    var url = "{{ route('customers.show', ['customer' => ':id']) }}";
                    url = url.replace(':id', customer_id);
                    window.location.replace(url);
                    
                }
                else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    })
                }
                

            }
        });
    
}

function user_add_function_assignto_edit(user_id, user_name){

///console.log(user_id);

    user_row = `<tr class="remove_row">
        <td>
            <div class="alert alert-success alert-dismissible">
                <a href="#" class="close close_button" onclick="close_button_assign_user()" data-dismiss="alert" aria-label="close">&times;</a>
                <input class="form-control added_user_id_edit" type="hidden" id="${user_id}" value="${user_id}">
                <strong>${user_name}</strong>
            </div>
        </td>
    </tr>`;

    $(".user_search_edit_assignto").append(user_row);
    $("#assign_to_edit").addClass("d-none");
    $("#assign_to_edit").removeClass("d-block");
    $(".user_search_result_assignto_edit").addClass("d-none");
    $(".user_search_result_assignto_edit").removeClass("d-block");


    $(".modal_close_button_assignto").click(function (e) { 
        e.preventDefault();
        $(".user_search_edit_assignto").html("");
        $("#assign_to_edit").addClass("d-none");
        $("#assign_to_edit").removeClass("d-block");
    });


}




</script>

@endsection
