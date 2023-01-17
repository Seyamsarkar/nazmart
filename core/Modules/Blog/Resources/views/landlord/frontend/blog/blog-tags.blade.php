@extends('landlord.frontend.frontend-page-master')
@section('title')
    {{ $tag_name}}
@endsection
@section('page-title')
    {{__('Tag: ').$tag_name}}
@endsection
@section('content')

    <section class="blog-content-area padding-120 ">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        @if(count($all_blogs) < 1)
                            <div class="col-lg-12">
                                <div class="alert alert-danger">
                                    {{__('No Post Available In ').$tag_name.__(' Tag')}}
                                </div>
                            </div>
                        @endif

                        @php
                            $user_lang = get_user_lang();
                        @endphp

                        @foreach($all_blogs as $data)
                            <div class="col-lg-6 col-md-6">
                                <div class="single-blog-grid margin-bottom-30">
                                    <div class="thumb">
                                        <a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}">
                                            {!! render_image_markup_by_attachment_id($data->image) !!}
                                        </a>
                                    </div>
                                    <div class="content mt-3">
                                        <ul class="post-meta">
                                            <li><a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}"><i class="las la-calendar-alt"></i> {{$data->created_at->diffForHumans()}}</a></li>
                                            <li><a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}">{{__('By ')}} <i class="las la-user"></i>{{($data->author) ?? __("Anonymous")}}</a></li>
                                        </ul>
                                        <h4 class="title mt-3"><a href="{{route(route_prefix().'frontend.blog.single', $data->slug)}}">{{$data->title}}</a></h4>
                                        <div class="description mt-3">
                                            {{ str(strip_tags($data->blog_content,55))->limit(255) }}
                                        </div>
                                        <a href="{{route(route_prefix().'frontend.blog.single', $data->slug)}}" class="readmore mt-3">{{__('Read More')}}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-lg-12">
                            {!! $all_blogs->links() !!}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog_single_sidebar_sidebar">
                        {!! render_frontend_sidebar('sidebar',['column' => false]) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
