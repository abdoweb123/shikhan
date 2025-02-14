<script src="{{asset('assets/admin/vendors/dropify/dist/js/dropify.min.js')}}"></script>
<script>
    $('.dropify').dropify({
      messages: {
          'default': 'اسحب الملفات هنا او اضغط للتحميل',
          'replace': 'اسحب الملفات هنا او اضغط للتحميل',
          'remove':  'حذف',
          'error':   'عفوا حدث خطأ'
      }
    });


			// Used events
			var drEvent = $('.dropify').dropify();

			drEvent.on('dropify.beforeClear', function(event, element) {
					resp = confirm( "{{ __('words.confirm_delete_image') }} : " + element.file.name + " ?");
          if (resp){
            id = event.target.id;
            console.log(id);
            document.getElementById(id + '_remove').checked = true;
            return true;
          }
          return false;
			});

			// drEvent.on('dropify.afterClear', function(event, element) {
			// 		alert('File deleted');
			// });

			drEvent.on('dropify.errors', function(event, element) {
					console.log('Has Errors');
			});

</script>
