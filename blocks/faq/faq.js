(function ($) {
    if ($(".faq-row-js").length === 0) return;

    $(".faq-row-js").click(
        function () {

            const topic = $(this);

            if ($(topic).hasClass("active")) {

                $(topic).find(".acc-content-js").slideUp("fast");
            } else {

                $(topic).find(".acc-content-js").slideDown("fast");
            }

            $(topic).toggleClass("active");

        }
    );

}(jQuery));