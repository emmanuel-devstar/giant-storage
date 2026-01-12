(function ($) {

    $.each($(".post-feed-js"), function (i, postFeed) {

        var postFeed = $(postFeed);
        var carouselId = postFeed.attr("carousel-id");
        const postsNumber = postFeed.attr("posts-number");

        let loop = (postsNumber < settings.number_of_columns) ? false : true;

        if ($(window).width() < 992) {
            loop = (postsNumber < 3) ? false : true;
        } else if ($(window).width() < 768) {
            loop = (postsNumber < 2) ? false : true;
        }

        postFeed.owlCarousel({
            lazyLoad: true,
            margin: 0,
            smartSpeed: 500,
            autoplay: false,
            autoplayTimeout: 4500,
            responsiveClass: true,
            loop: loop,
            nav: false,
            dots: false,
            items: 2,
            onChange: function () {

            },
            onChanged: function (event) {

            },
            onInitialized: function () {

                $(".nav-js[carousel-id = '" + carouselId + "']").find(".prev-js").click(() => {
                    postFeed.find(".owl-prev").trigger('prev.owl.carousel');
                });

                $(".nav-js[carousel-id = '" + carouselId + "']").find(".next-js").click(() => {
                    postFeed.find(".owl-next").trigger('next.owl.carousel');
                });

                window.setRatioElements();
                window.setMaxHeight();

            },
            responsive: {
                768: {
                    margin: 10,
                    items: 3,
                },
                992: {
                    items: settings.number_of_columns,
                },
            }

        });
    });

}(jQuery))