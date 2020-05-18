$(document).ready( function () {
	$('#excel_download').DataTable( {
		dom: 'lfrBtip',
		buttons: [
		'excelHtml5'
		],
	} );
} );