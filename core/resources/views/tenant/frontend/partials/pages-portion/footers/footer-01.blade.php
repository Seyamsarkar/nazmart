<footer class="footer-area style-02 footer-color-two">
    <div class="footer-top footer-middle-border padding-top-35 padding-bottom-90">
        <div class="container container-one">
            <div class="row theme-one-footer justify-content-between">
                {!! render_frontend_sidebar('footer',['column' => true]) !!}
            </div>
        </div>
    </div>
    <div class="footer-bottom pb-4 section-bg-2">
        <div class="container container-one">
            <div class="row align-items-center">
                <div class="col-lg-6 mt-4">
                    {!! render_frontend_sidebar('footer_bottom_left',['column' => false]) !!}
                </div>

                <div class="col-lg-6 mt-4">
                    {!! render_frontend_sidebar('footer_bottom_right',['column' => false]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area copyright-bg">
        <div class="container container-three">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="copyright-contents center-text">
                        <span>
                            {!! get_footer_copyright_text() !!}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
