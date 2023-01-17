<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="{{tenant_url_with_protocol(tenant()?->domain?->domain)}}" class="nav-link" target="_blank">
                <div class="nav-profile-image">

                    @if(auth('admin')->user()->image != null)
                        {!! render_image_markup_by_attachment_id(optional(auth('admin')->user())->image,'','full',true) !!}
                    @else
                        <img src="{{global_asset('assets/landlord/uploads/media-uploader/no-image.jpg')}}" alt="">
                    @endif

                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{optional(auth('admin')->user())->name}}</span>
                    <span class="text-secondary text-small">{{optional(auth('admin')->user())->username}}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        {!! \App\Facades\LandlordAdminMenu::render_tenant_sidebar_menus() !!}
    </ul>
</nav>
