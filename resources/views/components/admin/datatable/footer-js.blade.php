
<script src="{{ asset('assets/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('assets/datatable/dataTables.select.js') }}"></script>

<script>
$(document).ready( function () {
    var table = $('#kt_table_1').DataTable({
                    dom: 'fBptipr', // pBfrtip    Blfrtip
                    // buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print' ]
                    // 'ordering': false,
                    "pageLength": 100,


                    scrollX: true,
                    language: {
                      paginate: {
                        next: "التالى",
                        previous: "السابق"
                      }
                    },
                    columnDefs: [ { // scheckbox -----
                        orderable: false,
                        className: 'select-checkbox',
                        targets:   0
                    } ],
                    select: {
                        style:    'multi',
                        selector: 'td:first-child'
                    },

                    // order: [[ 1, 'asc' ]], // end check box ------
                    buttons: [
                      {extend:'pageLength'},
                       { extend: 'copy' },
                       { extend: 'excel' },
                       { extend: 'csv' },
                       { extend: 'print' },
                       { text: 'pdf' , action: function () {

                                           // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                                           data = document.getElementById("kt_table_1").innerHTML;
                                           // Done but error 414 request url is too larg solved by changing get to post

                                           $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
                                           // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                                           $.ajax({
                                           url: "/pdf",
                                           type: 'post',
                                           // dataType: "json",
                                           data: { 'data':data },
                                           xhrFields: { responseType: 'blob' },
                                           success: function(response, status, xhr) {
                                               // https://github.com/barryvdh/laravel-dompdf/issues/404

                                               // console.log(response);
                                               // var filename = "" ;
                                               // var disposition = xhr.getResponseHeader('Content-Disposition');
                                               // if (disposition) {
                                               //     var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                               //     var matches = filenameRegex.exec(disposition);
                                               //     if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                                               // }
                                               // var blob = new Blob([response], { type: 'application/octet-stream' });
                                               // var URL = window.URL || window.webkitURL;
                                               // var downloadUrl = URL.createObjectURL(blob);
                                               // var a = document.createElement("a");
                                               // a.href = downloadUrl;
                                               // // a.setAttribute('href', );
                                               // a.download = filename;
                                               // document.body.appendChild(a);
                                               // a.target = "_blank";
                                               // a.click();


                                               var filename = "";
                                               var disposition = xhr.getResponseHeader('Content-Disposition');

                                                if (disposition) {
                                                   var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                                   var matches = filenameRegex.exec(disposition);
                                                   if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                                               }
                                               var linkelem = document.createElement('a');
                                               try {
                                                   var blob = new Blob([response], { type: 'application/octet-stream' });

                                                   if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                                       //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                                       window.navigator.msSaveBlob(blob, filename);
                                                   } else {
                                                       var URL = window.URL || window.webkitURL;
                                                       var downloadUrl = URL.createObjectURL(blob);

                                                       if (filename) {
                                                           // use HTML5 a[download] attribute to specify filename
                                                           var a = document.createElement("a");

                                                           // safari doesn't support this yet
                                                           if (typeof a.download === 'undefined') {
                                                               window.location = downloadUrl;
                                                           } else {
                                                               a.href = downloadUrl;
                                                               a.download = filename;
                                                               document.body.appendChild(a);
                                                               a.target = "_blank";
                                                               a.click();
                                                           }
                                                       } else {
                                                           window.location = downloadUrl;
                                                       }
                                                   }

                                               } catch (ex) {
                                                   console.log(ex);
                                               }

                                           },error: function (xhr, status, error)
                                              { console.log(xhr.responseText); },
                                           });
                                      }
                       }
                   ]
                });


                // select all  -------------------------------------------------
                $("#select_all").on( "click", function(e) {
                    if ($(this).is( ":checked" )) {
                        table.rows().select();
                        $('#delete').removeClass('btn btn-outline-danger');
                        $('#delete').addClass('btn btn-danger btn-elevate');
                        $('#delete').text( deleteWord + ' : ' + table.rows('.selected').data().length );
                    } else {
                        table.rows().deselect();
                        $('#delete').removeClass('btn btn-danger btn-elevate');
                        $('#delete').addClass('btn btn-outline-danger');
                        $('#delete').text( deleteWord );
                    }
                });


                // select row  -------------------------------------------------
                deleteWord = "{{ __('words.delete') }}";
                $('#kt_table_1 tbody').on( 'click', 'tr', function () {
                    $(this).toggleClass('selected');

                    if (table.rows('.selected').data().length > 0 ) {
                        $('#delete').removeClass('btn btn-outline-danger');
                        $('#delete').addClass('btn btn-danger btn-elevate');
                        $('#delete').text( deleteWord + ' : ' + table.rows('.selected').data().length );
                    } else {
                      $('#delete').removeClass('btn btn-danger btn-elevate');
                      $('#delete').addClass('btn btn-outline-danger');
                      $('#delete').text( deleteWord );
                    }
                });


                // delete button -----------------------------------------------
              $( '#frm_delete' ).on('submit', function(e) {

                  e.preventDefault();

                  var dataList=[];
                  $("#kt_table_1 .selected").each(function(index) {
                      dataList.push($(this).find('td:first').attr('value'))
                  })

                  if(dataList.length == 0){
                    Swal.fire({
                        title: "{{ __('admin/dashboard.please_select_record') }}",
                        text: "{{ __('admin/dashboard.please_select_record') }}",
                        type:"info" ,
                        timer: 3000,
                        showConfirmButton: true,
                        confirmButtonText: '{{ __("admin/dashboard.ok") }}'
                    });
                    return;
                  };

                  var type = $(this).attr('method');
                  var url = $(this).attr('action');
                  var data = $(this).serialize();
                  data = data + '&' + 'ids=' + dataList;

                  Swal.fire({
                    title: '{{ __("messages.confirm_delete_title") }}', text: '{{ __("messages.confirm_delete_text") }}', type: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: '{{ __("messages.yes_delete") }}' , cancelButtonText: '{{ __("messages.cancel") }}'
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
                                {window.location.replace( '{{ route("index") }}' );}
                                Swal.fire("", "{{ __('messages.deleted_faild') }}", "error");
                                console.log(xhr.responseText);

                              }
                          });
                    }
                  })

              });
              //  --------------------------------------------------------------




});





</script>
