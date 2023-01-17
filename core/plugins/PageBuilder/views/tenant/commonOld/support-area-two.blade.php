@php
    $user_lang = get_user_lang();
    $alignment = $data['section_alignment'];
    $class_condition = $alignment == 'right' ? 'style-01' : '';
@endphp

<div id="overview" class="create-content-area style-01" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="create-audio-content {{$class_condition}}">
            <div class="row align-items-center">
                @if($alignment == 'left')
                <div class="col-lg-5">
                    <div class="trip-img wow animate__animated animate__backInUp bg-image" {!! render_background_image_markup_by_attachment_id($data['image']) !!}></div>
                </div>
                @endif
                <div class="col-lg-6 offset-lg-1">
                    <div class="section-title audio-title padding-bottom-25">
                        <h4 class="title audio-title wow animate__animated animate__fadeInUp">{!! $data['title'] !!}</h4>
                        <p class="audio-pera wow animate__animated animate__fadeInUp animated">{!! $data['subtitle'] !!}</p>
                    </div>
                    <ul class="content wow animate__animated animate__fadeInUp animated">
                        @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                            @php
                                $rp_title = $title ?? '';
                                $ex = explode(' ', $rp_title) ?? [];
                                $first_words = array_slice($ex, 0,-2) ?? '';
                                $last_words = array_slice($ex, -2,2) ?? '';
                            @endphp
                        <li class="audio-list"><i aria-hidden="true" class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? '' }} style-02"></i>{{implode(' ',$first_words) ?? ''}}<strong> {{implode(' ',$last_words) ?? ''}}</strong></li>
                        @endforeach
                    </ul>
                </div>

                @if($alignment == 'right')
                <div class="col-lg-5">
                    <div class="trip-img margin-top-40 wow animate__animated animate__backInDown bg-image" {!! render_background_image_markup_by_attachment_id($data['image']) !!}></div>
                </div>
               @endif

            </div>
        </div>
    </div>
</div>

