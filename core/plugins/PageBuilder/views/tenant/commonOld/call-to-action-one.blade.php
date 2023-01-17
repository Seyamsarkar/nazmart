@php
    $user_lang = get_user_lang();
    $colors = ['style-01','style-02','style-03','style-04'];
@endphp

<div class="call-to-action-area bg-image" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="offer-item-area">
        <div class="container">
            <div class="offer-item-wrap learning-app">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="section-title desktop-center padding-bottom-75">
                            <h3 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h3>
                            <p class="wow animate__animated animate__fadeInUp animated">{{$data['subtitle']}}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                        <div class="col-lg-6">
                            <div class="offer-single-item style-01 wow animate__animated animate__fadeInUp animated">
                                <div class="icon {{$colors[$key % count($colors)]}}">
                                    <i class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i>
                                </div>
                                <div class="content padding-bottom-55">
                                    <h4 class="title">
                                        <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}">{{$title ?? ''}}</a>
                                    </h4>
                                    <p>{{$data['repeater_data']['repeater_description_'.$user_lang][$key] ?? ''}} </p>
                                </div>
                            </div>
                        </div>
                   @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

