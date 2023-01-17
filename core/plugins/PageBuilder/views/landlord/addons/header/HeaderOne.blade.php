@php
    $title =        $data['title'];
    $subtitle =     $data['subtitle'];
    $button_text =  $data['button_text'];
    $button_url =   $data['button_url'];
    $button_icon=   $data['button_icon'];
    $padding_top =  $data['padding_top'];
    $padding_bottom =  $data['padding_bottom'];

    if(!empty($title)){
        $ex = explode(' ' ,$title) ;
        if (count($ex) > 1)
            {
                $first_three_word =  $ex[0]. ' '. $ex[1]. ' ' .$ex[2];
                $third_word = $ex[3] . ' ' .$ex[4];
                $remaining_last = array_slice($ex,5) ;
                $implode_last = implode(' ', $remaining_last);
            }
    }
@endphp

<div class="banner-area banner-padding section-bg-1" data-padding-top="{{$padding_top}}"
     data-padding-bottom="{{$padding_bottom}}" id="{{$data['section_id']}}">
    <div class="banner-shpes">
        {!! render_image_markup_by_attachment_id($data['bg_shape_image']) !!}
        {!! render_image_markup_by_attachment_id($data['left_shape_image']) !!}
        {!! render_image_markup_by_attachment_id($data['right_shape_image']) !!}
    </div>
    <div class="container">
        <div class="row justify-content-between align-items-center flex-column-reverse flex-lg-row">
            <div class="col-lg-6 mt-4">
                <div class="banner-content-wrapper">
                    <div class="banner-content">
                        @php
                            if (str_contains($data['title'], '{h}') && str_contains($data['title'], '{/h}'))
                                {
                                    $text = explode('{h}',$data['title']);

                                    $highlighted_word = explode('{/h}', $text[1])[0];

                                    $highlighted_text = '<span class="banner-content-title-shape">'. $highlighted_word .'</span>';
                                    $final_title = '<h2 class="banner-content-title">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $data['title']).'</h2>';
                                } else {
                                    $final_title = '<h2 class="banner-content-title">'. $data['title'] .'</h2>';
                                }
                        @endphp

                        {!! $final_title !!}

                        <p class="banner-content-para mt-4"> {{$data['subtitle']}} </p>
                        <div class="btn-wrapper mt-4 mt-lg-5">
                            <a href="{{$data['button_url'] ?? 'javascript:void(0)'}}"
                               class="cmn-btn cmn-btn-bg-1"> {{$data['button_text']}} <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4">
                <div class="banner-thumb-wrapper">
                    <div class="banner-radius-shape">
                        {!! render_image_markup_by_attachment_id($data['right_background_shape']) !!}
                    </div>
                    <div class="banner-thumb-content-wrapper">
                        <div class="banner-thumb-content">
                            <div class="banner-thumb-content-shapes">
                                {!! render_image_markup_by_attachment_id($data['right_floating_image_1']) !!}
                                {!! render_image_markup_by_attachment_id($data['right_floating_image_2']) !!}
                                {!! render_image_markup_by_attachment_id($data['right_floating_image_3']) !!}
                            </div>
                            <div class="banner-thumb">
                                {!! render_image_markup_by_attachment_id($data['right_foreground_image']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
