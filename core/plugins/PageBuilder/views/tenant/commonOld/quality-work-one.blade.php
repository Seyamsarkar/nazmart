@php
    $user_lang = get_user_lang();
    $colors = ['style-01','style-02','style-03','style-04'];
@endphp

<div class="quality-works-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="section-title startup margin-bottom-55">
                            <p>{{$data['title']}}</p>
                            <h3 class="title">{{$data['subtitle']}}</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                   @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                    <div class="col-lg-6 col-md-6">
                        <div class="work-single-item margin-bottom-30">
                            <div class="content">
                                <div class="icon {{$colors[$key % count($colors)]}}">
                                    <i class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i>
                                </div>
                                <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}">
                                    <h3 class="title">{{$title ?? ''}}</h3>
                                </a>
                                <p>{{$data['repeater_data']['repeater_subtitle_'.$user_lang][$key] ?? ''}}</p>
                            </div>
                        </div>
                    </div>
                     @endforeach
                </div>
            </div>
            <div class="col-lg-5">
                <div class="work-single-img">
                   {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
