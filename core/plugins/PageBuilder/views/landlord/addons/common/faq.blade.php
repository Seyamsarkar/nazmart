@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp

<section class="question-area section-bg-1" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom'] ?? ''}}">
    <div class="container">
        <div class="section-title">
            {!! get_modified_title($data['title']) !!}
            <p class="section-para"> {{$data['subtitle']}} </p>
        </div>
        <div class="row align-items-center justify-content-center mt-4">
            <div class="col-xl-5 mt-4">
                <div class="question-thumb-wrapper">
                    <div class="thumb center-text">
                        {!! render_image_markup_by_attachment_id($data['left_image']) !!}
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-lg-9 mt-4">
                <div class="faq-wrapper">
                    <div class="faq-contents">
                        @php
                            $i = 1;
                        @endphp
                        @foreach($data['repeater_data']['repeater_title_'.$current_lang] as $key => $info)
                            <div class="faq-item wow fadeInLeft {{$key==1 ? 'active open' : ''}}" data-wow-delay=".{{$i++}}s">
                                <div class="faq-title">
                                    {{$data['repeater_data']['repeater_title_'.$current_lang][$key] ?? ''}}
                                </div>
                                <div class="faq-panel">
                                    <p class="faq-para"> {{$data['repeater_data']['repeater_description_'.$current_lang][$key] ?? ''}} </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
