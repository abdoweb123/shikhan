<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
"use strict";
var defaults = {
	"language": {
		"paginate": {
			"first": '<i class="la la-angle-double-left"></i>',
			"last": '<i class="la la-angle-double-right"></i>',
			"next": '<i class="la la-angle-right"></i>',
			"previous": '<i class="la la-angle-left"></i>'
		}
	}
};

if (KTUtil.isRTL()) {
	defaults = {
		"language": {
			"paginate": {
				"first": '<i class="la la-angle-double-right"></i>',
				"last": '<i class="la la-angle-double-left"></i>',
				"next": '<i class="la la-angle-left"></i>',
				"previous": '<i class="la la-angle-right"></i>'
			}
		}
	}
}

$.extend(true, $.fn.dataTable.defaults, defaults);

// fix dropdown overflow inside datatable
<<<<<<< HEAD
=======
=======
"use strict";
var defaults = {
	"language": {
		"paginate": {
			"first": '<i class="la la-angle-double-left"></i>',
			"last": '<i class="la la-angle-double-right"></i>',
			"next": '<i class="la la-angle-right"></i>',
			"previous": '<i class="la la-angle-left"></i>'
		}
	}
};

if (KTUtil.isRTL()) {
	defaults = {
		"language": {
			"paginate": {
				"first": '<i class="la la-angle-double-right"></i>',
				"last": '<i class="la la-angle-double-left"></i>',
				"next": '<i class="la la-angle-left"></i>',
				"previous": '<i class="la la-angle-right"></i>'
			}
		}
	}
}

$.extend(true, $.fn.dataTable.defaults, defaults);

// fix dropdown overflow inside datatable
>>>>>>> origin/Abdelrahman_shikhan-10
>>>>>>> b7cb4e1d80796a98313415ef7a5d8e797d4f6f04
KTApp.initAbsoluteDropdown('.dataTables_wrapper');