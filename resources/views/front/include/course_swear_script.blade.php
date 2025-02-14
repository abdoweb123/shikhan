<script>
var course_swear_text = document.getElementById('course_swear_text').innerHTML;

$( '.v_q_alert' ).click(function(e) {
     e.preventDefault();
     var url = $(this).attr('url');
     Swal.fire({
       title: '{{__("words.q_title")}}',
       icon: 'question',
       html: course_swear_text,
       className: "swear",

       confirmButtonText: '{{__("words.q_Yes")}}',
       cancelButtonText: '{{__("words.no")}}',
       showCancelButton: true,
       showCloseButton: true
       }).then((result) => {
         if (result.value) {
            window.location.href = url
         }
       })
});
</script>
