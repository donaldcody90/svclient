$(document).ready(function () {
   var location = window.location;
   var found = false;
   $(".credit_card a").each(function(){
      var href = $(this).attr("href");
      if(href==location){
         $(this).parent().addClass("selected");
         found = true;
      }
   });
   if(!found){
      $(".credit_card li:first").addClass("selected");
   }
});

$(document).ready(function () {
	$(".deploy_body label .detail2").click(function(){
		var text1= $(".price", this).text();
		var text2= '(' + $(".division", this).text() + ')';
		$(".deploy .consequence .order_total").text(text1);
		$(".deploy .consequence .order_total_hr").text(text2);
	});
	
});