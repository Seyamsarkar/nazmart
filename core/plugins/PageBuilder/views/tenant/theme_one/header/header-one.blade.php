    <div class="banner-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="banner-social-content">
        @foreach($data['social_repeater']['social_media_name_'] as $key => $value)
            <a href="{{$data['social_repeater']['social_media_url_'][$key]}}" class="item"> {{$value}} </a>
        @endforeach
    </div>
    <div class="jacket-text-shape">
        {!! render_image_markup_by_attachment_id($data['foreground_image'] ?? '') !!}
    </div>
    <div class="container container-one">
        <div class="row justify-content-center section-bg-2">
            <div class="col-lg-12">
                <div class="banner-content-wrapper">
                    <div class="global-slick-init dot-style-one dot-color-two banner-dots dot-absolute"
                         data-infinite="true" data-arrows="true" data-dots="true" data-autoplaySpeed="3000"
                         data-autoplay="true">
                        @foreach($data['repeater_data']['title_'] as $key => $value)
                            @php
                                $title = \App\Helpers\SanitizeInput::esc_html($value) ?? '';
                                $subtitle = \App\Helpers\SanitizeInput::esc_html($data['repeater_data']['subtitle_'][$key]) ?? '';
                                $button_text = \App\Helpers\SanitizeInput::esc_html($data['repeater_data']['shop_button_text_'][$key]) ?? '';
                                $button_url = \App\Helpers\SanitizeInput::esc_url($data['repeater_data']['shop_button_url_'][$key]) ?? '';
                                $background_image = $data['repeater_data']['background_image_'][$key] ?? '';
                                $figure_image = $data['repeater_data']['figure_image_'][$key] ?? '';

                                $image_shape = get_attachment_image_by_id($background_image)['img_url'] ?? '';
                            @endphp
                            <div class="banner-image">
                                <style>
                                    .banner-content-wrapper .after-shape-{{$key}}::after{
                                        background-image: url("{{$image_shape}}");
                                    }
                                </style>
                                <div class="banner-image-thumb after-shape-{{$key}}">
                                    {!! render_image_markup_by_attachment_id($figure_image ?? '') !!}
                                </div>
                                <div class="banner-image-content">
                                    <h2 class="banner-image-content-title fw-500 mt-3">
                                        <a href="javascript:void(0)"> {!! get_tenant_highlighted_text($title) !!} </a>
                                    </h2>
                                    <p class="banner-image-content-para mt-3"> {{$subtitle}} </p>
                                    <div class="btn-wrapper">
                                        <a href="{{$button_url}}" class="cmn-btn cmn-btn-bg-2 radius-0 mt-4 mt-lg-5"> {{$button_text}} </a>
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
