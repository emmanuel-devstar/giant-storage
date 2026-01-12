(function ($) {

    $(document).ready(function () {

        $.each($(".logotypes-js"), function(i,logotypes) {

            var logotypes = $(logotypes);
            

            const items = logotypes.attr("data-items");
            var loop = true;

            if(items <= 2 ){
                loop = false;
            }  

            if ($(window).width() >= 678) {
                if(items <= 3 ){
                    loop = false;
                }                
            }

            if ($(window).width() >= 992) {
                if(items <= 4 ){
                    loop = false;
                }                
            }

            if ($(window).width() >= 1200) {
                    if(items <= 6 ){
                        loop = false;
                    }                
            }            
            
            logotypes.owlCarousel({                
                lazyLoad: true,
                margin: 0,                
                smartSpeed: 500,
                autoplay: false,
                autoplayTimeout: 3000,
                responsiveClass: true,
                loop: loop,
                nav: true,
                dots: false,
                items: 2,                
                onInitialized: function (el) {                                                 
                                                                         
                    if($(el.target).find(".owl-nav").hasClass("disabled")){
                        logotypes.parent().find(".nav-js").addClass("hide");
                    }

                    $(el.target).parent().find(".l-prev-js").click(() => {                        
                        logotypes.find(".owl-prev").trigger('prev.owl.carousel');
                    });
                    
                    $(el.target).parent().find(".l-next-js").click(() => {
                        logotypes.find(".owl-next").trigger('next.owl.carousel');
                    });

                },
                responsive: {
                    678: {
                        items: 3,                    
                    },
                    992: {
                        items: 4,                    
                    },
                    1200: {
                        items: 6,                    
                    }
                }
            });
        });

    })

}(jQuery))