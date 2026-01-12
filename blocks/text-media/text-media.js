(function($) {        

    $(document).ready(function() {

        $.each($(".banner-js"), function(i,banner) {
            
            var banner = $(banner);
            
            banner.owlCarousel({
                /* slideSpeed: 1000,   */            
                lazyLoad: false,
                margin: 0,
                /* paginationSpeed: 1000, */
                smartSpeed: 500,
                autoplay: banner.attr("data-autoplay"),
                autoplayTimeout: banner.attr("data-interval"),
                responsiveClass: true,
                loop: true,
                nav: false,
                dots: true,                
                items: 1,   
                autoHeight: false,             
                onInitialized: function(el) {
                    $(el.target).parent().find(".tm-prev-js").click(() => {
                        banner.find(".owl-prev").trigger('prev.owl.carousel');
                    });
                    $(el.target).parent().find(".tm-next-js").click(() => {
                        banner.find(".owl-next").trigger('next.owl.carousel');
                    });
                },
                responsive : {                                                                        
                    1200 : {
                        dots : false,                 
                    }
                }
            });

        });

    })
}(jQuery))