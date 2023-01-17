(function ($){
    "use strict";

    $(document).ready(function(){

        $(document).on('click','.nav-ul .nav-items', function (){
            $(this).siblings().removeClass('active');
        })

        //form-group with-icon password-group

        /* password show/hide */
        $(document).on('click','.password-group .icon',function (){
            let el = $(this);
            let input =  el.parent().find('input');
            let type = input.attr('type');
            if (type === 'password'){
                input.attr('type','text')
                return;
            }
            input.attr('type','password')
        });


        /* initialize owl caoursel*/
        var t = $("html").attr("dir"), e = void 0 !== t && "ltr" !== t;

        var o = $(".global-carousel-init");
        o.length > 0 && $.each(o, function () {
            var t = $(this), n = t.children("div"), i = !!t.data("loop") && t.data("loop"),
                o = !!t.data("center") && t.data("center"), d = t.data("desktopitem") ? t.data("desktopitem") : 1,
                s = t.data("mobileitem") ? t.data("mobileitem") : 1,
                l = t.data("tabletitem") ? t.data("tabletitem") : 1, c = !!t.data("nav") && t.data("nav"),
                r = !!t.data("dots") && t.data("dots"), u = !!t.data("autoplay") && t.data("autoplay"),
                m = t.data("navcontainer") ? t.data("navcontainer") : "",
                v = t.data("stagepadding") ? t.data("stagepadding") : 0, p = t.data("margin") ? t.data("margin") : 0;
            n.length < 2 || t.owlCarousel({
                loop: i,
                autoplay: u,
                autoPlayTimeout: 5000,
                smartSpeed: 2000,
                margin: p,
                dots: r,
                center: o,
                nav: c,
                rtl: e,
                navContainer: m,
                stagePadding: v,
                navText: ['<i class="las la-angle-left"></i>', '<i class="las la-angle-right"></i>'],
                responsive: {
                    0: {items: 1, nav: !1, stagePadding: 0},
                    460: {items: s, nav: !1, stagePadding: 0},
                    599: {items: s, nav: !1, stagePadding: 0},
                    768: {items: l, nav: !1, stagePadding: 0},
                    960: {items: l, nav: !1, stagePadding: 0},
                    1200: {items: d},
                    1920: {items: d}
                }
            })
        });

    }); // document ready


})(jQuery);
