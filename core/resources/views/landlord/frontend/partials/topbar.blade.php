<div class="topbar-area">
    <div class="container-max">
        <div class="row">
            <div class="col-lg-12">
                <div class="topbar-inner">
                    <div class="left-content">
                        <ul class="social-icon">
                            <li><a href="{{get_static_option('topbar_twitter_url')}}"><i class="lab la-twitter"></i></a></li>
                            <li><a href="{{get_static_option('topbar_linkedin_url')}}"><i class="lab la-linkedin-in"></i></a></li>
                            <li><a href="{{get_static_option('topbar_facebook_url')}}"><i class="lab la-facebook-f"></i></a></li>
                            <li><a href="{{get_static_option('topbar_youtube_url')}}"><i class="lab la-youtube"></i></a></li>
                        </ul>
                    </div>

                    <div class="right-content">
                        @if(!empty(get_static_option('landlord_frontend_language_show_hide')))
                           <x-language-change/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
