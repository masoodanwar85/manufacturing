// let copyButtonTrans = 'Copy';
// let excelButtonTrans = 'Excel';
let pdfButtonTrans = 'PDF';
let printButtonTrans = 'Print';

$.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' });
$.extend(true, $.fn.dataTable.defaults, {
  dom: 'lBfrtip<"actions">',
  buttons: [
	//   {
	//   extend: 'copy',
	//   className: 'btn-default',
	//   text: copyButtonTrans,
	//   exportOptions: {
	// 	  columns: ':visible'
	//   }
  // },{
	//   extend: 'excel',
	//   className: 'btn-default',
	//   text: excelButtonTrans,
	//   exportOptions: {
	// 	  columns: ':visible'
	//   }
  // },
  {
	  extend: 'pdf',
	  className: 'btn-primary',
	  text: pdfButtonTrans,
	  exportOptions: {
		  columns: ':visible'
	  }
  },{
	  extend: 'print',
	  className: 'btn-default',
	  text: printButtonTrans,
	  exportOptions: {
		  columns: ':visible'
	  }
  }]
});

$.fn.dataTable.ext.classes.sPageButton = '';
let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
