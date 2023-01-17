(function($) {
    "use strict";

    $(document).ready(function() {

        $(document).on('click', '.close-bars, .body-overlay', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $(document).on('click', '.sidebar-icon', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });

    });

    /*-----------------
        Nice Select
    ------------------*/

    $('.nice_select_js').niceSelect();


})(jQuery);