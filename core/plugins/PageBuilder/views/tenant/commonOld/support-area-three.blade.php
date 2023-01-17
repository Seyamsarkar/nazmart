@php
    $user_lang = get_user_lang();
@endphp

<div class="learning-bg"{!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
    <div id="overview" class="build-area learning " data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="container">
            <div class="row align-items-center ">
             @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title )
                 @php
                      $alignment = $data['repeater_data']['repeater_section_alignment_'.$user_lang][$key] ?? '';
                      $image = $data['repeater_data']['repeater_image_'.$user_lang][$key] ?? '';
                      $subtitle = $data['repeater_data']['repeater_subtitle_'.$user_lang][$key] ?? '';
                      $description = $data['repeater_data']['repeater_description_'.$user_lang][$key] ?? '';
                 @endphp
                @if($alignment == 'left')
                <div class="col-lg-6 padding-bottom-180">
                    <div class="build-img bg-image-02 wow animate__animated animate__backInLeft animated" data-parallax='{"x": , "y": 20}' {!! render_background_image_markup_by_attachment_id($image) !!}></div>
                </div>
                @endif
                <div class="col-lg-5 offset-lg-1 padding-bottom-180">
                    <div class="bulid-content-area style-01">
                        <div class="{{$data['heading_style']}}">
                            <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}">
                                <h4 class="title wow animate__animated animate__fadeInUp animated"><span></span>{{$title ?? ''}}</h4>
                            </a>
                        </div>
                        <div class="section-title">
                            <h4 class="title social-title wow animate__animated animate__fadeInUp animated">{{$subtitle}}</h4>
                            <p class="wow animate__animated animate__fadeInUp animated">{{$description}}</p>
                        </div>
                    </div>
                </div>

                @if($alignment == 'right')
                    <div class="col-lg-6">
                        <div class="build-img bg-image-02 wow animate__animated animate__backInLeft animated" {!! render_background_image_markup_by_attachment_id($image) !!}></div>
                    </div>
                @endif
             @endforeach
            </div>
        </div>
    </div>
</div>

