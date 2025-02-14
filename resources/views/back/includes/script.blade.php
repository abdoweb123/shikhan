<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/moment.js') }}"></script>
<script src="{{ asset('assets/admin/js/mustache.min.js') }}"></script>
{{--<script src="http://malsup.github.com/jquery.form.js"></script>--}}
<!-- Bootstrap -->
<script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('assets/admin/js/fastclick.js') }}"></script>
<!-- NProgress -->
<script src="{{ asset('assets/admin/js/nprogress.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('assets/admin/js/icheck.min.js') }}"></script>
<!-- Datatables -->
<script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/responsive.bootstrap.js') }}"></script>
<script src="{{ asset('assets/admin/js/datatables.scroller.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/jszip.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vfs_fonts.js') }}"></script>

<!-- Custom Theme Scripts -->
<script src="{{ asset('assets/admin/js/custom.min.js') }}"></script>

<script src="{{ asset('assets/js_Admin/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('assets/js_Admin/jquery.lazyload.min.js') }}"></script>
<script src="{{ asset('assets/js_Admin/jquery.bxslider.min.js') }}"></script>
<script src="{{ asset('assets/js_Admin/JsAdmin.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/general/jquery.repeater/src/lib.js') }}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/demo1/pages/components/extended/toastr.js')}}" type="text/javascript"></script>

	<script type="text/javascript">
			function gat_data(data_div,err_div,type,url,data,special=null)
			{
				// $('#'+err_div).html(loader());

				$.ajax({
					 type: type,
					 url: url,
					 data: data,
					 success: function (data) {
							 // console.log(data);
							 // return;

							if (data['status']=='ma')
							{
								jQuery('#popup_div').modal();
								// $('#popup_div').modal();// because my be this happend in button click not in link so the button doesnt join to a modal
								$('#details').html(data['ma_html']);
								return;
							}


								msg = data['msg'];
								if (msg)
								{
									if (data['alert'] == 'swal') {
										Swal.fire({
												title: msg,
												text: msg,
												type: data['status'] ,
												timer: 2000,
												showConfirmButton: false
										});
									}
								}


						if (data['html'])
						{ $('#'+data_div).html(data['html']); }

						if (data['link'])
						{window.location.href = '/' + data['link'];} // {{ Request()->lang }}

						if (data['linkOut'])
						{window.location.href = data['linkOut'];}

						if (data['redir'])
						{location.reload();}

					 },
					 error: function (xhr, status, error)
					 {
						 // if (xhr.status == 419) // httpexeption login expired or user loged out from another tab
						 if (xhr.status == 401) // if user deatvate him self so we must ho to admin home page
						 {window.location.replace( '{{ route("dashboard.index") }}' );}
						 console.log(xhr.responseText);

					 },
				 });
			}


			function ajaxForm(e,me,data_div,err_div,data)
			{
				e.preventDefault();
				var type=$(me).attr('method');
				var url=$(me).attr('action');
				var data=$(me).serialize();
				gat_data(data_div,err_div,type,url,data);
			}

			function ajaxlink(e,me,data_div,err_div,data)
			{
				e.preventDefault();
				var type='get';
				var url=$(me).attr("data-href");
				var data=data;
				gat_data(data_div,err_div,type,url,data);
			}


		{{--	$(document).ready(function()
		  {
		        $(document).on('click', '.pagination a',function(event)
		        {
		            event.preventDefault();
		            $('li').removeClass('active');
		            $(this).parent('li').addClass('active');
		            var url = $(this).attr('href');
								if (url == '#') { // becuase laravel pagination and datatable pagination has the same structure so this event happend in click on pagination in datatable and laravel
									return;
								}
		            // var page = $(this).attr('href').split('page=')[1];
								gat_data('dt','err_dt','get', url ,'');
	        });
	    });--}}




			function changeLessonType(me,div)
			{
					var div_grammer_contents = document.getElementById('div_grammer_contents');
					div_grammer_contents.style.visibility = "hidden";

					var div_training_contents = document.getElementById('div_training_contents');
					div_training_contents.style.visibility = "hidden";


					if ($(me).val() == 2 || $(me).val() == 3) {
						div_grammer_contents.style.visibility = "visible";
					}

					if ($(me).val() == 4) {
						div_training_contents.style.visibility = "visible";
					}

			}



		</script>


{!! @$script !!}
