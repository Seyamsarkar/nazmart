<footer class="footer-area section-bg-1">
    <div class="footer-top footer-middle-border padding-top-35 padding-bottom-70">
        <div class="container">
            <div class="row justify-content-between">
                {!! render_frontend_sidebar('footer',['column' => true]) !!}
            </div>
        </div>
    </div>
    <div class="copyright-area copyright-bg">
        <div class="container container-three">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="copyright-contents center-text">
                        <span> {!! get_footer_copyright_text(\App\Facades\GlobalLanguage::default_slug()) !!} </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="back-to-top">
    <span class="back-top"> <i class="las la-angle-up"></i> </span>
</div>

<script src="{{asset('assets/landlord/frontend/js/jquery-3.6.1.min.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/jquery-migrate-3.4.0.min.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/jquery.lazy.min.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/landlord/common/js/axios.min.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/slick.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/odometer.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/wow.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/viewport.jquery.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/jquery.syotimer.min.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/jquery.nice-select.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/jquery.nicescroll.min.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/nouislider-8.5.1.min.js')}}"></script>
<script src="{{asset('assets/landlord/frontend/js/main.js')}}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<x-custom-js.lang-change-landlord/>
<x-custom-js.landlord-newsletter-store/>

<script>
    $(function () {
        var ENDPOINT = window.location.href;
        var posts = 6;

        $(document).on('click', '#load_more', function (e) {
            e.preventDefault();
            var el = $(this);
            var category = el.data('category');
            var order = el.data('order');
            var order_by = el.data('order_by');

            posts += 6;
            LoadMore(posts, category, order, order_by);
        });

        function LoadMore(posts, category, order, order_by) {
            $.ajax({
                url: '{{route('landlord.frontend.blog.load_more.ajax')}}',
                type: "get",
                data: {
                    'posts': posts,
                    'category': category,
                    'order': order,
                    'order_by': order_by
                },
                beforeSend: function () {
                    $('#load_more').text('Loading..');
                },
                success: function (response) {
                    if (response != '')
                    {
                        let load_more_items = $("#load-more-items");
                        load_more_items.css('display', 'none');
                        load_more_items.fadeIn(1000);
                        load_more_items.append(response);

                        $('#load_more').text('Load More');
                    } else {
                        $('#load_more').text('No More Blog Available');
                    }
                },
                error: function (jqXHR, ajaxOptions, thrownError) {

                }
            });
        }
    });
</script>


    @yield('scripts')

    @php
        $dynamic_style = 'assets/landlord/frontend/js/dynamic-script.js';
    @endphp
    @if(file_exists($dynamic_style))
        <script src="{{asset($dynamic_style)}}"></script>
    @endif
</body>
</html>
