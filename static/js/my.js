$(document).ready(function(){
	
	$('.addnew-ticket .button1 button').click(function(){

		$('.addnew-ticket .button1 button').removeClass('current');
		$('.addnew-ticket .button1 button').removeAttr('name', 'type');
		$(this).addClass('current');
		$(this).attr('name', 'type');
	});

});
