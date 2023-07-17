@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
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
                    
                </div>

                <div class="card-body">
                <form class="form-horizontal" action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
                	@csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="{{translate('Name')}}" name="name" value="{{ $user->name }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Email')}}</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" placeholder="{{translate('Email')}}" name="email" value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Phone')}}</label>
                        <div class="col-sm-9">
                            <input type="phone" class="form-control" placeholder="{{translate('Phone')}}" name="phone" value="{{ $user->phone }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="new_password">{{translate('New Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" placeholder="{{translate('New Password')}}" name="new_password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="confirm_password">{{translate('Confirm Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" placeholder="{{translate('Confirm Password')}}" name="confirm_password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Avatar')}} <small>(100x100)</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="avatar" class="selected-files" value="{{ Auth::user()->avatar }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address">{{translate('address')}}</label>
                        <div class="col-sm-9">
                            <textarea type="text" class="form-control" placeholder="{{translate('Address')}}" name="address" >{{ (!empty($address->address))?$address->address:"" }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address">{{translate('State')}}</label>
                        <div class="col-sm-9 dropdown">
                            <select class="form-control aiz-selectpicker" name="state" id="state" data-live-search="true">
                                @foreach($states as $state)
                                 <option {{ (!empty($address->state_id) && $address->state_id==$state->id) ? "selected" : "" }} value="{{$state->id}}">{{$state->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address">{{translate('City')}}</label>
                        <div class="col-sm-9 dropdown">
                            <select class="form-control aiz-selectpicker" name="city" id="city" data-live-search="true">
                                @foreach($cities as $city)
                                 <option {{ (!empty($address->city_id) && $address->city_id==$city->id) ? "selected" : "" }} value="{{$city->id}}">{{$city->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address">{{translate('Shipping Phone')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="{{translate('Shipping Phone')}}" name="shipping_phone" value="{{ (!empty($address->phone))?$address->phone:$user->phone }}" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address">{{translate('Postal Code')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="{{translate('Post Code')}}" name="postal_code" value="{{ (!empty($address->postal_code))?$address->postal_code:"" }}" >
                        </div>
                    </div>
                    
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>

            </div>
            
        </div>
    </div>
@endsection
