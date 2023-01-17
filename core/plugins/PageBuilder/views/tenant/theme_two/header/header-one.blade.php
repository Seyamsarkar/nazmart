<div class="banner-area banner-two section-bg-3 theme-two-banner-padding" data-padding-top="{{$data['padding_top']}}"
     data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container container-one">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="banner-content-wrapper">
                    <div class="global-slick-init recent-slider nav-style-one" data-infinite="true" data-arrows="true"
                         data-dots="false" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="6000"
                         data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                         data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>'>
                        @foreach($data['repeater_data']['title_'] as $key => $value)
                            @php
                                $title = $value ?? '';
                                $subtitle = $data['repeater_data']['subtitle_'][$key] ?? '';
                                $button_text = $data['repeater_data']['shop_button_text_'][$key] ?? '';
                                $button_url = $data['repeater_data']['shop_button_url_'][$key] ?? '';
                                $button_color = $data['repeater_data']['button_color_'][$key] ? 'background:'.$data['repeater_data']['button_color_'][$key] : '';
                                $figure_image = $data['repeater_data']['figure_image_'][$key] ?? '';

                                $image = get_attachment_image_by_id($figure_image);
                                $image_shape = $image != null ? $image['img_url'] : '';
                            @endphp
                            <div class="banner-image">
                                <div class="row align-items-center flex-column-reverse flex-lg-row">
                                    <div class="col-lg-6">
                                        <div class="banner-image-content">
                                            <h2 class="banner-image-content-title fw-500 mt-3">
                                                <a href="javascript:void(0)"> {{$title}} </a>
                                            </h2>
                                            <p class="banner-image-content-para mt-3"> {{$subtitle}} </p>
                                            <div class="btn-wrapper">
                                                <a href="{{$button_url}}"
                                                   class="cmn-btn cmn-btn-bg-3 radius-0 mt-4 mt-lg-5" style="{{$button_color}}"> {{$button_text}} </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="banner-image-thumb">
                                            <img src="{{$image_shape}}" alt="img">
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
</div>
