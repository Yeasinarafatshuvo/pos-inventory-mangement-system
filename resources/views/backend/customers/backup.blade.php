@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .shadow_class{
        box-shadow: 0 0 50px #ccc;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-lg-3">
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
        </div>
        <div class="col-lg-9">
            <div class="row">
                <div class="col-md-12">
                        <div class="horizontal-tabs">
                           <ul class="nav nav-tabs" role="tablist">
                              <li class="nav-item">
                                 <a class="nav-link active" data-toggle="tab" href="#home-h" role="tab" aria-controls="home">General</a>
                              </li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#comments_h" role="tab" aria-controls="comments">Comments</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reminder_h" role="tab" aria-controls="reminder">Reminder</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#assign_to_h" role="tab" aria-controls="assign_to">Assign To</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#product_h" role="tab" aria-controls="product">Product</a></li>
                              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#order_list_h" role="tab" aria-controls="order_list">Order List</a></li>
                           </ul>
                           <div class="tab-content">
                              <div class="tab-pane active" id="home-h" role="tabpanel">
                                {{-- <div class="sv-tab-panel">Home Panel</div> --}}
                                <div class="row my-3">
                                    <div class="col-md-3">
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active" id="v-pills-basic-tab" data-toggle="pill" href="#v-pills-basic" role="tab" aria-controls="v-pills-basic" aria-selected="true">Basic</a>
                                            <a class="nav-link" id="v-pills-documents-tab" data-toggle="pill" href="#v-pills-documents" role="tab" aria-controls="v-pills-documents" aria-selected="false">Documents</a>
                                            <a class="nav-link" id="v-pills-identity-tab" data-toggle="pill" href="#v-pills-identity" role="tab" aria-controls="v-pills-identity" aria-selected="false">Identity</a>
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
                                                    <form>
                                                        <div class="row">
                                                          <div class="col-md-6 my-2">
                                                            <label for="name">Name</label>
                                                            <input type="text" id="name" class="form-control" placeholder="Name">
                                                          </div>
                                                          <div class="col-md-6 my-2">
                                                            <label for="primary_phone_number">Primary Phone Number</label>
                                                            <input type="text" id="primary_phone_number" class="form-control" placeholder="Primary Phone Number">
                                                          </div>
                                                          <div class="col-md-6 my-2">
                                                            <label for="name">Secondary Phone Number</label>
                                                            <input type="text" id="secondary_phone_number" class="form-control" placeholder="Secondary Phone Number">
                                                          </div>
                                                          <div class="col-md-6 my-2">
                                                            <label for="client_type">Client Type</label>
                                                            <input type="text" id="client_type" class="form-control" placeholder="Client Type">
                                                          </div>
                                                          <div class="col-md-6 my-2">
                                                            <label for="client_priority">Client Priority</label>
                                                            <input type="text" id="client_priority" class="form-control" placeholder="Client Priority">
                                                          </div>
                                                          <div class="col-md-6 my-2">
                                                            <label for="data_source">Data Source</label>
                                                            <input type="text" id="data_source" class="form-control" placeholder="Data Source">
                                                          </div>
                                                        </div>
                                                      </form>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-documents" role="tabpanel" aria-labelledby="v-pills-documents-tab"> Message Contents </div>
                                            <div class="tab-pane fade" id="v-pills-identity" role="tabpanel" aria-labelledby="v-pills-identity-tab"> Profile Contents </div>
                                            <div class="tab-pane fade" id="v-pills-contact" role="tabpanel" aria-labelledby="v-pills-contact-tab"> Contact </div>
                                            <div class="tab-pane fade" id="v-pills-address" role="tabpanel" aria-labelledby="v-pills-address-tab"> address </div>
                                            <div class="tab-pane fade" id="v-pills-reference" role="tabpanel" aria-labelledby="v-pills-reference-tab"> reference </div>
                                            <div class="tab-pane fade" id="v-pills-bank_account" role="tabpanel" aria-labelledby="v-pills-bank_account-tab"> bank_account </div>
                                          </div>
                                    </div>
                                </div>
                                
                                {{-- sfsdfsfs --}}
                            </div>
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
                                                    <label for="#product_name_search">Interested Product</label>
                                                    <input type="text" class="form-control mb-3 product_name_search" value="" placeholder="product search here">
                                                    <input type="text" hidden class="form-control mb-3" id="product_search_result" value="" >
                                                    <table class="table">
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
                                                            <tr>
                                                                <td class="text-center pt-4">01</td>
                                                                <td class="text-center pt-4">this is note</td>
                                                                <td class="text-center pt-4">02-02-2023</td>
                                                                <td class="text-center pt-4">3.30 PM</td>
                                                                <td class="text-center pt-4">Repon</td>
                                                                <td class="text-center pt-4">Complete</td>
                                                                <td class="text-center pt-4">
                                                                    <button type="button" class="btn btn-primary">Edit</button>
                                                                </td>
    
                                                            </tr>
                                                        
                                                        </tbody>
                                                  </table>
                                                </div>
                                                <div class="tab-pane fade" id="v-pills-add-reminders" role="tabpanel" aria-labelledby="v-pills-add-reminders-tab"> 
                                                    <label for="#date_time_picker">Date Timer Picker <span class="text-danger">*</span></label>
                                                    <input type="datetime-local" id="date_time_picker" class="form-control mb-3" placeholder="Select DateTime">
                                                    <label for="#reminder">Reminder Note <span class="text-danger">*</span></label>
                                                    <textarea id="reminder_text" class="aiz-text-editor" name="description"></textarea>
                                                    <input type="hidden" id="customer_id" value="{{ $user->id }}">
                                                    <input type="button" id="reminder_submit" class="btn btn-primary" value="Add Reminder">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="assign_to_h" role="tabpanel">
                                <div class="sv-tab-panel">
                                    <div class="row my-3">
                                        <div class="col-md-3">
                                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                <a class="nav-link active" id="v-pills-assign-tab" data-toggle="pill" href="#v-pills-assign" role="tab" aria-controls="v-pills-assign" aria-selected="false">Assign To</a>
                                                <a class="nav-link" id="v-pills-add-assign-tab" data-toggle="pill" href="#v-pills-add-assign" role="tab" aria-controls="v-pills-add-assign" aria-selected="false">Add Assign To</a>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="tab-content" id="v-pills-tabContent">
                                                <div class="tab-pane fade show active" id="v-pills-assign" role="tabpanel" aria-labelledby="v-pills-assign-tab"> Here is the assign to content </div>
                                                <div class="tab-pane fade" id="v-pills-add-assign" role="tabpanel" aria-labelledby="v-pills-add-assign-tab"> You can add assign to content </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="product_h" role="tabpanel">
                                <div class="sv-tab-panel">
                                    <div class="row my-3">
                                        <div class="col-md-3">
                                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                <a class="nav-link active" id="v-pills-cart-tab" data-toggle="pill" href="#v-pills-cart" role="tab" aria-controls="v-pills-cart" aria-selected="false">Cart Item</a>
                                                <a class="nav-link" id="v-pills-wishlist-tab" data-toggle="pill" href="#v-pills-wishlist" role="tab" aria-controls="v-pills-wishlist" aria-selected="false">Wishlist Item</a>
                                                <a class="nav-link" id="v-pills-reviewd-tab" data-toggle="pill" href="#v-pills-reviewd" role="tab" aria-controls="v-pills-reviewd" aria-selected="false">Reviewed Item</a>
                                                <a class="nav-link" id="v-pills-follow-shop-tab" data-toggle="pill" href="#v-pills-follow-shop" role="tab" aria-controls="v-pills-follow-shop" aria-selected="false">Followed Shop</a>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="tab-content" id="v-pills-tabContent">
                                                <div class="tab-pane fade show active" id="v-pills-cart" role="tabpanel" aria-labelledby="v-pills-cart-tab">
                                                    <table id="dtBasicExample2" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">SL</th>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Comment</th>
                                                            <th scope="col">Added By</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center pt-4">01</td>
                                                                <td class="text-center pt-4">02-02-2023</td>
                                                                <td class="text-center pt-4">this is comments</td>
                                                                <td class="text-center pt-4">Repon</td>
                                                                <td class="text-center pt-4">
                                                                    <button type="button" class="btn btn-primary">Edit</button>
                                        
                                                                </td>
    
                                                            </tr>
                                                        
                                                        </tbody>
                                                  </table>
                                                </div>
                                                <div class="tab-pane fade" id="v-pills-wishlist" role="tabpanel" aria-labelledby="v-pills-wishlist-tab">
                                                    <table id="dtBasicExample3" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">SL</th>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Comment</th>
                                                            <th scope="col">Added By</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center pt-4">01</td>
                                                                <td class="text-center pt-4">02-02-2023</td>
                                                                <td class="text-center pt-4">this is comments</td>
                                                                <td class="text-center pt-4">Repon</td>
                                                                <td class="text-center pt-4">
                                                                    <button type="button" class="btn btn-primary">Edit</button>
                                        
                                                                </td>
    
                                                            </tr>
                                                        
                                                        </tbody>
                                                  </table>
                                                </div>
                                                <div class="tab-pane fade" id="v-pills-reviewd" role="tabpanel" aria-labelledby="v-pills-reviewd-tab">
                                                    <table id="dtBasicExample4" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">SL</th>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Comment</th>
                                                            <th scope="col">Added By</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center pt-4">01</td>
                                                                <td class="text-center pt-4">02-02-2023</td>
                                                                <td class="text-center pt-4">this is comments</td>
                                                                <td class="text-center pt-4">Repon</td>
                                                                <td class="text-center pt-4">
                                                                    <button type="button" class="btn btn-primary">Edit</button>
                                        
                                                                </td>
    
                                                            </tr>
                                                        
                                                        </tbody>
                                                  </table>
                                                </div>
                                                <div class="tab-pane fade" id="v-pills-follow-shop" role="tabpanel" aria-labelledby="v-pills-follow-shop-tab">
                                                    <table id="dtBasicExample5" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">SL</th>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Comment</th>
                                                            <th scope="col">Added By</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center pt-4">01</td>
                                                                <td class="text-center pt-4">02-02-2023</td>
                                                                <td class="text-center pt-4">this is comments</td>
                                                                <td class="text-center pt-4">Repon</td>
                                                                <td class="text-center pt-4">
                                                                    <button type="button" class="btn btn-primary">Edit</button>
                                        
                                                                </td>
    
                                                            </tr>
                                                        
                                                        </tbody>
                                                  </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                              <div class="tab-pane" id="order_list_h" role="tabpanel">
                                <div class="sv-tab-panel">
                                    <div class="card my-3">
                                        <div class="card-header">
                                            {{ translate('Orders of this customer') }}
                                        </div>
                                        <div class="card-body">
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
    </div>
@endsection

@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#dtBasicExample').DataTable();
    $('.dataTables_length').addClass('bs-select');
    $('#dtBasicExample1').DataTable();
    $('#dtBasicExample2').DataTable();
    $('#dtBasicExample3').DataTable();
    $('#dtBasicExample4').DataTable();
    $('#dtBasicExample5').DataTable();


    // CRM Comment Product Search

$(".product_name_search").keypress(function (e) { 
    
    $(".product_search_result").html("");
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
                        <td class="pt-4"><a onclick="product_add_function(${value.id})">${value.name}</a></td>
                    </tr>`;
                    serial = serial+1;
                

                });
                $(".product_search_result").html(row);
            }


        }
    });


    }, 50);
});




$("#add_comment_function").click(function (e) { 
    e.preventDefault();
   
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var comment = $("#add_comment").val();
    var customer_id = $("#customer_id").val();


    $.ajax({
        type: "POST",
        url: "{{route('customer_crm.add_comment')}}",
        data: {
            comment:comment,
            customer_id:customer_id
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
        }
    });
    
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
            var crm_comment_serial = 1;
            var date = "";
            console.log(response);
            $.each(response.data, function(index, value) {
                
                    var date = moment(value.created_at).format('DD/MM/YYYY');
                    crm_comments_row = `<tr class="tabel_row mb-3" id=row_${value.id}>
                        <td class="text-center pt-4">${crm_comment_serial}</td>
                        <td class="text-center pt-4">${date}</td>
                        <td class="text-center pt-4">${value.comments}</td>
                        <td class="text-center pt-4">${value.name}</td>
                        <td class="text-center pt-4"><button type="button" class="btn btn-primary">Edit</button></td>
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
    var customer_id = $("#customer_id").val();


    
    $.ajax({
        type: "POST",
        url: "{{route('customer_crm.add_reminder')}}",
        data: {
            customer_id:customer_id,
            reminder_text:reminder_text,
            date:date
        },
        success: function (response) {
            console.log(response);
        }
    });


});





}); // end of ready function


// var config = {
//     enableTime: true,
//     dateFormat: "Y-m-d H:i",
//     altInput: true,
//     altFormat: "F j, Y (h:S K)",
// };
$("input[type=datetime-local]").flatpickr(config);

$("#reminder_button").click(function (e) { 
    e.preventDefault();
    console.log("ok");
});

function product_add_function(product_id){

console.log(product_id);

}






</script>
@endsection
