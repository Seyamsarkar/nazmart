@extends('landlord.frontend.frontend-page-master')
@section('title')
    {{ $category_name}}
@endsection

@section('page-title')
    {{ $category_name}}
@endsection

@section('content')
    <section class="blog-content-area padding-120 ">
        <div class="container">
            <div class="row">
                @forelse($all_blogs as $data)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-blog-grid margin-bottom-30">
                            <div class="thumb">
                                <a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}">
                                    {!! render_image_markup_by_attachment_id($data->image) !!}
                                </a>
                            </div>
                            <div class="content mt-3">
                                <ul class="post-meta">
                                    <li>
                                        <a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}"><i
                                                class="las la-calendar-alt"></i> {{$data->created_at->diffForHumans()}}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}">
                                            {{__('By ')}}
                                            <i class="las la-user"></i>{{($data->author) ?? __("Anonymous")}}</a>
                                    </li>
                                </ul>
                                <h4 class="title mt-3">
                                    <a href="{{route(route_prefix().'frontend.blog.single', $data->slug)}}">
                                        {{$data->title}}
                                    </a>
                                </h4>
                                <div class="description mt-3">
                                    {{ str(strip_tags($data->blog_content,55))->limit(255) }}
                                </div>
                                <div class="btn-wrapper mt-3">
                                    <a href="{{route(route_prefix().'frontend.blog.single', $data->slug)}}" class="readmore font-weight-bold">{{__('Read More')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="col-lg-12">
                        <div class="alert alert-danger">
                            {{__('No Post Available In ').$category_name.__(' Category')}}
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-lg-12">
                    <div class="pagination_wrapper">
                        {!! $all_blogs->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
