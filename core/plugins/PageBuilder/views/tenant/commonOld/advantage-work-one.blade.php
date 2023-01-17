@php
    $user_lang = get_user_lang();
@endphp

<div class="advantege-work-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="advantege-img" {!! render_background_image_markup_by_attachment_id($data['bg_image_one']) !!}></div>
        <div class="advantege-img-02"  {!! render_background_image_markup_by_attachment_id($data['bg_image_two']) !!}></div>
        <div class="row">
            <div class="col-lg-6">
                <div class="content">
                    <div class="section-title padding-bottom-50">
                        <h4 class="title wow animate__animated animate__fadeInUp animated">{{$data['title']}}</h4>
                        <p class="wow animate__animated animate__fadeInUp animated">{{$data['subtitle']}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
          @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
            <div class="col-lg-4 col-md-6">
                <div class="single-icon-box-09">
                    <div class="content">
                        <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}">
                              <h4 class="title">{{$title}}</h4>
                        </a>
                        <p>{{$data['repeater_data']['repeater_description_'.$user_lang][$key] ?? ''}}</p>
                    </div>
                    <div class="icon">
                        <i class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
