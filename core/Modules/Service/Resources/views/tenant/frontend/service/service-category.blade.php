@extends('tenant.frontend.frontend-page-master')

@section('title')
    {{ $category_name}}
@endsection

@section('page-title')

    {{__('Category: ').$category_name}}
@endsection

@section('content')
    <section class="blog-content-area" data-padding-top="110" data-padding-bottom="110">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        @if(count($all_services) < 1)
                            <div class="col-lg-12">
                                <div class="alert alert-danger">
                                    {{__('No Post Available In ').$category_name.__(' Category')}}
                                </div>
                            </div>
                        @endif

                        @php
                            $user_lang = get_user_lang();
                        @endphp

                        @foreach($all_services as $data)
                            <div class="col-lg-6 col-md-6">
                                <div class="single-blog-grid margin-bottom-30">
                                    <div class="thumb">
                                        {!! render_image_markup_by_attachment_id($data->image) !!}
                                    </div>
                                    <div class="content">
                                        <ul class="post-meta">
                                            <li><a href="{{route(route_prefix().'frontend.service.single',$data->slug)}}"><i class="las la-calendar-alt"></i> {{$data->created_at->diffForHumans()}}</a></li>
                                            <li><a href="{{route(route_prefix().'frontend.service.single',$data->slug)}}">{{__('By ')}} <i class="las la-user"></i>{{($data->author) ?? __("Anonymous")}}</a></li>
                                        </ul>
                                        <h4 class="title"><a href="{{route(route_prefix().'frontend.service.single', $data->slug)}}">{{$data->getTranslation('title',$user_lang)}}</a></h4>
                                        <div class="description">
                                            {!! Str::words($data->blog_content,55) !!}
                                        </div>
                                        <a href="{{route(route_prefix().'frontend.service.single', $data->slug)}}" class="readmore">{{__('Read More')}}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-lg-12">
                            {!! $all_services->links() !!}
                    </div>
                </div>
                <div class="col-lg-4">
                    {!! render_frontend_sidebar('service',['column' => false]) !!}
                </div>
            </div>
        </div>
    </section>
@endsection
