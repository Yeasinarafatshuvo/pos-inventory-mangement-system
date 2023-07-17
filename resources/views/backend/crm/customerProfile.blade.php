@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body text-center">
                    <span class="avatar avatar-xxl mb-3">
                            <img src="https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-PNG-Image-File.png"> 
                    </span>
                    <h1 class="h5 mb-1">{{$client_information->client_title}}</h1>
                    <div class="text-left mt-5">
                        <h6 class="separator mb-4 text-left"><span
                                class="bg-white pr-3">{{ translate('Client Information') }}</span></h6>
                        <p class="text-muted">
                            <strong>{{ translate('Full Name') }} :</strong>
                            <span class="ml-2">{{$client_information->client_title}}</span>
                        </p>
                        <p class="text-muted"><strong>{{ translate('Email') }} :</strong>
                            <span class="ml-2">{{$client_information->client_title}}@gmail.com</span>
                        </p>
                    </div>
                    <div class="text-left mt-5">
                        <h6 class="separator mb-4 text-left">
                            <span class="bg-white pr-3">{{ translate('Others Information') }}
                            </span>
                        </h6>
                        <p class="text-muted">
                            <strong>{{ translate('Number of Orders') }} :</strong>
                            <span class="ml-2"></span>
                        </p>
                        <p class="text-muted">
                            <strong>{{ translate('Ordered Amount') }} :</strong>
                            <span class="ml-2"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    {{ translate('Orders of this customer') }}
                </div>
                <div class="card-body">
                    {{-- <table class="table aiz-table mb-0">
                        <thead>
                            <tr>

                            </tr> 
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
