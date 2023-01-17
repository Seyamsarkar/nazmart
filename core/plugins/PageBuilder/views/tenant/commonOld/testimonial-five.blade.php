@php
    $user_lang = get_user_lang();
@endphp

<section class="testimonial-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-title learning desktop-center padding-bottom-55">
                    <h3 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h3>
                    <p class="wow animate__animated animate__fadeInUp animated">{{$data['subtitle']}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="testimonial-carousel-area">
                    <div class="testimonial-carousel">
                        @foreach($data['testimonial'] ?? [] as $key => $item)
                        <div class="single-testimonial-item-05 wow animate__animated animate__fadeInUp animated">
                            <div class="icon style-01">
                                <i class="flaticon-quote-left"></i>
                            </div>
                            <div class="thumb">
                               {!! render_image_markup_by_attachment_id($item->image,'','thumb') !!}
                            </div>
                            <div class="content">
                                <div class="author-details">
                                    <div class="author-meta">
                                        <h4 class="title">{{$item->getTranslation('name',$user_lang)}}</h4>
                                        <div class="designation">{{$item->getTranslation('designation',$user_lang)}}</div>
                                    </div>
                                </div>
                                <p class="description">{{\Illuminate\Support\Str::words($item->getTranslation('description',$user_lang),20)}}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
