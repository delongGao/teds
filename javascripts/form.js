var Form = (function() {


    return {
        init: function() {
            Form.validate();
        },

        validate: function() {
            $('form').submit(function(e) {
                console.log("function called");
//            $('form').find("input[type=submit]").click(function(evt) {
                var flag = true;
                for (var i = 0; i < $('.notEmpty').length; i++) {
                    if (('.notEmpty')[i].value == "") {
                        flag = false;
                    }
                }
                if (!flag) {
                    return false;
                }
//                e.preventDefault();
//                    window.history.back();

            })
        },

        password_behavior: function() {
            var pswdField = $('#password_field');
            var pswdVerify = $('#password_verify');

            pswdVerify.keyup(function(event) {
                var char = String.fromCharCode(event.charCode);

                var pw_val = pswdField.val();
                var pw_val_mirror = pswdVerify.val();
                $("#pw_very_show").html()
                $("#pw_very_show").removeClass();
                if (pw_val == pw_val_mirror) {
                    $('#pw_very_show').text("password matches!").addClass("pw_correct");
                } else {
                    $('#pw_very_show').text("password not match!").addClass("pw_wrong");
                }
            });
        }
    }
})();
//
//var Formm = {
//    init: function() {
//        Formm.validate();
//    },
//
//    validate: function() {
//        $('form').submit(function(evt) {
//            var flag = true;
//            for (var i = 0; i < $('.notEmpty').length; i++) {
//                if (('.notEmpty')[i].val() == "") {
//                    flag = false;
//                }
//            }
//            if (!flag) {
//                evt.preventDefault();
////                    window.history.back();
//                Notice.init("Invalid input found, please try again!");
//            }
//        })
//    }
//}