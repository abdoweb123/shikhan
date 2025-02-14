<script>
// delete inline button -----------------------------------------------
function deleteInLine(e,me) {

  e.preventDefault();

  var type = $(me).attr('method');
  var url = $(me).attr('action');
  var data = $(me).serialize();

  Swal.fire({
    title: '{{ __("messages.confirm_delete_title") }}', text: '{{ __("messages.confirm_delete_text") }}', type: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: '{{ __("messages.yes_delete") }}' , cancelButtonText: '{{ __("messages.cancel") }}'
  }).then((result) => {
    if (result.value) {
              $.ajax({
              url : url ,
              type : type ,
              data : data ,
              dataType:"JSON",
              success: function (data) {
                  // console.log(data);
                  // return;

                  if(data['success']) {
                    location.reload();
                  }

                  if(data['error']) {
                      Swal.fire("{{trans('messages.deleted_faild')}}", data['error'], "error");
                  }
              },
              fail: function(xhrerrorThrown){
                  Swal.fire("", "{{ __('messages.deleted_faild') }}", "error");
              },
              error: function (xhr, status, error) {
                if (xhr.status == 419) // httpexeption login expired or user loged out from another tab
                {window.location.replace( '{{ route("index") }}' );}
                Swal.fire("", "{{ __('messages.deleted_faild') }}", "error");
                console.log(xhr.responseText);
              }
          });
    }
  })

};
</script>
//  --------------------------------------------------------------
