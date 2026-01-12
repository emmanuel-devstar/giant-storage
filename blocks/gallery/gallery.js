(function ($) {

    $(document).ready(function () {

        $.each($(".media-carousel-js"), function(i,c) {

            var c = $(c);
            
            c.owlCarousel({                
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
                        c.find(".owl-prev").trigger('prev.owl.carousel');
                    });
                    $(el.target).parent().find(".next-js").click(() => {
                        c.find(".owl-next").trigger('next.owl.carousel');
                    });
                },
            });
        });

    })

}(jQuery))