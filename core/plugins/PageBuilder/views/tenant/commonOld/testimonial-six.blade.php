@php
    $user_lang = get_user_lang();
@endphp

<section class="testimonial-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="section-title startup desktop-center margin-bottom-55">
                    <h3 class="title">{{$data['title']}}</h3>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="testimonial-carousel-area">
                    <div class="testimonial-carousel-two">
                        @foreach($data['testimonial'] ?? [] as $key => $item)
                        <div class="single-testimonial-item-08">
                            <div class="thumb">
                                {!! render_image_markup_by_attachment_id($item->image) !!}
                            </div>
                            <div class="content">
                                <div class="content-wrap">
                                    <div class="icon">
                                        <i class="fas fa-quote-right"></i>
                                    </div>
                                    <p class="description">{{\Illuminate\Support\Str::words($item->getTranslation('description',$user_lang),20)}}</p>
                                    <div class="author-details">
                                        <div class="author-meta">
                                            <h4 class="title">{{$item->getTranslation('name',$user_lang)}}</h4>
                                            <div class="designation">{{$item->getTranslation('designation',$user_lang)}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
