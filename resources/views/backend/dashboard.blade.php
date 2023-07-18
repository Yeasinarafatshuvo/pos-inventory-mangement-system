@extends('backend.layouts.app')

@section('content')
    

    @can('show_dashboard')

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="shadow-xl rounded-lg pt-5 px-4 mb-5 d-flex justify-content-between align-items-end"
                    style="background-color: #91A8D0">
                    <div class="pb-5">
                        <div class="fw-500">{{ translate('Total Customers') }}</div>
                        <div class="h2 fw-700">{{ \App\Models\User::where('user_type', 'customer')->count() }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64.001" viewBox="0 0 64 64.001">
                        <g id="Group_8872" data-name="Group 8872" transform="translate(330 100)" opacity="0.5">
                            <path id="Union_27" data-name="Union 27"
                                d="M48,34V25h2v9ZM0,34V25A24.993,24.993,0,0,1,42.678,7.322,24.924,24.924,0,0,1,50,25H48A23,23,0,1,0,2,25v9Z"
                                transform="translate(-330 -70)" fill="#fff" />
                            <path id="Subtraction_44" data-name="Subtraction 44"
                                d="M68,38H66V29A23.046,23.046,0,0,0,47.136,6.369a29.165,29.165,0,0,0-3.414-2.36A24.98,24.98,0,0,1,68,29h0v9Z"
                                transform="translate(-334 -74)" fill="#fff" />
                            <path id="Subtraction_38" data-name="Subtraction 38"
                                d="M13,26A13,13,0,0,1,3.808,3.808,13,13,0,1,1,22.192,22.192,12.915,12.915,0,0,1,13,26ZM13,2A11,11,0,1,0,24,13,11.012,11.012,0,0,0,13,2Z"
                                transform="translate(-318 -100)" fill="#fff" />
                            <path id="Subtraction_43" data-name="Subtraction 43"
                                d="M31,30a13.156,13.156,0,0,1-2.717-.283A17.155,17.155,0,0,0,30,27.955c.329.03.665.045,1,.045A11,11,0,1,0,31,6c-.333,0-.669.015-1,.045a17.153,17.153,0,0,0-1.718-1.762A13.148,13.148,0,0,1,31,4a13,13,0,0,1,9.193,22.193A12.915,12.915,0,0,1,31,30Z"
                                transform="translate(-322 -104)" fill="#fff" />
                        </g>
                    </svg>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="shadow-xl rounded-lg pt-5 px-4 mb-5 d-flex justify-content-between align-items-end"
                    style="background-color: #F0C05A">
                    <div class="pb-5">
                        <div class="fw-500">{{ translate('Total Products') }}</div>
                        <div class="h2 fw-700">{{ \App\Models\Product::count() }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64.001" viewBox="0 0 64 64.001">
                        <path id="Union_29" data-name="Union 29"
                            d="M64,64H0V0H64V64h0ZM2,62H62V2H2ZM25,23V21H37V2h2V23Zm0-2V2h2V21Z" fill="#fff" opacity="0.5" />
                    </svg>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="shadow-xl rounded-lg pt-5 px-4 mb-5 d-flex justify-content-between align-items-end"
                    style="background-color: #7BC4C4">
                    <div class="pb-5">
                        <div class="fw-500">{{ translate('Total Orders') }}</div>
                        <div class="h2 fw-700">{{ \App\Models\Order::count() }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                        <path id="Union_30" data-name="Union 30"
                            d="M56,62a6.011,6.011,0,0,0,5.657-4H28.747a8.014,8.014,0,0,1-2.461,4H56v2H22q-.252,0-.5-.016Q21.252,64,21,64v-.062A8.012,8.012,0,0,1,14,56h2a6.008,6.008,0,0,0,5.5,5.98A6.008,6.008,0,0,0,27,56H64a8.009,8.009,0,0,1-8,8Zm-8-6V8h0a6.008,6.008,0,0,0-6-6h0V0a8.009,8.009,0,0,1,8,8V56ZM14,56V8H0A8.009,8.009,0,0,1,8,0H42V2H13.286A7.984,7.984,0,0,1,16,8V56ZM13.657,6A6.011,6.011,0,0,0,8,2H8A6.011,6.011,0,0,0,2.343,6ZM28,49V47H44v2Zm0-4V43H44v2Zm-8,0V43h4v2Zm8-6V37H44v2Zm0-4V33H44v2Zm-8,0V33h4v2Zm8-6V27H44v2Zm0-4V23H44v2Zm-8,0V23h4v2Zm8-6V17H44v2Zm0-4V13H44v2Zm-8,0V13h4v2Z"
                            fill="#fff" opacity="0.5" />
                    </svg>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="shadow-xl rounded-lg pt-5 px-4 mb-5 d-flex justify-content-between align-items-end"
                    style="background-color: #FF6F61">
                    <div class="pb-5">
                        <div class="fw-500">{{ translate('Total Sales') }}</div>
                        <div class="h2 fw-700">
                            {{ format_price(\App\Models\Order::where('delivery_status', '!=', 'cancelled')->sum('grand_total'), true) }}
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="64.002" height="64" viewBox="0 0 64.002 64">
                        <g id="Group_8873" data-name="Group 8873" transform="translate(-1801.1 -206)" opacity="0.5">
                            <path id="Path_18946" data-name="Path 18946"
                                d="M29.022,34.545a10.117,10.117,0,0,0-1.18-5.14,11.161,11.161,0,0,0-3.985-3.739,44.893,44.893,0,0,0-8.3-3.606,35.052,35.052,0,0,1-8.09-3.694,11.715,11.715,0,0,1-3.848-4.19A12.449,12.449,0,0,1,2.376,8.36,11.576,11.576,0,0,1,6.036-.585,14.312,14.312,0,0,1,15.579-4.16v-6.4h1.881v6.4q6.294.342,9.715,4.19T30.6,10.515H28.749a13.168,13.168,0,0,0-3.3-9.355,11.723,11.723,0,0,0-9.013-3.54A12.837,12.837,0,0,0,7.558.6a9.839,9.839,0,0,0-3.335,7.7,10.722,10.722,0,0,0,1.112,5.3,10.348,10.348,0,0,0,3.694,3.54,37.464,37.464,0,0,0,7.269,3.2,61.714,61.714,0,0,1,7.183,2.856,15.758,15.758,0,0,1,4.139,2.89,10.806,10.806,0,0,1,2.446,3.66,12.813,12.813,0,0,1,.8,4.755,11.6,11.6,0,0,1-3.54,8.808,14.468,14.468,0,0,1-9.492,3.711v6.431H15.956v-6.4Q8.7,46.774,4.736,42.925T.768,32.339H2.615q0,6.038,3.78,9.45T16.674,45.2a12.91,12.91,0,0,0,8.859-3.073A9.71,9.71,0,0,0,29.022,34.545Z"
                                transform="translate(1834.232 216.556)" fill="#fff" />
                            <path id="Path_18947" data-name="Path 18947"
                                d="M29.022,34.545a10.117,10.117,0,0,0-1.18-5.14,11.161,11.161,0,0,0-3.985-3.739,44.893,44.893,0,0,0-8.3-3.606,35.052,35.052,0,0,1-8.09-3.694,11.715,11.715,0,0,1-3.848-4.19A12.449,12.449,0,0,1,2.376,8.36,11.576,11.576,0,0,1,6.036-.585,14.312,14.312,0,0,1,15.579-4.16v-6.4h1.881v6.4q6.294.342,9.715,4.19T30.6,10.515H28.749a13.168,13.168,0,0,0-3.3-9.355,11.723,11.723,0,0,0-9.013-3.54A12.837,12.837,0,0,0,7.558.6a9.839,9.839,0,0,0-3.335,7.7,10.722,10.722,0,0,0,1.112,5.3,10.348,10.348,0,0,0,3.694,3.54,37.464,37.464,0,0,0,7.269,3.2,61.714,61.714,0,0,1,7.183,2.856,15.758,15.758,0,0,1,4.139,2.89,10.806,10.806,0,0,1,2.446,3.66,12.813,12.813,0,0,1,.8,4.755,11.6,11.6,0,0,1-3.54,8.808,14.468,14.468,0,0,1-9.492,3.711v6.431H15.956v-6.4Q8.7,46.774,4.736,42.925T.768,32.339H2.615q0,6.038,3.78,9.45T16.674,45.2a12.91,12.91,0,0,0,8.859-3.073A9.71,9.71,0,0,0,29.022,34.545Z"
                                transform="translate(1800.332 216.556)" fill="#fff" />
                        </g>
                    </svg>
                </div>
            </div>
        </div>

       
    @endcan

@endsection

@section('script')
    
@endsection
