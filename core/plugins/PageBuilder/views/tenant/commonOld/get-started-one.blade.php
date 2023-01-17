@php
    $user_lang = get_user_lang();
@endphp

<div class="get-started-area customer bg-image" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!} data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-left">
            <div class="col-lg-6">
                <div class="section-title white brand padding-bottom-50 wow animate__animated animate__fadeInUp animated">
                    <div class="content-area">
                        <div class="ratings">
                            <i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="las la-star"></i>
                            <i class="las la-star"></i>
                        </div>
                        <p class="customer-para">{{$data['rating_subtitle']}}</p>
                    </div>
                    <div class="author-meta">
                        <h4 class="title">{{$data['rating_title']}}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-heading">
                    <h2 class="title wow animate__animated animate__fadeInUp animated">{{$data['content_title']}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
          @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
            <div class="col-lg-4 col-md-6">
                <div class="work-single-item-02 style-01 wow animate__animated animate__fadeInUp animated">
                    <div class="icon">
                       {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.$user_lang][$key] ?? '') !!}
                    </div>
                    <div class="content">
                      <h3 class="title">  <a href="{{$data['repeater_data']['repeater_title_url_'.$user_lang][$key] ?? ''}}">{{$title ?? ''}}</a></h3>
                        <p class="para">{{$data['repeater_data']['repeater_subtitle_'.$user_lang][$key] ?? ''}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
