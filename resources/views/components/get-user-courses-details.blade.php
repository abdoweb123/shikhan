<script>
function getUserDetails( $params = [] ) {

  // console.log($params['userId']);

  var elm= $('#modal-body-div');
  $.ajax({
      url: "{{ route('dashboard.user.details') }}",
      type: "post",
      dataType: "json",
      data : { '_token': '{{ csrf_token() }}', 'params': $params },
      success: function (data) {
           // console.log(data['data']);
           elm.html(data['data']);
       },error:function(data){
           console.log(data.responseJSON);
       }
   });
}
</script>
