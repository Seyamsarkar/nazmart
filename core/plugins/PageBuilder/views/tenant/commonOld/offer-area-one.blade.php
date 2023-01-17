@php
    $user_lang = get_user_lang();
@endphp

<!-- Offer section Area -->
<div class="offer-area bg-image padding-bottom-400 padding-top-100" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title customer desktop-center">
                    <h3 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="offer-item-area">
    <div class="container">
        <div class="offer-item-wrap m-top">
            <div class="row">
                @php
                    $colors = ['style','style-01','style-02','style-03'];
                @endphp
                @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key=> $title)
                <div class="col-lg-6">
                    <div class="offer-single-item wow animate__animated animate__fadeInUp animated">
                        <div class="icon {{$colors[$key % count($colors)]}}">
                            <i class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i>
                        </div>
                        <div class="content style-01">
                            <h4 class="title">
                                <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}">{{$title ?? ''}}</a>
                            </h4>
                            <p>{{$data['repeater_data']['repeater_description_'.$user_lang][$key] ?? ''}}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
