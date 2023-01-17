@php
    $user_lang = get_user_lang();
    $title = $data['title'];
    $ex = explode(' ', $title) ?? [];
    $first_words = array_slice($ex, 0,-5) ?? '';
    $last_words = array_slice($ex, -5,5) ?? '';


@endphp

<div id="audio-map" class="audio-map-section " data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-title desktop-center padding-bottom-40">
                    <h3 class="title social-title wow animate__animated animate__fadeInUp animated">{{implode(' ', $first_words)}} <br>{{implode(' ', $last_words)}}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="map-section style-01">
    <div class="bg-image" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="testimonial-carousel-four">
                       @foreach($data['testimonial'] as $item)

                        <div class="single-carousel-item">
                            <div class="content">
                                <div class="icon">
                                    <i class="{{$data['icon']}}"></i>
                                </div>
                                <div class="section-title desktop-center wow animate__animated animate__fadeInUp animated">
                                    <p class="audio-pera">{!! $item->getTranslation('description',$user_lang) !!}</p>
                                    <span> <strong> {{$item->getTranslation('name',$user_lang)}} </strong>, {{$item->getTranslation('designation',$user_lang)}} </span>
                                </div>
                            </div>
                        </div>
                       @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
