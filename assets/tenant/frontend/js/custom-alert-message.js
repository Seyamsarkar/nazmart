class customMessage {
    constructor() {
        clearTimeout(window.timeoutId)
        undoMessage();
    } // Not Working

    static success(text, optionalText = '', optionalLink = '', time) {
        let main_wrap = this.common(text, time);
        main_wrap.css({'backgroundColor': '#20bf6b', 'boxShadow': '0px 0px 15px rgb(32 191 107 / 59%)'});

        main_wrap.find('.custom_alert_banner__url').remove();
        if (optionalText !== '' && optionalLink !== '') {
            let url_div = '<a href="' + optionalLink + '" class="custom_alert_banner__url">' + optionalText + '</a>';
            $(url_div).insertAfter('.custom_alert_banner__text');
        }
    }

    static error(text, time) {
        let main_wrap = this.common(text, time);
        main_wrap.find('.custom_alert_banner__url').remove();
        main_wrap.css({'backgroundColor': '#eb3b5a', 'boxShadow': '0px 0px 15px rgb(235 59 90 / 59%)'});
    }

    static warning(text, time) {
        let main_wrap = this.common(text, time);
        main_wrap.find('.custom_alert_banner__url').remove();
        main_wrap.css({'backgroundColor': '#F79F1F', 'boxShadow': '0px 0px 15px rgb(247 159 31 / 59%)'})
    }

    static common(text, time = 5000) {
        let bannerWrapper = $(".custom_alert_banner__content");
        bannerWrapper.find('.custom_alert_banner__text').text(text)
        bannerWrapper.addClass('active');

        window.timeoutId = setTimeout(() => {
            undoMessage()
        }, 50000000);

        return bannerWrapper;
    }
}

$('.custom_alert_banner__close').on('click', function () {
    undoMessage()
});

function undoMessage() {
    let bannerWrapper = $(".custom_alert_banner__content");
    bannerWrapper.removeClass('active');
    bannerWrapper.find('.custom_alert_banner__url').remove();
}