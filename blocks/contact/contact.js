(function ($) {
  class ContactFormRcV3 {
    constructor(form) {
      try {
        this.form = document.querySelector(form);
        this.submit = this.form.querySelector(".btn-submit-js");

        this.events();
      } catch (err) {
        console.log(err.message);
      }
    }

    events() {
      $(this.form)
        .find(".field-js")
        .on("keyup", () => {
          var filled = false;

          $(this.form)
            .find(".field-js")
            .each(function () {
              if ($(this).val().length > 0) {
                filled = true;
              }
            });

          if (filled) {
            $(".fade-in-js").fadeIn();
          } else {
            $(".fade-in-js").fadeOut();
          }
        });

      this.submit.addEventListener("click", (e) => {
        e.preventDefault();

        const form = $(this.form);

        grecaptcha.ready(() => {
          if (this.form.checkValidity()) {
            grecaptcha
              .execute(contact.rcSiteKey, {
                action: "submit",
              })
              .then(function (token) {
                const action = form.attr("send");
                const title = form.attr("title");

                $.ajax({
                  type: "POST",
                  url: ajaxUrl,
                  dataType: "html",
                  data:
                    form.serialize() +
                    "&action=" +
                    action +
                    "&title=" +
                    title +
                    "&g-recaptcha-response=" +
                    token,
                  success: function (resp) {
                    if (resp === "ok") {
                      form.parent().find(".form__error").removeClass("active");

                      form.find(".field-js").each(function () {
                        $(this).val("");
                      });

                      form.find(".service-check-js").prop("checked", false);
                      form.find(".privacy-policy-js").prop("checked", false);

                      if (contact.redirect_on_submit) {
                        window.location.href = contact.redirect_on_submit;
                      } else {
                        form.find(".form__thanks").addClass("active");
                        setTimeout(() => {
                          form.find(".form__thanks").removeClass("active");
                        }, 5000);
                      }
                    } else {
                      form
                        .parent()
                        .find(".form__error")
                        .html(resp)
                        .addClass("active");
                      form.parent().find(".form__thanks").removeClass("active");
                    }
                  },
                  error: function () {
                    form
                      .parent()
                      .find(".form__error")
                      .html(
                        "There was an error trying to send your message. Please try again later.",
                      )
                      .addClass("active");
                    form.parent().find(".form__error").addClass("active");
                    form.parent().find(".form__thanks").removeClass("active");
                  },
                }).always(function () {});
              });
          } else {
            this.form.reportValidity();
          }
        });
      });
    }
  }
  class Map {
    constructor() {
      this.map = false;
      this.view = document.querySelector("#googleMap");
      if (!this.view) {
        return;
      }

      this.zoom = 16;

      this.markers = [];
      this.location = [];

      this.location.lat = parseFloat(contact.lat);
      this.location.lng = parseFloat(contact.lng);
    }

    addMarker() {
      var marker = new google.maps.Marker({
        position: {
          lat: this.location.lat,
          lng: this.location.lng,
        },
        icon: {
          url: themeUri + "/images/icons/marker.svg",
        },
      });

      marker.setMap(this.map);

      this.markers.push(marker);
    }

    removeMarkers() {
      for (let i = 0; i < this.markers.length; i++) {
        this.markers[i].setMap(null);
      }
    }

    init() {
      /*             const {
                            AdvancedMarkerElement
                        } = await google.maps.importLibrary("marker"); */

      this.map = new google.maps.Map(this.view, {
        zoom: this.zoom,
        zoomControl: true,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false,
        styles: [
          {
            featureType: "all",
            elementType: "labels.text.fill",
            stylers: [
              {
                saturation: 36,
              },
              {
                color: "#333333",
              },
              {
                lightness: 40,
              },
            ],
          },
          {
            featureType: "all",
            elementType: "labels.text.stroke",
            stylers: [
              {
                visibility: "on",
              },
              {
                color: "#ffffff",
              },
              {
                lightness: 16,
              },
            ],
          },
          {
            featureType: "all",
            elementType: "labels.icon",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            featureType: "administrative",
            elementType: "geometry.fill",
            stylers: [
              {
                color: "#fefefe",
              },
              {
                lightness: 20,
              },
            ],
          },
          {
            featureType: "administrative",
            elementType: "geometry.stroke",
            stylers: [
              {
                color: "#fefefe",
              },
              {
                lightness: 17,
              },
              {
                weight: 1.2,
              },
            ],
          },
          {
            featureType: "landscape",
            elementType: "geometry",
            stylers: [
              {
                color: "#f5f5f5",
              },
              {
                lightness: 20,
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "geometry",
            stylers: [
              {
                color: "#f5f5f5",
              },
              {
                lightness: 21,
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [
              {
                color: "#c3e7b4",
              },
              {
                lightness: 21,
              },
            ],
          },
          {
            featureType: "road.highway",
            elementType: "geometry.fill",
            stylers: [
              {
                color: "#ffffff",
              },
              {
                lightness: 17,
              },
            ],
          },
          {
            featureType: "road.highway",
            elementType: "geometry.stroke",
            stylers: [
              {
                color: "#ffffff",
              },
              {
                lightness: 29,
              },
              {
                weight: 0.2,
              },
            ],
          },
          {
            featureType: "road.arterial",
            elementType: "geometry",
            stylers: [
              {
                color: "#ffffff",
              },
              {
                lightness: 18,
              },
            ],
          },
          {
            featureType: "road.local",
            elementType: "geometry",
            stylers: [
              {
                color: "#ffffff",
              },
              {
                lightness: 16,
              },
            ],
          },
          {
            featureType: "transit",
            elementType: "geometry",
            stylers: [
              {
                color: "#f2f2f2",
              },
              {
                lightness: 19,
              },
            ],
          },
          {
            featureType: "water",
            elementType: "geometry",
            stylers: [
              {
                color: "#e9e9e9",
              },
              {
                lightness: 17,
              },
            ],
          },
        ],
      });

      this.map.setCenter(this.location);

      this.addMarker();
    }
  }

  window.map = new Map();

  $(document).ready(function () {
    const bf = new ContactFormRcV3("#contact-form");
  });
})(jQuery);
