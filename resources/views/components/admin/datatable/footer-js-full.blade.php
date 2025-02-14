{{--
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-colvis-1.6.2/b-html5-1.6.2/b-print-1.6.2/cr-1.5.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.js"></script>
--}}

<script src="{{ asset('assets/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('assets/datatable/dataTables.select.js') }}"></script>



<script>
$(document).ready( function () {

  dt1_dom = 'fBptipr<"top"l>';
  dt1_scrollX = true;
  dt1_columnDefs_orderable = false;
  dt1_columnDefs_className = 'select-checkbox';
  dt1_columnDefs_targets = 0;
  dt1_select_style = 'multi';
  dt1_select_selector = 'td:first-child';
  dt1_order = [[ 1, 'asc' ]];
  dt1_colReorder = true;
  dt1_lengthMenu = [[100, 200, -1], [100, 200, "الكل"]];
  dt1_display_search_header = true;

  dt1_display_search_input_columns = dt1_display_search_input_columns_values;
  dt1_display_search_droplist_columns = dt1_display_search_droplist_columns_values;


  if (typeof dt1_dom_value !== 'undefined') { dt1_dom = dt1_dom_value; }
  if (typeof dt1_scrollX_value !== 'undefined') { dt1_scrollX = dt1_scrollX_value; }
  if (typeof dt1_columnDefs_orderable_value !== 'undefined') { dt1_columnDefs_orderable = dt1_columnDefs_orderable_value; }
  if (typeof dt1_columnDefs_className_value !== 'undefined') { dt1_columnDefs_className = dt1_columnDefs_className_value; }
  if (typeof dt1_columnDefs_targets_value !== 'undefined') { dt1_columnDefs_targets = dt1_columnDefs_targets_value; }
  if (typeof dt1_select_style_value !== 'undefined') { dt1_select_style = dt1_select_style_value; }
  if (typeof dt1_select_selector_value !== 'undefined') { dt1_select_selector = dt1_select_selector_value; }
  if (typeof dt1_order_value !== 'undefined') { dt1_order = dt1_order_value; }
  if (typeof dt1_colReorder_value !== 'undefined') { dt1_colReorder = dt1_colReorder_value; }
  if (typeof dt1_lengthMenu_value !== 'undefined') { dt1_lengthMenu = dt1_lengthMenu_value; }
  if (typeof dt1_display_search_header_value !== 'undefined') { dt1_display_search_header = dt1_display_search_header_value; }



    var table = $('#kt_table_1').DataTable({
                    dom: dt1_dom,  // 'fBptipr', pBfrtip    Blfrtip
                    // buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print' ]
                    // 'ordering': false,
                    scrollX: dt1_scrollX,
                    lengthMenu: dt1_lengthMenu,
                    language: {
                      search: "بحث",
                      zeroRecords : 'لا يوجد نتائج',
                      info: "عرض _START_ إلى _END_ من _TOTAL_ سجل",
                      infoEmpty: "عرض 0 إلى 0 من 0 سجل",
                      lengthMenu: "عرض _MENU_ سجل",
                      paginate: {
                        next: "التالى",
                        previous: "السابق"
                      }
                    },
                    colReorder: dt1_colReorder,
                    columnDefs: [ { // scheckbox -----
                        orderable: dt1_columnDefs_orderable,
                        className: dt1_columnDefs_className,
                        targets: dt1_columnDefs_targets
                    } ],
                    select: {
                        style: dt1_select_style,
                        selector: dt1_select_selector
                    },
                    order: dt1_order, // end check box ------
                    initComplete: function () {

                              if (dt1_display_search_header === true) {

                                       // 1- text box search
                                       this.api().columns( dt1_display_search_input_columns_values ).every( function () {
                                               var that = this;

                                               var input = $('<input type="text" placeholder=" ' + this.header().textContent + '" />')
                                                   .appendTo( $(that.header()).empty() );

                                               $( 'input', this.header() ).on( 'keyup change clear', function () {
                                                   if ( that.search() !== this.value ) {
                                                       that
                                                           .search( this.value )
                                                           .draw();
                                                   }
                                               });
                                       });

                                       // 2- dropdown list
                                       this.api().columns( dt1_display_search_droplist_columns ).every( function () {
                                              var column = this;
                                              var select = $('<select><option value="">' + this.header().textContent + '</option></select>')
                                                  .appendTo( $(column.header()).empty() )
                                                  .on( 'change', function () {
                                                      var val = $.fn.dataTable.util.escapeRegex(
                                                          $(this).val()
                                                      );

                                                      column
                                                          .search( val ? '^'+val+'$' : '', true, false )
                                                          .draw();
                                                  } );

                                              column.data().unique().sort().each( function ( d, j ) {
                                                  select.append( '<option value="' + d + '">' + d.substr(0,15) + '</option>' )
                                              } );
                                      });

                             }
                   },
                   buttons: [
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
                deleteWord = "Delete";
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
                    return;
                  }

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
                                {window.location.replace( '/' );}
                                Swal.fire("", "{{ __('messages.deleted_faild') }}", "error");
                                console.log(xhr.responseText);

                              }
                          });
                    }
                  })

              });
              //  --------------------------------------------------------------



              // $('#kt_table_1 tbody').on( 'mouseover', 'tr', function () {
              //
              //   console.log($(this).find(':last-child'));
              //
              // });




});





</script>
