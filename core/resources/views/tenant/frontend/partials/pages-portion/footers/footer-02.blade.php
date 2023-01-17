<footer class="footer-area style-03 section-bg-3">
    <div class="footer-top footer-middle-border padding-top-35 padding-bottom-90">
        <div class="container container-one">
            <div class="row justify-content-between">
                {!! render_frontend_sidebar('footer',['column' => true]) !!}
            </div>
        </div>
    </div>

    <div class="copyright-area copyright-bg">
        <div class="container container-one">
            <div class="row gy-4 justify-content-center theme-two-footer">
                {!! render_frontend_sidebar('footer_bottom_left',['column' => true]) !!}

                <div class="col-lg-4">
                    <div class="copyright-contents center-text">
                        {!! get_footer_copyright_text() !!}
                    </div>
                </div>

                {!! render_frontend_sidebar('footer_bottom_right',['column' => true]) !!}
            </div>
        </div>
    </div>
</footer>