$(function() {
//    var root_url = "http://localhost:90"; -- local dev
    var root_url = "http://depts.washington.edu/tedsrate"; // -- remote env

    $('.email_sender').click(function() {
        // handle click ajax for sending email - ugly
        var urpID = $(this).attr("data-urpid");
        var email = $(this).attr("data-email");
        //        var rating_info = $(this).parent("td").siblings("td").text();
        var url = root_url + "/teds/ajax_service.php?" + "email=" + email + "&urpID=" + urpID + "&sendEmail=true";

//        $('#emailModal div.rating_info_check')
//            .html("")
//            .append(rating_info);
        var res = email.match(/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/g);
        if (res) {
            $('#emailModal div.email_check')
                .html("")
                .append(email);
            $('#emailModal #email_sender_confirm').attr("data-url", url);
            $('#emailModal').modal();
        } else {
            Notice.init("The email address provided is invalid!", "notice_warning");
        }
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
                copyToClipboard(data[2]);
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

    // handle logout
    $('#logout').click(function() {
        var c = confirm("Please confirm: log out?");
        if (c) {
            $('#logout_form').submit();
//            $.post( "adminproc.php", { source: "logout" } )
//                .done(function() {location.reload();});
//            console.log("log me out");
        }
    })

    // handle clipboard
//    $('.url_copy').click(function() {
//        var email = $(this).attr("data-email");
//        var urpID = $(this).attr("data-urpid");
//        var url = root_url + "/teds/ajax_service.php?" + "email=" + email + "&urpID=" + urpID + "&sendEmail=false";
//        // handle clipboard
////        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
//        // var re = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
//        var res = email.match(/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/g);
//        if (res) {
//            $.ajax({
//                url:url
//            })
//            .done(function(data) {
//                data = jQuery.parseJSON(data);
//                if (data && data[1]) {
//                    copyToClipboard(data[2]);
//                }
//            })
//            .fail(function() {
//                // do sth with error
//                console.log("server failed");
//                Notice.init("Cannot connect to server, please try again later!", "notice_warning");
//            });
//        } else {
//            Notice.init("The email address provided is invalid!", "notice_warning");
//        }
//    })

    // handle table row hover
//    $('.table-hover tr').slice(1).mouseenter(function() {
//        var controlGroup = $("<span class='row-control' style='display:none;'><i class='fa fa-pencil-square-o row-edit'></i> <i class='fa fa-times-circle row-delete'></i></span>");
//        $(this).append(controlGroup);
//        controlGroup.show(500);
//
//        // click event handler for icons
//        $('.row-edit').click(function() {
//
//            // console.log($(this).parents("tr").attr("id"));
//        })
//        $('.row-delete').click(function() {
//            var c = confirm("Please confirm: delete this record?");
//            if (c) {
//                var url = "/teds/row_edit_delete.php?targetid=" + $(this).parents("tr").attr("id");
//                $.ajax(url)
//                    .done(function() {
//                        Notice.init("Record successfully deleted!", "notice_success");
//                    })
//                    .fail(function() {
//                        Notice.init("Server error!", "notice_warning");
//                    })
//            } else {
//
//            }
//            // console.log($(this).parents("tr").attr("id"));
//        })
//    })
//    $('.table-hover tr').slice(1).mouseleave(function() {
//        $('.row-control').hide(500);
//        $('.row-control').remove();
//    })
});

function copyToClipboard(text) {
    window.prompt("Copy to clipboard: Ctrl/Command+C, Enter", text);
}
