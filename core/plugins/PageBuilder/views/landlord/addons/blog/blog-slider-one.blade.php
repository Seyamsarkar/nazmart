@php
    if (str_contains($data['title'], '{h}') && str_contains($data['title'], '{/h}'))
    {
        $text = explode('{h}',$data['title']);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="section-shape">'. $highlighted_word .'</span>';
        $final_title = '<h2 class="title">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $data['title']).'</h2>';
    } else {
        $final_title = '<h2 class="title">'. $data['title'] .'</h2>';
    }
@endphp

<section class="blog-area section-bg-1" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}"  id="{{$data['section_id']}}">
    <div class="container">
        <div class="section-title">
            {!! $final_title !!}
            <p class="section-para"> {{$data['subtitle']}} </p>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 mt-4">
                <div class="global-slick-init slider-inner-margin dot-style-one" data-infinite="true" data-arrows="false" data-dots="true" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-responsive='[{"breakpoint": 1600,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>
                    @foreach($data['blogs'] ?? [] as $key=> $blog)
                        <div class="slick-slider-item">
                        <div class="single-blog">
                            <div class="single-blog-thumb">
                                {!! render_image_markup_by_attachment_id($blog->image ?? '', '', 'full', false) !!}
                            </div>
                            <div class="single-blog-contents mt-4">
                                <h2 class="single-blog-contents-title">
                                    <a href="{{route('landlord.frontend.blog.single',$blog['slug'])}}"> {{$blog->title}} </a>
                                </h2>
                                <div class="single-blog-contents-bottom mt-4">
                                    <a href="{{route('landlord.frontend.blog.single',$blog['slug'])}}" class="reading-btn"> {{__('Keep Reading')}} <i class="las la-arrow-right"></i> </a>
                                    <span class="min-reading"> {{$blog->created_at->format('d M Y')}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
