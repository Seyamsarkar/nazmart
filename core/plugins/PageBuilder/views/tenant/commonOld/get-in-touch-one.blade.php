@php
    $user_lang = get_user_lang();
@endphp

<div class="get-in-touch-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="bg-image" {!! render_background_image_markup_by_attachment_id($data['left_image']) !!}></div>
    <div class="bg-image-02" {!! render_background_image_markup_by_attachment_id($data['right_image']) !!}></div>
    <div class="bg-image-03" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 offset-lg-3">
                <div class="section-title brand white">
                    <p>{{$data['title']}}</p>
                    <div class="title">{{$data['subtitle']}} <br> {{$data['description']}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
