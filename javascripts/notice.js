Notice = (function() {
    var noticeContainer = $("#noticeInfo");

    return {
        init: function(msg, type) {
            noticeContainer.removeClass().addClass(type); // type should be notice_success or notice_warning
            noticeContainer.empty().append("<p>" + msg + "</p>");
            Notice.fade();
            noticeContainer.click(function() {
                $(this).find("p").remove();
            })
        },
        fade: function() {
            setTimeout(function() { noticeContainer.find("p").animate(
                {
                    "opacity":0,
                    "zIndex":0
                }
                , function() { $(this).remove(); }); },2000
            );
        }
    }
})();