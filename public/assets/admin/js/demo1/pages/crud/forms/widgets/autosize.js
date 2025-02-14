<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
// Class definition

var KTAutosize = function () {
    
    // Private functions
    var demos = function () {
        // basic demo
        var demo1 = $('#kt_autosize_1');
        var demo2 = $('#kt_autosize_2');

        autosize(demo1);

        autosize(demo2);
        autosize.update(demo2);
    }

    return {
        // public functions
        init: function() {
            demos(); 
        }
    };
}();

jQuery(document).ready(function() {
    KTAutosize.init();
<<<<<<< HEAD
=======
=======
// Class definition

var KTAutosize = function () {
    
    // Private functions
    var demos = function () {
        // basic demo
        var demo1 = $('#kt_autosize_1');
        var demo2 = $('#kt_autosize_2');

        autosize(demo1);

        autosize(demo2);
        autosize.update(demo2);
    }

    return {
        // public functions
        init: function() {
            demos(); 
        }
    };
}();

jQuery(document).ready(function() {
    KTAutosize.init();
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
});