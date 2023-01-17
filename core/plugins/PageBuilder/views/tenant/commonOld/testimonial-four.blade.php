@php
    $user_lang = get_user_lang();
@endphp

<section class="testimonial-area medical" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="medical-image" data-parallax='{"x": 20, "y": 20}' {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="section-title medical desktop-center padding-top-75">
                    <h3 class="title">{{$data['title']}}</h3>
                </div>
            </div>
        </div>
        <div class="row padding-top-75">
            <div class="col-lg-6">
                <div class="testimonial-carousel-area">
                    <div class="testimonial-carousel-two">
                        @php $single_image = ''; @endphp
                        @foreach($data['testimonial'] ?? [] as $key => $item)

                            @php $single_image.= $item->image @endphp
                        <div class="single-testimonial-item-11 style-01">
                            <div class="icon">
                                <i class="flaticon-quote-left"></i>
                            </div>
                            <div class="content">
                                <p class="description">{{\Illuminate\Support\Str::words($item->getTranslation('description',$user_lang),20)}}</p>
                                <div class="author-details">
                                    <div class="author-meta">
                                        <h4 class="title">{{$item->getTranslation('name',$user_lang)}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="thumb">
                   {!! render_image_markup_by_attachment_id($data['static_testimonial_image']) !!}
                </div>
            </div>
        </div>
    </div>
</section>
