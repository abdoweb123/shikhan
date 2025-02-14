<script>

// as function ok
// function confirmDelete(e, me)
// {
//
//     e.preventDefault();
//
//     Swal.fire({
//       title: '{{ __("messages.confirm_delete_title") }}',
//       text: '{{ __("messages.confirm_delete_text") }}',
//       showCancelButton: true,
//       confirmButtonColor: '#3085d6',
//       cancelButtonColor: '#d33',
//       confirmButtonText: '{{ __("messages.yes_delete") }}' ,
//       cancelButtonText: '{{ __("messages.cancel") }}'
//     }).then((result) => {
//       if (result.value) {
//           $(me).closest('form').submit();
//       }
//     })
//
// };

// get by class
$( '.confirm-delete' ).on('click', function(e){
  e.preventDefault();

  Swal.fire({
    title: '{{ __("messages.confirm_delete_title") }}',
    text: '{{ __("messages.confirm_delete_text") }}',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: '{{ __("messages.yes_delete") }}' ,
    cancelButtonText: '{{ __("messages.cancel") }}'
  }).then((result) => {
    if (result.value) {
        $(this).closest('form').submit();
    }
  })
});



function confirmDeleteAjax(e)
{

  e.preventDefault();

  var type = $(this).attr('method');
  var url = $(this).attr('action');
  var data = $(this).serialize();
  data = data + '&' + 'ids=' + dataList;

  Swal.fire({
    title: '{{ __("messages.confirm_delete_title") }}',
    text: '{{ __("messages.confirm_delete_text") }}',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: '{{ __("messages.yes_delete") }}' ,
    cancelButtonText: '{{ __("messages.cancel") }}'
  }).then((result) => {
    if (result.value) {
              $.ajax({
              url : url ,
              type : type ,
              data : data , // {'ids':dataList},
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
              error: function (xhr, status, error)
              {
                if (xhr.status == 419) // httpexeption login expired or user loged out from another tab
                {
                  window.location.replace( '{{ route("dashboard.index") }}' );
                }
                Swal.fire("", "{{ __('messages.deleted_faild') }}", "error");
                console.log(xhr.responseText);

              }
          });
    }
  })

};


</script>
