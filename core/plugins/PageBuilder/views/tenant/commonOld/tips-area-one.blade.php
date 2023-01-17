@php
    $user_lang = get_user_lang();
@endphp

<section class="testimonial-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-6">
                <div class="section-title desktop-center medical padding-bottom-55">
                    <h3 class="title">{{$data['title']}}</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="testimonial-carousel-three">
                    @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                    <div class="single-testimonial-12">
                        <div class="thumb">
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.$user_lang][$key] ?? '') !!}
                        </div>
                        <div class="content">
                            <p class="description">{{$title ?? ''}}</p>
                            <div class="author-details">
                                <div class="author-meta">
                                    <h4 class="title">{{$data['repeater_data']['repeater_subtitle_'.$user_lang][$key] ?? ''}}</h4>
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
