<div class="topbar-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="topbar-inner">
                    <div class="left-contnet">
                        <ul class="social-icon">
                            @foreach($all_social_icons as $data)
                                <li><a href="{{ $data->url }}"><i class="{{ $data->icon }}"></i></a></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="right-contnet">
                        <ul class="user-account">
                            @if (auth()->check())
                                @php
                                    $route = auth()->guest() == 'admin' ? route('tenant.admin.dashboard') : route('tenant.user.home');
                                @endphp
                                <li><a href="{{ $route }}">{{ __('Dashboard') }}</a> <span>/</span>
                                    <a href="{{ route('tenant.user.logout') }}">{{ __('Logout') }}</a>
                                </li>
                            @else
                                <li><a href="{{ route('tenant.user.login') }}">{{ __('Login') }}</a> <span>/</span>
                                    <a href="{{ route('tenant.user.register') }}">{{ __('Register') }}</a></li>
                            @endif
                        </ul>
                        <div class="language_dropdown @if(get_user_lang_direction() == 'rtl') ml-1 @else mr-1 @endif d-sm-none" id="languages_selector">
                            @if (auth()->check())
                                @php
                                    $route = auth()->guest() == 'admin' ? route('tenant.admin.dashboard') : route('tenant.user.home');
                                @endphp
                                <div class="selected-language">{{ __('Account') }}<i class="fas fa-caret-down"></i></div>
                                <ul>
                                    <li><a href="{{ $route }}">{{ __('Dashboard') }}</a>
                                    <li><a href="{{ route('tenant.user.logout') }}">{{ __('Logout') }}</a></li>
                                </ul>
                            @else
                                <div class="selected-language">{{ __('Login') }}<i class="fas fa-caret-down"></i></div>
                                <ul>
                                    <li><a href="{{ route('tenant.user.login') }}">{{ __('Login') }}</a>
                                    <li><a href="{{ route('tenant.user.register') }}">{{ __('Register') }}</a>
                                </ul>
                            @endif
                        </div>
                        @if (!empty(get_static_option('language_selector_status')))
                            <div class="language_dropdown" id="languages_selector">
                                <div class="selected-language" data-toggle="dropdown">{{ get_language_name_by_slug(get_user_lang()) }} <i
                                        class="las la-angle-down"></i></div>
                                <ul class="dropdown-menu">
                                    @foreach (\App\Models\Language::all() as $lang)
                                        <li data-value="{{ $lang->slug }}">{{ $lang->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
