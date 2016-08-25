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

// $(document).ready(function(){
	// $(".server-size .deploy_body label input:radio[name=plan]:first").attr('checked', true);
	// var check= $(".server-size .deploy_body label input").attr("checked");
	// if(check=="checked")
	// {
		// $(".server-size .deploy_body label div").removeClass();
		// $(".server-size .deploy_body label div").addClass("vpsactive");
	// }
// });

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
