<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    @php
                        if (auth('admin')->user()->image != null)
                        {
                            $image_id = auth('admin')->user()->image;
                        } else {
                            $image_id = get_static_option('placeholder_image');
                        }
                    @endphp

                    @if($image_id != null)
                        {!! render_image_markup_by_attachment_id($image_id,'','full',true) !!}
                    @else
                        <img src="{{asset('assets/landlord/uploads/media-uploader/no-image.jpg')}}" alt="">
                    @endif
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{auth('admin')->user()->name}}</span>
                    <span class="text-secondary text-small">{{auth('admin')->user()->username}}</span>
                </div>
                @if(auth('admin')->user()->email_verified === 1)
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                @endif
            </a>
        </li>
        {!! \App\Facades\LandlordAdminMenu::render_sidebar_menus() !!}
    </ul>
</nav>