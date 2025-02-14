<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
$(document).ready(function(){

    $(".sk-cube-grid").fadeOut(2000,function(){

        $("#loading").fadeOut(2000 , function(){

            $("body").css("overflow","auto")

        })
    })
})
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

toastr.options = {
    "closeButton": true, // true/false
    "debug": false, // true/false
    "newestOnTop": false, // true/false
    "progressBar": false, // true/false
    // "positionClass": "md-toast-top-right", // md-toast-top-right / md-toast-top-left / md-toast-bottom-right / md-toast-bottom-left
    "preventDuplicates": false, // true/false
    "onclick": null,
    "showDuration": "300", // in milliseconds
    "hideDuration": "1000", // in milliseconds
    "timeOut": "5000", // in milliseconds
    "extendedTimeOut": "1000", // in milliseconds
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

var $element = $('input[type="range"]');

$element
  .rangeslider({
    polyfill: false,
    onInit: function() {
      var $handle = $('.rangeslider__handle', this.$range);
      updateHandle($handle[0], this.value);
    }
  })
  .on('input', function(e) {
    var $handle = $('.rangeslider__handle', e.target.nextSibling);
    updateHandle($handle[0], this.value);
  });

function updateHandle(el, val) {
  el.textContent = val;
}
<<<<<<< HEAD
=======
=======
$(document).ready(function(){

    $(".sk-cube-grid").fadeOut(2000,function(){

        $("#loading").fadeOut(2000 , function(){

            $("body").css("overflow","auto")

        })
    })
})
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

toastr.options = {
    "closeButton": true, // true/false
    "debug": false, // true/false
    "newestOnTop": false, // true/false
    "progressBar": false, // true/false
    // "positionClass": "md-toast-top-right", // md-toast-top-right / md-toast-top-left / md-toast-bottom-right / md-toast-bottom-left
    "preventDuplicates": false, // true/false
    "onclick": null,
    "showDuration": "300", // in milliseconds
    "hideDuration": "1000", // in milliseconds
    "timeOut": "5000", // in milliseconds
    "extendedTimeOut": "1000", // in milliseconds
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

var $element = $('input[type="range"]');

$element
  .rangeslider({
    polyfill: false,
    onInit: function() {
      var $handle = $('.rangeslider__handle', this.$range);
      updateHandle($handle[0], this.value);
    }
  })
  .on('input', function(e) {
    var $handle = $('.rangeslider__handle', e.target.nextSibling);
    updateHandle($handle[0], this.value);
  });

function updateHandle(el, val) {
  el.textContent = val;
}
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
