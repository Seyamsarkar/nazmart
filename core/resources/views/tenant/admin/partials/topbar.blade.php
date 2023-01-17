<style>
    .tenant_info {
        display: flex;
        align-items: center;
    }
    .tenant_info div:not(:last-child) {
        margin-right: 20px;
    }
    .tenant_info div span {
        font-size: 14px;
        font-weight: 500;
        color: #666;
    }
    .tenant_info div > span:first-child {
        font-weight: 700;
        color: var(--bs-info);
        margin-right: 5px;
        font-size: 15px;
    }
    .navbar .navbar-menu-wrapper {
        gap: 20px;
    }
    .warning-details-card i {
        font-size: 20px;
    }
    .warning-details-card .preview-thumbnail{
        padding: 5px;
    }
    .warning-details-card .preview-subject{
        margin-left: 5px;
    }
</style>

<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{route('tenant.admin.dashboard')}}">
            @php
                $logo_type = !empty(get_static_option('dark_mode_for_admin_panel')) ? 'site_white_logo' : 'site_logo';
            @endphp
            {!! render_image_markup_by_attachment_id(get_static_option($logo_type)) !!}
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{route('tenant.admin.dashboard')}}">
            {!! render_image_markup_by_attachment_id(get_static_option('site_favicon')) !!}
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <div class="tenant_info d-flex">
            @php
                $permission_limit = tenant()?->payment_log?->package;
                $page_count = \App\Models\Page::count();
                $blog_count = \Modules\Blog\Entities\Blog::count();
                $product_count = \Modules\Product\Entities\Product::withTrashed()->count();

                $page_limit = $permission_limit?->page_permission_feature == -1 ? __('Unlimited') : $permission_limit?->page_permission_feature;
                $blog_limit = $permission_limit?->blog_permission_feature == -1 ? __('Unlimited') : $permission_limit?->blog_permission_feature;
                $product_limit = $permission_limit?->product_permission_feature == -1 ? __('Unlimited') : $permission_limit?->product_permission_feature;
                $storage_limit = $permission_limit?->storage_permission_feature == -1 ? __('Unlimited') : $permission_limit?->storage_permission_feature;

                $storage_count = get_tenant_storage_info('mb');
                $storage_remaining_percent = 100-($storage_count/$permission_limit?->storage_permission_feature ?? 1)*100;

                // Inventory Warnings
                $threshold_amount = get_static_option('stock_threshold_amount');

                $inventory_product_items = \Modules\Product\Entities\ProductInventoryDetail::where('stock_count', '<', ($threshold_amount ?? 0)+1)
                ->whereHas('is_inventory_warn_able', function ($query) {
                    $query->where('is_inventory_warn_able', 1);
                })
                ->select('id', 'product_id')
                ->get();

                $inventory_product_items_id = !empty($inventory_product_items) ? $inventory_product_items->pluck('product_id')->toArray() : [];

                $products = \Modules\Product\Entities\Product::with('inventory')
                                ->where('is_inventory_warn_able', 1)
                                ->whereHas('inventory', function ($query) use ($threshold_amount) {
                                    $query->where('stock_count', '<', ($threshold_amount ?? 0) + 1);
                                })
                                ->select('id')
                                ->get();

                $products_id = !empty($products) ? $products->pluck('id')->toArray() : [];

                $every_filtered_product_id = array_unique(array_merge($inventory_product_items_id, $products_id));
                $all_products = \Modules\Product\Entities\Product::whereIn('id', $every_filtered_product_id)->select('id', 'name', 'is_inventory_warn_able')->get();
            @endphp
            <div class="tenant_info_item" id="tenant_info_list">
                <span class="tenant_info_icon"> <i class="mdi mdi-lightbulb-on-outline"></i> </span>
                <div class="tenant_info_list">

                    <div class="tenant_info_list_item">
                        <span class="tenant_info_list_title">{{__('Page:')}}</span>
                        <span class="tenant_info_list_para">{{$page_count.'/'.$page_limit}}</span>
                    </div>

                    <div class="tenant_info_list_item">
                        <span class="tenant_info_list_title">{{__('Blog:')}}</span>
                        <span class="tenant_info_list_para">
                            <span class="{{$blog_count == $permission_limit?->blog_permission_feature ? 'text-danger' : ''}}">{{$blog_count}}</span>
                            <span>/{{$blog_limit}}</span>
                        </span>
                    </div>

                    <div class="tenant_info_list_item product-limits-nav">
                        <span class="tenant_info_list_title ">{{__('Product:')}}</span>
                        <span class="tenant_info_list_para">
                            <span class="{{$product_count == $permission_limit?->product_permission_feature ? 'text-danger' : ''}}">{{$product_count}}</span>
                            <span>/{{$product_limit}}</span>
                        </span>
                    </div>

                    <div class="tenant_info_list_item">
                        <span class="tenant_info_list_title">{{__('Storage:')}}</span>
                        @php
                            $allocatedStorage = $permission_limit?->storage_permission_feature;
                            $oneThirdOfStorage = $allocatedStorage - ($allocatedStorage * 20) / 100;
                        @endphp
                        <span class="tenant_info_list_para">
                            <span class="{{$storage_remaining_percent >= $oneThirdOfStorage ? 'text-danger' : ''}}">{{round($storage_count, 3)}}</span>
                            <span>/{{$storage_limit != 'Unlimited' ? $allocatedStorage : $storage_limit}} MB</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="nav-profile-img">
                        @if(auth('admin')->user()->image != null)
                            {!! render_image_markup_by_attachment_id(optional(auth('admin')->user())->image,'','full',true) !!}
                        @else
                            <img src="{{global_asset('assets/landlord/uploads/media-uploader/no-image.jpg')}}" alt="">
                        @endif
                        <span class="availability-status online"></span>
                    </div>
                    <div class="nav-profile-text">
                        <p class="mb-1 text-black">{{optional(auth('admin')->user())->name}}</p>
                    </div>
                </a>
                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('tenant.admin.edit.profile')}}">
                        <i class="mdi mdi-account-settings me-2 text-success"></i> {{__('Edit Profile')}}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('tenant.admin.change.password')}}">
                        <i class="mdi mdi-key me-2 text-success"></i> {{__('Change Password')}}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#"
                       onclick="event.preventDefault();
                           document.getElementById('tenanat_logout_submit_btn').dispatchEvent(new MouseEvent('click'));">
                        <i class="mdi mdi-logout me-2 text-primary"></i>
                        {{__('Signout')}}
                        <form id="logout-form" action="{{ route('tenant.admin.logout') }}" method="POST" class="d-none">
                            @csrf
                            <button class="d-none" type="submit" id="tenanat_logout_submit_btn"></button>
                        </form>
                    </a>
                </div>
            </li>

            <li class="nav-item d-none d-lg-block full-screen-link">
                <a class="nav-link">
                    <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                </a>
            </li>


            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-email-outline"></i>
                    @if($new_message)
                        <span class="count-symbol bg-warning"></span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                     aria-labelledby="messageDropdown">

                    <h6 class="p-3 mb-0">{{$new_message. ' '.  __('Messages') }}  </h6>
                    <div class="dropdown-divider"></div>

                    @foreach($all_messages as $message)
                        <a class="dropdown-item preview-item" href="{{route(route_prefix().'admin.contact.message.view', $message->id)}}">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-success">
                                    <i class="las la-envelope"></i>
                                </div>
                            </div>
                            @php
                                $fields = json_decode($message->fields,true);
                            @endphp
                            <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                <h6 class="preview-subject mb-1 font-weight-normal">{{__('You have message from').' '}}
                                    <strong>{{optional($message->form)->title}}</strong></h6>
                                <p class="text-gray mb-0"> {{$message->created_at->diffForHumans() . ' '}}  @if($message->status === 1)<small class="mt-1 text-danger">{{'('.__('New' .')')}}</small>@endif</p>
                            </div>
                            <div class="dropdown-divider"></div>
                            @endforeach

                            <h6 class="p-3 mb-0 text-center">
                                <a class="dropdown-item" href="{{route(route_prefix().'admin.contact.message.all')}}">{{__('See All')}}</a>
                            </h6>
                        </a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>

                    <span class="count-symbol {{count($all_products) > 0 ? 'bg-danger' : ''}}">{{count($all_products) > 0 ? count($all_products) : ''}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <h6 class="p-3 mb-0">{{__('Stock Reminder').' ('.count($all_products).')'}}</h6>

                    @forelse($all_products->take(10) as $product)
                        @php
                            $inventory = $product?->inventory?->stock_count;
                            $variant = $product->inventoryDetail->where('stock_count', '<=', $threshold_amount)->first();
                            $variant = !empty($variant) ? $variant->stock_count : [];

                            $stock = min($inventory, $variant);
                        @endphp
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item preview-item" href="{{route('tenant.admin.product.edit', $product->id).'/inventory-tab'}}">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-warning">
                                    <i class="mdi mdi-cart-arrow-down"></i>
                                </div>
                            </div>
                            <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                <h6 class="preview-subject font-weight-normal mb-1">{{$product->name}}</h6>
                                <p class="text-gray ellipsis mb-0 text-danger"> {{sprintf(__('Remaining stock is %u'), $stock)}} </p>
                            </div>
                        </a>
                    @empty
                        <h6 class="p-3 mb-0 text-center">{{__('No data available')}}</h6>
                    @endforelse

                    @if(!empty($all_products) > 0)
                        <h6 class="p-3 mb-0 text-center">
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#warningModal">{{__('See all warnings')}}</a>
                        </h6>
                    @endif
                </div>
            </li>

            <li class="nav-item nav-logout d-none d-lg-block">
                <a class="nav-link" href="#">
                    <i class="mdi mdi-theme-light-dark"></i>
                </a>
            </li>
            <li class="nav-item nav-logout d-none d-lg-block">
                <a class="btn btn-outline-danger btn-icon-text" href="{{tenant_url_with_protocol(tenant()->domain?->domain)}}" target="_blank">
                    <i class="mdi mdi-upload btn-icon-prepend"></i> {{__('Visit Your Website')}}
                </a>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>

<!--  All Warnings Modal -->
<div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('All Stock Warnings')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @forelse($all_products as $product)
                        @php
                            $inventory = $product?->inventory?->stock_count;
                            $variant = $product->inventoryDetail->where('stock_count', '<=', $threshold_amount)->first();
                            $variant = !empty($variant) ? $variant->stock_count : [];

                            $stock = min($inventory, $variant);
                        @endphp

                        <div class="col-lg-12 col-md-12">
                            <div class="card warning-details-card mb-2">
                                <a class="dropdown-item" href="{{route('tenant.admin.product.edit', $product->id).'/inventory-tab'}}">
                                    <div class="preview-thumbnail d-flex">
                                        <div class="preview-icon text-warning">
                                            <i class="mdi mdi-cart-arrow-down"></i>
                                        </div>
                                        <h6 class="preview-subject font-weight-normal mb-1">{{$product->name}}</h6>
                                    </div>
                                    <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                        <p class="text-gray ellipsis mb-0 text-danger"> {{sprintf(__('Remaining stock is %u'), $stock)}} </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @empty
                        <h6 class="p-3 mb-0 text-center">{{__('No data available')}}</h6>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>
    </div>
</div>
