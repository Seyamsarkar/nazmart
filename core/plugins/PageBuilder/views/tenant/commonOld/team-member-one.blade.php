@php
    $user_lang = get_user_lang();
@endphp

<section class="creative-team" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title desktop-center padding-bottom-50">
                    <span class="subtitle">{{$data['title']}}</span>
                    <h2 class="title">{{$data['subtitle']}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($data['repeater_data']['repeater_name_'.$user_lang] ?? [] as $key => $name)
                <div class="col-lg-3 col-sm-6">
                    <div class="team-single-item">
                        <div class="thumb">
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.$user_lang][$key] ?? '' ) !!}
                        </div>
                        <div class="content">
                            <h4 class="title">{{$name ?? ''}}</h4>
                            <span>{{$data['repeater_data']['repeater_designation_'.$user_lang][$key] ?? ''}}</span>
                            <div class="social-link style-02">
                                <ul>
                                    <li><a href="{{$data['repeater_data']['repeater_facebook_url_'.$user_lang][$key] ?? '' }}"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="{{$data['repeater_data']['repeater_linkedin_url_'.$user_lang][$key] ?? '' }}"><i class="fab fa-linkedin-in"></i></a></li>
                                    <li><a href="{{$data['repeater_data']['repeater_twitter_url_'.$user_lang][$key] ?? '' }}"><i class="fab fa-twitter"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
