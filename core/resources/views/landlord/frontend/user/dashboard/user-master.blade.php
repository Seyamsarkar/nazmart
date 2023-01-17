@extends('landlord.frontend.frontend-page-master')

@section('content')
    <style>
        .dashboard-list .list.has-children .sub-menu {
            display: none;
        }
        /*.dashboard-list .list.has-children.open .sub-menu {*/
        /*    display: block;*/
        /*}*/
        .dashboard-list .list.has-children a {
            position: relative;
        }
        .dashboard-list .list.has-children > a:after {
            content: "\f067";
            position: absolute;
            right: 20px;
            top: 16px;
            font-family: 'Line Awesome Free';
            font-weight: 900;
        }
        .dashboard-list .list.has-children.show > a:after {
            content: "\f068";
        }
        .dashboard-list .list.has-children .sub-menu {
            padding-left: 20px;
        }
        .dashboard-list .list.has-children .sub-menu li a {
            padding: 7px 15px;
            margin: 0;
        }
        .dashboard-list .list.show > a {
            background: var(--main-color-one);
        }
        .dashboard-list .list.has-children.show > .sub-menu .list.selected a {
            background: #fff;
            color: var(--main-color-one);
            font-size: 16px;
        }
        .dashboard-list .list.has-children .sub-menu li a:hover {
            background: #fff;
            color: var(--main-color-one);
        }
        .dashboard-list .list:hover a {
            background: unset;
            color: unset;
        }
        .dashboard-list .list a:hover {
            background: var(--main-color-one);
            font-weight: 500;
            color: #fff;
        }
    </style>
    <div class="container">
    <div class="body-overlay"></div>
    <div class="dashboard-area landlord dashboard-padding" data-padding-top="100" data-padding-bottom="100">
        <div class="container-fluid">
            <div class="dashboard-contents-wrapper">
                <div class="dashboard-icon">
                    <div class="sidebar-icon">
                        <i class="las la-bars"></i>
                    </div>
                </div>
                <div class="dashboard-left-content">
                    <div class="dashboard-close-main">
                        <div class="close-bars"> <i class="las la-times"></i> </div>
                        <div class="dashboard-top padding-top-40">
                            <div class="author-content">
                                <h4 class="title"> {{Auth::guard('web')->user()->name ?? __('Not Given')}} </h4>
                            </div>
                        </div>
                        <div class="dashboard-bottom margin-top-35 margin-bottom-50">
                            <ul class="dashboard-list">
                                <li class="list @if(request()->routeIs('landlord.user.home')) active @endif">
                                    <a href="{{route('landlord.user.home')}}"> <i class="las la-th"></i> {{__('Dashboard')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('landlord.user.dashboard.package.order')) active @endif">
                                    <a href="{{route('landlord.user.dashboard.package.order')}}"> <i class="las la-tasks"></i> {{__('Payment Logs')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('landlord.user.dashboard.custom.domain')) active @endif">
                                    <a href="{{route('landlord.user.dashboard.custom.domain')}}"> <i class="las la-tasks"></i> {{__('Custom Domain')}} </a>
                                </li>

                                <li class="list has-children">
                                    <a href="#0"> <i class="las la-wallet"></i> {{__('My Wallet')}} </a>
                                    <ul class="sub-menu">
                                        <li class="list {{request()->routeIs('landlord.user.wallet.history') ? 'selected' : '' }}">
                                            <a class="@if(request()->routeIs('landlord.user.wallet.history')) active-submenu @endif" href="{{route('landlord.user.wallet.history')}}">My Wallet</a>
                                        </li>
                                        <li class="list {{request()->routeIs('landlord.user.wallet.settings') ? 'selected' : '' }}">
                                            <a class="@if(request()->routeIs('landlord.user.wallet.settings')) active-submenu @endif" href="{{route('landlord.user.wallet.settings')}}">Settings</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="list @if(request()->routeIs('landlord.user.home.support.tickets')) active @endif">
                                    <a href="{{route('landlord.user.home.support.tickets')}}"> <i class="las la-tasks"></i> {{__('Support Tickets')}} </a>
                                </li>
                                <li class="list @if(request()->routeIs('landlord.user.home.edit.profile')) active @endif">
                                    <a href="{{route('landlord.user.home.edit.profile')}}"> <i class="las la-tasks"></i> {{__('Edit Profile')}} </a>
                                </li>
                                <li class="list @if(request()->routeIs('landlord.user.home.change.password')) active @endif ">
                                    <a href="{{route('landlord.user.home.change.password')}}"> <i class="las la-tasks"></i> {{__('Change Password')}} </a>
                                </li>

                                <li class="list">
                                    <a href="{{ route('landlord.user.logout') }}" ><i class="las la-sign-out-alt"></i>{{ __('Logout') }}</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>


                <div class="dashboard-right">
                    <div class="parent">
                        <div class="col-xl-12">
                            <x-error-msg/>
                            <x-flash-msg/>
                            @yield('section')
                        </div>
                    </div>
                </div>

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


