@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();

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

<section class="featured-area section-bg-1" data-padding-top="{{$data['padding_top']}}"
         data-padding-bottom="{{$data['padding_bottom']}}" id="{{$data['section_id']}}">
    <div class="featured-shapes">
        {!! render_image_markup_by_attachment_id($data['bg_shape_image'], '','full',false) !!}
    </div>
    <div class="container">
        <div class="section-title">
            {!! $final_title !!}

            <p class="section-para"> {{$data['subtitle']}} </p>
        </div>
        <div class="single-feature-wrapper">
            <div class="row g-0 mt-5">
                @foreach($data['repeater_data']['repeater_title_'.$current_lang] as $key => $info)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-feature">
                            <div class="single-feature-icon radius-10">
                                {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.$current_lang][$key], '', 'full', false) !!}
                            </div>
                            <div class="single-feature-content mt-4">
                                <h3 class="single-feature-content-title"><a
                                        href="{{$data['repeater_data']['repeater_button_link_'.$current_lang][$key] ?? '#'}}"> {{$data['repeater_data']['repeater_title_'.$current_lang][$key]}} </a>
                                </h3>
                                <p class="single-feature-content-para mt-3"> {{$data['repeater_data']['repeater_description_'.$current_lang][$key]}} </p>
                                <a href="{{$data['repeater_data']['repeater_button_link_'.$current_lang][$key] ?? '#'}}" class="single-feature-content-btn-explore mt-4"> {{$data['repeater_data']['repeater_button_text_'.$current_lang][$key]}} <i
                                        class="las la-arrow-right"></i> </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
