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
