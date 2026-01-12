(function ($) {

    $(document).ready(function () {

        teamData = JSON.parse(teamData);
        /* console.log(teamData["ver"]); */

        $.each($(".team-js"), function (i, team) {

            var team = $(team);
            var carouselId = team.attr("carousel-id");

            team.owlCarousel({
                lazyLoad: false,
                margin: 40,
                smartSpeed: 500,
                autoplay: false,
                autoWidth: false,
                autoplayTimeout: 3000,
                responsiveClass: true,
                loop: true,
                nav: false,
                dots: false,
                items: 1,
                responsive: {
                    500: {
                        autoWidth: true,
                    },
                },
                onResize: function (event) {
                    let newWidth = $(team).find(".owl-stage").width() + 100;
                    setTimeout(function () {
                        $(team).find(".owl-stage").css("width", newWidth + "px");
                    }, 10);

                },
                onChange: function () {
                    if ($(window).width() > 500) {
                        $(team).find(".owl-item").removeClass("big");
                    }

                },
                onChanged: function (event) {

                    setTimeout(function () {

                        let index = $(event.currentTarget).find(".owl-item.active").eq(0).find("img").data("index");

                        const teamWrapper = $(team).parent().parent().parent();

                        teamWrapper.find(".m-name-js").text(teamData[index].name);
                        teamWrapper.find(".m-position-js").text(teamData[index].position);
                        teamWrapper.find(".m-email-js").text(teamData[index].email);
                        teamWrapper.find(".m-phone-js").text(teamData[index].phone);
                        teamWrapper.find(".m-description-js").html(teamData[index].description);
                        if (teamData[index].linkedin) {
                            teamWrapper.find(".member-linkedin-js").show();
                            teamWrapper.find(".member-linkedin-js").attr("href", teamData[index].linkedin);
                        } else {
                            teamWrapper.find(".member-linkedin-js").hide();
                        }

                        if ($(window).width() > 500) {
                            $(event.currentTarget).find(".owl-item.active").eq(0).addClass("big");
                        }
                    }, 10);

                },
                onInitialized: function () {
                    let newWidth = $(team).find(".owl-stage").width() + 100;

                    setTimeout(function () {
                        $(team).find(".owl-stage").css("width", newWidth + "px");
                    }, 10);


                    $(".team-nav-js[carousel-id = '" + carouselId + "']").find(".t-prev-js").click(() => {

                        team.find(".owl-prev").trigger('prev.owl.carousel');

                    });

                    $(".team-nav-js[carousel-id = '" + carouselId + "']").find(".t-next-js").click(() => {

                        team.find(".owl-next").trigger('next.owl.carousel');

                    });

                },

            });
        });

    })

}(jQuery))