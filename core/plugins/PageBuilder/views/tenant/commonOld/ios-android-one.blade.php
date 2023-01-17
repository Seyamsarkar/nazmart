

<div class="get-started-area-01" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title desktop-center">
                    <h2 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @php $colors = ['','style-01','style-02'] @endphp
            @foreach($data['repeater_data']['repeater_icon_'] ?? [] as $key=> $icon)
            <div class="col-lg-6">
                <div class="ios-single-item padding-top-50 wow animate__animated animate__fadeInUp animated">
                    <div class="icon {{$colors[$key % count($colors)]}}">
                        <i class="{{$icon ?? ''}}"></i>
                    </div>
                    <div class="color {{$colors[$key % count($colors)]}}"></div>
                    <a href="{{$data['repeater_data']['repeater_image_url_'][$key] ?? ''}}">
                        {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'][$key],'bg-img' ?? '') !!}
                    </a>
                </div>
            </div>
             @endforeach
        </div>
    </div>
</div>
