@include('landlord.frontend.partials.header')

<div class="breadcrumb-area breadcrumb-padding section-bg-1 @if((in_array(request()->route()->getName(),['landlord.homepage','landlord.dynamic.page']) && $page_post->breadcrumb == 0 ))
     d-none
@endif">
    <div class="breadcrumb-shapes">
        <img src="{{asset('assets/img/banner/left-dot-line.png')}}" alt="">
        <img src="{{asset('assets/img/banner/right-dot-line.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-contents center-text">
                    <h2 class="breadcrumb-contents-title"> @yield('page-title') </h2>
                    <ul class="breadcrumb-contents-list mt-3">
                        <li class="breadcrumb-contents-list-item"> <a href="{{route('landlord.homepage')}}">{{__('Home')}}</a> </li>
                        <li class="breadcrumb-contents-list-item"> @yield('page-title') </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@yield('content')
@include('landlord.frontend.partials.footer')
