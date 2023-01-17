@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('User Dashboard')}}
@endsection

@section('page-title')
    {{__('User Dashboard')}}
@endsection

@section('style')
    <x-media-upload.css/>
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/user-dashboard/css/style.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/user-dashboard/css/custom-style.css')}}">
@endsection

@section('content')
    <div class="body-overlay"></div>
    <div class="seller-profile-details-area padding-bottom-100 padding-top-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 margin-top-30">
                    <div class="seller-profile-dashboard">
                        <div class="dashboard-icon">
                            <div class="sidebar-icon">
                                <i class="las la-bars"></i>
                            </div>
                        </div>
                        <div class="dashboard-close">
                            <div class="close-bars"> <i class="las la-times"></i> </div>
                            <ul class="seller-dashboard-list">
                                <li class="list @if(request()->routeIs('tenant.user.home')) active @endif">
                                    <a href="{{route('tenant.user.home')}}"> <i class="las la-th"></i> {{__('Dashboard')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('tenant.user.dashboard.package.order')) active @endif">
                                    <a href="{{route('tenant.user.dashboard.package.order')}}"> <i class="las la-tasks"></i> {{__('Order List')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('tenant.user.dashboard.package.order.refund')) active @endif">
                                    <a href="{{route('tenant.user.dashboard.package.order.refund')}}"> <i class="las la-tasks"></i> {{__('Refund Products')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('tenant.user.home.support.tickets') || request()->routeIs('tenant.frontend.support.ticket')) active @endif">
                                    <a href="{{route('tenant.user.home.support.tickets')}}"> <i class="las la-tasks"></i> {{__('Support Tickets')}} </a>
                                </li>
                                <li class="list @if(request()->routeIs('tenant.user.home.manage.account')) active @endif">
                                    <a href="{{route('tenant.user.home.manage.account')}}"> <i class="las la-tasks"></i> {{__('Manage My Account')}} </a>
                                </li>
                                <li class="list @if(request()->routeIs('tenant.user.home.change.password')) active @endif ">
                                    <a href="{{route('tenant.user.home.change.password')}}"> <i class="las la-tasks"></i> {{__('Change Password')}} </a>
                                </li>

                                <li class="list">
                                    <a href="{{ route('tenant.user.logout') }}" ><i class="las la-sign-out-alt"></i>{{ __('Logout') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 margin-top-30">
                    <x-error-msg/>
                    <x-flash-msg/>
                    @yield('section')
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $('.close-bars, .body-overlay').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $('.sidebar-icon').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });
    </script>
@endsection
