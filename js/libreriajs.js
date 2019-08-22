// JavaScript Document
	function validarKey(key){
		var nopermitida = [38,64]
		//Tecla presionada
		press = key.which;	
		if ((press != 8) && ($.inArray(press, nopermitida) != -1)){
			key.preventDefault();				
		}
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
	jQuery(function($){
		$.datepicker.regional['es'] = {
			changeMonth: true,
			changeYear: true,		
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
			dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
			dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;']};
		$.datepicker.setDefaults($.datepicker.regional['es']);
	});

//Borrar clases al enfocar elemento
	$('body').on('click', '.form-control', function(){
		$(this).parent().removeClass('has-error has-success');
	});	

//Inicializar los tooltips
	$('[data-toggle="tooltip"]').tooltip();
});	