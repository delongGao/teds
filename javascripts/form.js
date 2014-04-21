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