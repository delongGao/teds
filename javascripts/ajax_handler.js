var AjaxHandler = (function() {

    return {
        init: function(trigger, receiver, type) {
            var triggerDom = $(trigger);
            var receiverDom = $(receiver);
            var typeFlag = type;

//            alert("fired");
//            console.log(triggerDom);
            triggerDom.change(function() {
                var url = "/tedsrate/teds/ajax_service.php?trigger=" + triggerDom.val() + "&type=" + typeFlag;
                console.log(url);
                $.ajax({
                    url:url
                })
                .done(function(data) {
                        AjaxHandler.update(receiverDom,data, typeFlag);
                    })
                .fail(function() {
                        console.log("error happened");
                    })
//                AjaxHandler.update();
            })
        },
        update: function(receiverWrapper, data, type) {
            if (data) {
                data = jQuery.parseJSON(data);
//                console.log(data);
                // update selection
//                console.log(data.length > 0);
                receiverWrapper.html("<option value='' disabled selected>Select your option</option>");
                if (data.length > 0) {
                    $('#' + type.split("_")[0] + '_based_wrapper').slideDown(400);
                    $.each(data, function(k, v) {
                        receiverWrapper.append("<option value=" + v.id + ">" + v.Title + "</option>");
                    })
                } else {
                    var msg = "No " + type.split("_")[1] +" found for this option, please try with another.";
                    Notice.init(msg, "notice_warning");
                }
            } else {
                console.log("sth bad happend");
            }
        }
    }
}());