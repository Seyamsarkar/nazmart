@php
    $user_lang = get_user_lang();
@endphp

<div class="our-organizations-area">
    <div class="container-fluid">
        <div class="row">
            @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                <div class="col-lg-4 col-md-6">
                    <div class="hard-single-item-02 margin-bottom-30">
                        <div class="thumb water-effect" {!! render_background_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.$user_lang][$key]) !!}>
                            <div class="content">
                                <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}">
                                    <h4 class="title">{{$title ?? ''}}</h4>
                                </a>
                                <p class="catagory">{{$data['repeater_data']['repeater_subtitle_'.$user_lang][$key] ?? ''}}</p>
                            </div>
                        </div>
                    </div>
                </div>
             @endforeach
        </div>
    </div>
</div>
