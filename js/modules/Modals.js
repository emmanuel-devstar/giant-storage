(function ($) {
    $(document).ready(function () {        

        var modal = {};

        modal.closeEvents = function () {
            //click outside wraper -> close modal        
            var self = this;


            this.view.click(
                function (e) {
                    if ($(e.target).hasClass("modal__wrapper") || $(e.target).hasClass("modal__container")) {
                        self.hideModal();
                    }
                }
            );

            //x -> close modal
            this.view.find(".modal-close-js").click(
                function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    self.hideModal();
                }
            );

        }


        modal.showError = function (error) {
            this.view.find(".modal__error").hide();
            this.view.find(".modal__msg").hide();
            this.view.find(".modal__error-text").text(error);
            this.view.find(".modal__error").show();
        };

        modal.showMsg = function (msg) {
            this.view.find(".modal__error").hide();
            this.view.find(".modal__msg").hide();
            this.view.find(".modal__msg-text").text(msg);
            this.view.find(".modal__msg").show();
        };

        modal.resetForm = function () {
            this.view.find(".modal__error").hide();
            this.view.find(".modal__msg").hide();
            this.view.find(".modal__msg-text").text("");
            this.view.find(".modal__error-text").text("");

            this.view.find("input").each(
                function () {
                    if ($(this).is(':checkbox')) {
                        $(this).attr("checked", false);
                    } else {
                        $(this).val("");
                    }
                }
            );

            this.view.find("textarea").each(
                function () {
                    this.value = "";
                }
            );


        };

        modal.showModal = function () {
            this.view.addClass("active");
            this.view.addClass("a-fade-in").removeClass("a-fade-out");
            this.view.find(".form__wrapper").addClass("a-popup-grow").removeClass("a-popup-shrink");
            this.view.find(".modal__content").addClass("a-popup-grow").removeClass("a-popup-shrink");

        };

        modal.hideModal = function () {

            this.view.removeClass("a-fade-in").addClass("a-fade-out");
            this.view.find(".form__wrapper").addClass("a-popup-shrink").removeClass("a-popup-grow");
            this.view.find(".modal__content").addClass("a-popup-shrink").removeClass("a-popup-grow");
            var self = this;
            setTimeout(function () {
                self.view.removeClass("active");
            }, 250);

            if (window.player) {

                setTimeout(function () {
                    const videoCont = document.querySelector(".video-js");

                    videoCont.querySelector(".o-video__poster").style.display = "block";
                    videoCont.classList.remove("active");

                    const videoPlay = document.querySelector(".video-play-js");
                    videoPlay.style.display = "block";

                    window.player.loadVideo(445341890);
                }, 1000);


            }
        };



        let support = {};
        support = $.extend(support, modal);
        support.view = $(".support-modal-js");
        support.btn = document.querySelector(".support-js");
        support.init = function () {
            if (support.btn) {
                support.closeEvents();

                support.btn.addEventListener("click", () => {
                    support.resetForm();
                    support.showModal();
                })
            }
        }

        support.init();

  






 
    });
})(jQuery);