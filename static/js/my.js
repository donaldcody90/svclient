$(document).ready(function () {
   var location = window.location;
   var found = false;
   $(".main-menu ul li p").each(function(){
      var pclass = $(this).attr('class');
      if(pclass=='menu2'){
         $(this).parent().addClass("selected");
         found = true;
      }
   });
   if(!found){
      $(".main-menu a li:first").addClass("selected");
   }
});

$(document).ready(function () {
   var location = window.location;
   var found = false;
   $(".billing_type a").each(function(){
      var href = $(this).attr("href");
      if(location==href){
         $(this).parent().addClass("selected");
		 found = true;
      }
   });
   if(!found){
      $(".billing_type li:first").addClass("selected");
   }
});

$(document).ready(function () {
   var location = window.location;
   var found = false;
   $(".nav a").each(function(){
      var href = $(this).attr("href");
      if(href==location){
         $(this).parent().addClass("selected");
         found = true;
      }
   });
   if(!found){
      $(".nav li:first").addClass("selected");
   }
});

$(document).ready(function () {
   var location = window.location;
   var found = false;
   $(".vps_modification a").each(function(){
      var href = $(this).attr("href");
      if(href==location){
         $(this).parent().addClass("selected");
         found = true;
      }
   });
   if(!found){
      $(".vps_modification li:first").addClass("selected");
   }
});

$(document).ready(function(){
	$(".addnew-ticket .button1 label input:radio[name=ticket-type]:first").attr('checked', true);
});

$(document).ready(function () {
	$(".server-size .deploy_body label .vps").click(function(){
		$(".server-size .deploy_body label > div").removeClass();
		$(".server-size .deploy_body label > div").addClass("vps");
		$(this).removeClass();
		$(this).addClass("vpsactive");
		var text1= $(".price", this).text();
		var text2= '(' + $(".division", this).text() + ')';
		$(".deploy .consequence .order_total").text(text1);
		$(".deploy .consequence .order_total_hr").text(text2);
	});
	
});

$(document).ready(function () {
	var location = window.location;
	var action = false;
	$(".billing_form .billingprice label input").click(function(){
		var value= $(this).attr('value');
		if(value)
		{
			$(".billing_form form").attr('action', location + '/' + value);
			action= true;
		}else{
			$(".billing_form form").attr('action', location);
			action= true;
		}
	});
	if(!action){
		var value= $(".billing_form .billingprice label input:first").attr('value');
		if(value)
		{
			$(".billing_form form").attr('action', location + '/' + value);
		}else{
			$(".billing_form form").attr('action', location);
		}
	}
	
});

































