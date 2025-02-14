<script>
function getcourses(id, $params = []){
  var elm= $('#modal-body-div');
  $.ajax({
      url: "/ar/getreportcousres/"+id,
      type: "GET",
      dataType: "json",
      data : { 'params' : $params },
      success: function (data) {
           // console.log(data);
           elm.html('');
           $.each(data, function(index, value){
             course_link = window.location.origin + '/' + '{{ app()->getlocale() }}/' + data[index]['site_alias'] + '/' + data[index]['course_alias'];
             $(elm).append('<p style="direction: ltr;"> ' + course_link + '</p>');
           });
       },error:function(){
           console.log(data);
       }
   });
}
</script>
