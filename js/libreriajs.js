// JavaScript Document
	function validarKey(key){
		var nopermitida = [38,64]
		//Tecla presionada
		press = key.which;
		if ((press != 8) && ($.inArray(press, nopermitida) != -1)){
			key.preventDefault();
		}
	}

	// Function Show and hide buttons report
	function getbuttonsList(niv){
		var buttons = "";
		if (niv < 3){
			var buttons = [
				{ 
					extend: 'copy',
					title: 'Reporte de Tipificaciones',
					text: 'Copiar',
					exportOptions: {
					  columns: [ 0,1,2,3,4,5,6,7,8]
					}
				},
				{
					extend: 'excel',
					title: 'Reporte de Tipificaciones',
					exportOptions: {
					  columns: [ 0,1,2,3,4,5,6,7,8]
				}
			}];			
		}else{
			var buttons = [];
		}
		return buttons;
	}

	function getbuttonsIncidents(niv){
		var buttons = "";
		if (niv < 3){
			var buttons = [
				{ 
					extend: 'copy',
					title: 'Reporte de Tipificaciones',
					text: 'Copiar',
					exportOptions: {
					  columns: [ 0,1,2,3,4,5,6,7]
					}
				},
				{
					extend: 'excel',
					title: 'Reporte de Tipificaciones',
					exportOptions: {
					  columns: [ 0,1,2,3,4,5,6,7]
				}
			}];			
		}else{
			var buttons = [];
		}
		return buttons;
	}

	$(function() {
		function reposition() {
			var modal = $(this),
				dialog = modal.find('.modal-dialog');
			modal.css('display', 'block');
			// Dividing by two centers the modal exactly, but dividing by three
			// or four works better for larger screens.
			dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 3));
		}

		// Reposition when a modal is shown
		$('.modal').on('show.bs.modal', reposition);
		// Reposition when the window is resized
		$(window).on('resize', function() {
			$('.modal:visible').each(reposition);
		});

	});	

$(document).ready(function(){
//Borrar clases al enfocar elemento
	$('body').on('click', '.form-control', function(){
		$(this).parent().removeClass('has-error has-success');
	});

//Inicializar los tooltips
	$('[data-toggle="tooltip"]').tooltip();

});	