@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Page Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Page Settings")}}</h4>
                        <form action="{{route(route_prefix().'admin.general.page.settings')}}" method="POST" enctype="multipart/form-data">
                          @csrf
                               <div class="form-group  mt-3">
                                   <label for="site_logo">{{__('Home Page Display')}}</label>
                                   <select name="home_page" class="form-control">
                                       @foreach($all_home_pages as $page)
                                           <option value="{{$page->id}}" @if($page->id == get_static_option('home_page'))  selected @endif >{{$page->title}}</option>
                                       @endforeach
                                   </select>
                               </div>

                            <div class="form-group  mt-3">
                                <label for="site_logo">{{__('Blog Page Display')}}</label>
                                <select name="blog_page" class="form-control">
                                    @foreach($all_home_pages as $page)
                                        <option value="{{$page->id}}" @if($page->id == get_static_option('blog_page'))  selected @endif >{{$page->title}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if(!tenant())
                                <div class="form-group  mt-3">
                                    <label for="site_logo">{{__('Price Plan Page Display')}}</label>
                                    <select name="pricing_plan" class="form-control">
                                        @foreach($all_home_pages as $page)
                                            <option value="{{$page->id}}" @if($page->id == get_static_option('pricing_plan'))  selected @endif >{{$page->title}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group  mt-3">
                                    <label for="site_logo">{{__('Terms and Condition Page Display')}}</label>
                                    <select name="terms_condition" class="form-control">
                                        @foreach($all_home_pages as $page)
                                            <option value="{{$page->id}}" @if($page->id == get_static_option('terms_condition'))  selected @endif >{{$page->title}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group  mt-3">
                                    <label for="site_logo">{{__('Privacy Policy Page Display')}}</label>
                                    <select name="privacy_policy" class="form-control">
                                        @foreach($all_home_pages as $page)
                                            <option value="{{$page->id}}" @if($page->id == get_static_option('privacy_policy'))  selected @endif >{{$page->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if(tenant())
                                <div class="form-group  mt-3">
                                    <label for="site_logo">{{__('Shop Page Display')}}</label>
                                    <select name="shop_page" class="form-control">
                                        @foreach($all_home_pages as $page)
                                            <option value="{{$page->id}}" @if($page->id == get_static_option('shop_page'))  selected @endif >{{$page->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if(tenant())
                                <div class="form-group  mt-3">
                                    <label for="site_logo">{{__('Order Track Page Display')}}</label>
                                    <select name="track_order" class="form-control">
                                        @foreach($all_home_pages as $page)
                                            <option value="{{$page->id}}" @if($page->id == get_static_option('track_order'))  selected @endif >{{$page->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
