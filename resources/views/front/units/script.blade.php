<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/toastr.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('assets/js/wow.min.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('assets/js/rangeslider.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-material-design.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-datetimepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/nouislider.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/material-kit.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/index.js?'.date('YmdHis')) }}"></script>

{!! @$script !!}

<script type="text/javascript">
    function lessonDone(course_id,post_id) {
        $.ajax({
            type:'get',
            url:'{{ route("member_lesson_seen") }}',
            data:{ 'course_id': course_id , 'post_id': post_id },
                success:function(data) {
                   // console.log(data);
                   location.reload();
                },
                error: function (xhr) {
                   console.log(xhr);
                },
            });
        }
</script>
