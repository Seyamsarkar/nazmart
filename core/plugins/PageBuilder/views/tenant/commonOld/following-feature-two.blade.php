@php
    $user_lang = get_user_lang();
    $colors = ['style-01','style-02','style-03','style-04'];
@endphp

<div id="which" class="build-area-03" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-title desktop-center margin-bottom-50">
                    <h3 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h3>
                    <p class="wow animate__animated animate__fadeInUp animated">{{$data['subtitle']}}</p>
                </div>
            </div>
        </div>
        <div class="row learning-service-item">
           @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
            <div class="col-lg-3 col-md-6">
                <div class="single-service-item-01 wow animate__animated animate__fadeInUp animated">
                    <div class="icon {{$colors[$key % count($colors)]}}">
                        <i class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? '' }}"></i>
                    </div>
                    <div class="content">
                        <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}">
                            <h3 class="title">{{$title ?? ''}}</h3>
                        </a>
                        <p>{{$data['repeater_data']['repeater_description_'.$user_lang][$key] ?? ''}}</p>
                    </div>
                </div>
            </div>
             @endforeach
        </div>
    </div>
</div>
