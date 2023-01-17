@php
    $user_lang = get_user_lang();
    $column_check = $data['section_alignment'] == 'left' ? '6' : '6';
@endphp

<div class="tracks-manages-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row align-items-center">
            @if($data['section_alignment'] == 'left')
                <div class="col-lg-6">
                    <div class="supports-img bg-image wow animate__animated animate__backInUp" data-parallax='{"x": -20, "y": 0}' {!! render_background_image_markup_by_attachment_id($data['image']) !!}></div>
                </div>
           @endif

            <div class="col-lg-{{$column_check}}">
                <div class="tracks-content-area padding-bottom-50">
                    <div class="section-title text-left wow animate__animated animate__fadeInUp animated">
                        <h4 class="{{$data['heading_style'] ?? ''}}">{{$data['title']}}</h4>
                        <p class="tracks-pera">{{$data['subtitle']}}</p>
                        <ul class="content">
                            @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                            <li><i aria-hidden="true" class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i>{{$title ?? ''}}</li>
                            @endforeach
                        </ul>

                        @if(!empty($data['button_text']))
                        <div class="btn-wrapper padding-top-30">
                            <div class="btn-startup style-01 boxed-btn {{$data['button_color'] ?? ''}}"><a href="{{$data['button_url']}}">{{$data['button_text']}}</a></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($data['section_alignment'] == 'right')
                <div class="col-lg-6">
                    <div class="supports-img bg-image wow animate__animated animate__backInUp" data-parallax='{"x": -20, "y": 0}' {!! render_background_image_markup_by_attachment_id($data['image']) !!}></div>
                </div>
            @endif
        </div>
    </div>
</div>
