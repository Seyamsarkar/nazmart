@php
    $user_lang = get_user_lang();
    $bg_color = $data['theme_color'];
    $theme_title_condition = $bg_color == 'style-01' ? '' : 'gym';
    $theme_loop_title_condition = $bg_color == 'style-01' ? 'style-01' : '';
    $theme_loop_description_condition = $bg_color == 'style-01' ? 'style-02' : '';
@endphp

<section class="blog-area {{ $bg_color ?? ''}}" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="section-title {{$theme_title_condition}} desktop-center padding-bottom-50">
                    <h3 class="title wow animate__animated animate__fadeInUp animated ">{{$data['title']}}</h3>
                </div>
            </div>
        </div>
        <div class="row">

            @foreach($data['blogs'] ?? [] as $item)
            <div class="col-md-6 col-lg-4">
                <div class="single-blog-grid-01 margin-bottom-30 wow animate__animated animate__fadeInUp animated">
                    <div class="thumb">
                       {!! render_image_markup_by_attachment_id($item->image) !!}
                        <div class="news-date {{$data['button_color']}}">
                            <h5 class="title">{{date('d M, Y', strtotime($item->created_at))}}</h5>
                        </div>
                    </div>
                    <div class="content">
                        <h4 class="title {{$theme_loop_title_condition}}"><a href="{{route('tenant.frontend.blog.single',$item->slug)}}">{{$item->title}}</a></h4>
                        <p class="{{$theme_loop_description_condition}}">{!! Str::words($item->blog_content) !!}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
