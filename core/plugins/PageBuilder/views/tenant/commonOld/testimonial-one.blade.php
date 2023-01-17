@php
    $user_lang = get_user_lang();
@endphp

<div class="counterup-area utility-bg style-01" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-title brand white desktop-center padding-bottom-50">
                    <h2 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h2>
                </div>
            </div>
        </div>

        <div class="testimonial_slider owl-carousel">
           @foreach($data['testimonial'] ?? [] as $item)
            <div class="testimonial-slider-single">
                <div class="row justify-content-center">
                    <div class="col-lg-2">
                        <div class="icon">
                            <i class="{{$data['icon']}}"></i>
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <div class="content wow animate__animated animate__fadeInUp animated">
                            <p class="count-pera">{{$item->getTranslation('description',$user_lang)}}</p>
                            <span><strong>{{$item->getTranslation('name',$user_lang) . ' , '}}</strong>{{$item->getTranslation('designation',$user_lang)}}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
