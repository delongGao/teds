$(function() {
    // handle click ajax for sending email - ugly

    $('.email_sender').click(function() {
        var urpID = $(this).attr("data-urpid");
        var email = $(this).attr("data-email");
//        var rating_info = $(this).parent("td").siblings("td").text();

        var url = "/teds/ajax_service.php?" + "email=" + email + "&urpID=" + urpID;

//        $('#emailModal div.rating_info_check')
//            .html("")
//            .append(rating_info);
        $('#emailModal div.email_check')
            .html("")
            .append(email);
        $('#emailModal #email_sender_confirm').attr("data-url", url);
        $('#emailModal').modal();
    })

    $('#email_sender_confirm').click(function() {
        var url = $(this).attr("data-url");
        console.log(url);
        $.ajax({
            url:url
        })
        .done(function(data) {
//            console.log("server succeeded");
//            console.log(data[1]);
            data = jQuery.parseJSON(data);
            if (data && data[1]) {
                Notice.init(data[1], data[0] ? "notice_success" : "notice_warning");
            } else {
                Notice.init("Server error! please try again!", "notice_warning");
            }
            $('#emailModal #email_sender_confirm').attr("data-url", null);
        })
        .fail(function() {
            // do sth with error
            console.log("server failed");
            Notice.init("Cannot connect to server, please try again later!", "notice_warning");
        });
    })
});

