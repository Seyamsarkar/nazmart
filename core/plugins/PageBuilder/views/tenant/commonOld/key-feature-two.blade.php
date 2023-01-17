@php
    $user_lang = get_user_lang();
@endphp

<div class="call-to-action-area-01 bg-image" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
    <div class="container">
        <div class="call-to-action-inner style-08">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <h2 class="title style-01 padding-bottom-55">{{$data['title']}}</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="counter-item-list">
                    @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                    <div class="col-lg-4">
                        <div class="single-counterup-01 white">
                            <div class="content">
                                <div class="count-wrap"><span class="count-num">{{$data['repeater_data']['repeater_number_'.$user_lang][$key] ?? ''}}</span></div>
                                <h4 class="title">{{$title ?? ''}}</h4>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

