<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left">
                @if (get_setting('system_logo_white') != null)
                    <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_white')) }}"
                        class="brand-icon" alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100" src="{{ static_asset('assets/img/logo-white.png') }}"
                        class="brand-icon" alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <ul class="aiz-side-nav-list" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <path id="Path_18917" data-name="Path 18917"
                                d="M3.889,11.889H9.222A.892.892,0,0,0,10.111,11V3.889A.892.892,0,0,0,9.222,3H3.889A.892.892,0,0,0,3,3.889V11A.892.892,0,0,0,3.889,11.889Zm0,7.111H9.222a.892.892,0,0,0,.889-.889V14.556a.892.892,0,0,0-.889-.889H3.889A.892.892,0,0,0,3,14.556v3.556A.892.892,0,0,0,3.889,19Zm8.889,0h5.333A.892.892,0,0,0,19,18.111V11a.892.892,0,0,0-.889-.889H12.778a.892.892,0,0,0-.889.889v7.111A.892.892,0,0,0,12.778,19ZM11.889,3.889V7.444a.892.892,0,0,0,.889.889h5.333A.892.892,0,0,0,19,7.444V3.889A.892.892,0,0,0,18.111,3H12.778A.892.892,0,0,0,11.889,3.889Z"
                                transform="translate(-3 -3)" fill="#707070" />
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>


                <!-- Inventory -->
                @can('inventory_manage')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Inventory') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->

                    <ul class="aiz-side-nav-list level-2">
                        @can('inventory_manage')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('pos.inventory.home')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['pos.inventory.home']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('Inventory  Manage') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                        @can('purchase_price_invoice')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('orders.purchase_order.home') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes([ 'orders.purchase_order.home']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Purchase Order list') }}</span>
                                    </a>
                                </li>
                        @endcan
                        @can('purchase_due_list')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('orders.purchase_order.due_list') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes([ 'orders.purchase_order.due_list']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Purchase Due list') }}</span>
                                    </a>
                                </li>
                        @endcan
                        @can('purchase_reuturn_product')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('purchase.return.product') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['purchase.return.product']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Purchase Return Product') }}</span>
                                    </a>
                                </li>
                        @endcan
                        @can('purchase_reuturn_product')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('purchase.return.product.list') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes([ 'purchase.return.product.list']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Purchase Return Product List') }}</span>
                                    </a>
                                </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                <!-- Inventory End-->

                <!-- Product -->

                <!-- Quotation -->
                @can('pos_sale')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('POS') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->

                    <ul class="aiz-side-nav-list level-2">
                        @can('pos_sale')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('pos.dashboard')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['pos.dashboard']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('POS Sale') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                        <!-- @can('inventory_manage')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('pos.inventory.home')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['pos.inventory.home']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('Inventory  Manage') }}
                                    </span>
                                </a>
                            </li>
                        @endcan -->
                    </ul>
                </li>
                @endcan
                <!-- Product -->
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Product') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        @can('show_products')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('product.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['product.index', 'product.create', 'product.edit', 'product_bulk_upload.index']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ addon_is_activated('multi_vendor') ? translate('Inhouse Products') : translate('Products') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                       
                        @can('wastage_products')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('product.wastage_home') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['product.wastage_home']) }}">
                                <span class="aiz-side-nav-text">
                                    {{ translate('Wastage Products') }}
                                </span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('product.wastage_list') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['product.wastage_list']) }}">
                                <span class="aiz-side-nav-text">
                                    {{ translate('Wastage Products List') }}
                                </span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>

                <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="Subtraction_32" data-name="Subtraction 32"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16ZM7,11a1,1,0,1,0,0,2h6a1,1,0,0,0,0-2ZM3,11a1,1,0,1,0,1,1A1,1,0,0,0,3,11ZM7,7A1,1,0,1,0,7,9h6a1,1,0,0,0,0-2ZM3,7A1,1,0,1,0,4,8,1,1,0,0,0,3,7ZM7,3A1,1,0,1,0,7,5h6a1,1,0,0,0,0-2ZM3,3A1,1,0,1,0,4,4,1,1,0,0,0,3,3Z"
                                    fill="#707070" />
                            </svg>
                            <span class="aiz-side-nav-text">{{ translate('Orders') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('show_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('orders.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['orders.index', 'orders.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Inhouse Orders') }}</span>
                                    </a>
                                </li>
                            @endcan
                            
                            @can('return_product')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('orders.return_products') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['orders.return_products', 'orders.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Return Products') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('return_product_list')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('orders.return_product_list') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['orders.return_product_list', 'orders.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Return Products  List') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('order_cancel_list')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('orders.cancel_list') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['orders.cancel_list', 'orders.show']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Order Cancel  List') }}</span>
                                </a>
                            </li>
                        @endcan
                        </ul>
                    </li>


                    <!-- Customers -->
                    @can('show_customers')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('customers.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['customers.index', 'customers.show']) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14">
                                    <g id="Group_8860" data-name="Group 8860" transform="translate(30 -252)">
                                        <path id="Rectangle_16218" data-name="Rectangle 16218"
                                            d="M4,0H6a4,4,0,0,1,4,4V7a0,0,0,0,1,0,0H1A1,1,0,0,1,0,6V4A4,4,0,0,1,4,0Z"
                                            transform="translate(-30 259)" fill="#707070" />
                                        <circle id="Ellipse_612" data-name="Ellipse 612" cx="3" cy="3" r="3"
                                            transform="translate(-28 252)" fill="#707070" />
                                        <path id="Subtraction_33" data-name="Subtraction 33"
                                            d="M16,8H12V5a4.98,4.98,0,0,0-1.875-3.9A4.021,4.021,0,0,1,11,1h2a4.005,4.005,0,0,1,4,4V7A1,1,0,0,1,16,8Z"
                                            transform="translate(-31 258)" fill="#707070" />
                                        <path id="Subtraction_34" data-name="Subtraction 34"
                                            d="M10,7A3.013,3.013,0,0,1,7.584,5.778a4.008,4.008,0,0,0,0-3.557A3,3,0,1,1,10,7Z"
                                            transform="translate(-29 251)" fill="#707070" />
                                    </g>
                                </svg>
                                <span class="aiz-side-nav-text">{{ translate('Customers') }}</span>
                            </a>
                        </li>
                    @endcan

                <!-- Supplier Start-->
                @can('supplier')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Supplier') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->

                    <ul class="aiz-side-nav-list level-2">
                        @can('show_orders')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('supplier_manage.view') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['supplier_manage.view']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Supplier Manage') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                <!-- Supplier System End -->


            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
