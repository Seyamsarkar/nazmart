@php
    $user_lang = get_user_lang();
    $colors = ['style-01','style-04','style-02','style-03','style-05'];
@endphp

<div class="header-bottom-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
            <div class="col-lg-4 col-md-6">
                <div class="work-single-item padding-bottom-55">
                    <div class="content">
                        <div class="icon {{$colors[$key % count($colors)]}}">
                            <i class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i>
                        </div>
                        <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}"><h3 class="title wow animate__animated animate__fadeInUp">{{$title ?? ''}}</h3></a>
                        <p class="utility-para wow animate__animated animate__fadeInUp animated">{{$data['repeater_data']['repeater_description_'.$user_lang][$key] ?? ''}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
