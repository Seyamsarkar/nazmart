@php
    $user_lang = get_user_lang();
@endphp
<div id="triangle" class="build-area-02 padding-top-110 padding-bottom-75">
    <div class="container">
        <div class="audio-triangle-area">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    @foreach($data['repeater_data_one']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                        <div class="audio-single-item margin-bottom-60">
                            <div class="content style-01">
                                <a href="{{$data['repeater_data_one']['repeater_title_url_'.$user_lang][$key] ?? '' }}"> <h4 class="title wow animate__animated animate__fadeInUp animated">{{$title}}</h4></a>
                                <p class="audio-pera wow animate__animated animate__fadeInUp"> {{$data['repeater_data_one']['repeater_description_'.$user_lang][$key] ?? ''}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-lg-6">
                    <div class="audio-bg" {!! render_background_image_markup_by_attachment_id($data['bg_image'] ?? '') !!}>
                        {!! render_image_markup_by_attachment_id($data['image'],'bg-img wow animate__animated animate__fadeInUp animated') !!}
                    </div>
                </div>

                <div class="col-lg-3">
                    @foreach($data['repeater_data_two']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                    <div class="content margin-bottom-60">
                        <a href="{{$data['repeater_data_one']['repeater_title_url_'.$user_lang][$key] ?? '' }}"></a> <h4 class="title wow animate__animated animate__fadeInUp animated">{{$title}}</h4>
                        <p class="style-01 wow animate__animated animate__fadeInUp">{{$data['repeater_data_one']['repeater_description_'.$user_lang][$key] ?? '' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
