class Accordion {
  constructor($) {   
    $(".agenda-row-js").click(
      function(){
        
        if($(this).hasClass("active")){
          $(this).find(".acc-arrow-js").removeClass("fa-chevron-up");
          $(this).find(".acc-arrow-js").addClass("fa-chevron-down");
          $(this).find(".acc-content-js").slideUp("fast");
        }else{
          $(this).find(".acc-arrow-js").removeClass("fa-chevron-down");
          $(this).find(".acc-arrow-js").addClass("fa-chevron-up");
          $(this).find(".acc-content-js").slideDown("fast");
        }

        $(this).toggleClass("active");
        
      }
    );               
  }       
}

export default Accordion
