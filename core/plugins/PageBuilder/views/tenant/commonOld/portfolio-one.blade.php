@php
    $user_lang = get_user_lang();
@endphp

<div class="organizations-header-section" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="organizations-header-wrap margin-bottom-30 bg-image" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-title brand white desktop-center">
                        <h3 class="title">{{$data['title']}}</h3>
                            <p>{{$data['subtitle']}}</p>

                            <a href="{{$data['button_url']}}">
                                <span>{{$data['button_text']}}</span>
                            </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
