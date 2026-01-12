(function ($) {

    $(document).ready(function () {

        $.each($(".testimonials-js"), function(i,testimonials) {

            var testimonials = $(testimonials);
            
            testimonials.owlCarousel({                
                lazyLoad: true,
                margin: 0,                
                smartSpeed: 500,
                autoplay: false,
                autoplayTimeout: 4500,
                responsiveClass: true,
                loop: true,
                nav: false,
                dots: false,
                items: 1,                
                onInitialized: function (el) {                     

                    $(el.target).parent().find(".prev-js").click(() => {                        
                        testimonials.find(".owl-prev").trigger('prev.owl.carousel');
                    });
                    $(el.target).parent().find(".next-js").click(() => {
                        testimonials.find(".owl-next").trigger('next.owl.carousel');
                    });
                },
            });
        });

    })

}(jQuery))