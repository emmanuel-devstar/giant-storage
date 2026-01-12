(function ($) {

       $(document).ready(function () {

              window.fillForm = function () {
                     const fieldElements = document.querySelectorAll('.field-js');

                     fieldElements.forEach((field) => {

                            switch (field.name) {
                                   case 'first_name':
                                          field.value = 'John';
                                          break;
                                   case 'last_name':
                                          field.value = 'Doe';
                                          break;

                                   case 'name':
                                          field.value = 'John Doe';
                                          break;

                                   case 'email':
                                          field.value = 'johndoe@example.com';
                                          break;
                                   case 'country':
                                          field.value = 'United States';
                                          break;
                                   case 'phone_prefix':
                                          field.value = '+44';
                                          break;
                                   case 'phone':
                                          field.value = '1234567890';
                                          break;
                                   case 'permissions':
                                          field.click();
                                          break;

                                   case 'resort_name':
                                          field.value = "Paradise Island";
                                          break;
                                   case 'size':
                                          field.value = "120";
                                          break;
                                   case 'budget':
                                          field.value = "120 000";
                                          break;
                                   case 'company':
                                          field.value = 'TechNova Solutions';
                                          break;

                                   case 'sector':
                                          field.value = 'Renewable Energy';
                                          break;

                                   case 'message':
                                          field.value = "Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
                                          break;
                                   default:
                                          break;
                            }
                     });
              }



              function handleResize() {
                     clearTimeout(window.timeoutId);
                     window.timeoutId = setTimeout(function () {
                            window.timeoutId = null;
                            setRatioElements();
                            setMaxHeight('align-h-js');
                            updateMapOnResize();
                            //setMapWrapperSize();

                     }, 300);
              }

              window.addEventListener('resize', handleResize);
              setRatioElements();
              setMaxHeight('align-h-js');
              setTimeout(() => {
                     setMaxHeight('align-h-js');
              }, 500);


              function setMapWrapperSize() {

                     let newH = $(".details-right-js").height() + 300 + "px";

                     if ($(window).width() > 576) {
                            newH = 400 + "px";
                     }

                     $(".map-wrapper-js").height(newH);
              }

              function updateMapOnResize() {

                     if ($(".details-right-js").length === 0) {
                            return;
                     }

                     let newCenter = new google.maps.LatLng(window.map.location.lat, window.map.location.lng);

                     window.map.map.setCenter(newCenter);

                     const bounds = window.map.map.getBounds();
                     const ne = bounds.getNorthEast();
                     const sw = bounds.getSouthWest();

                     let distance = 0.3;

                     if ($(window).width() > 1200) {
                            distance = 0.4;
                     } else if ($(window).width() > 992) {
                            distance = 0.25;
                     }

                     const newLat = sw.lat() + (distance * (ne.lat() - sw.lat()));

                     const newLng = (ne.lng() + sw.lng()) / 2;

                     newCenter = new google.maps.LatLng(newLat, newLng);

                     window.map.map.setCenter(newCenter);

              }

              class Hamburger {
                     constructor() {
                            const hamburger = document.querySelector(".hamburger-js");
                            const menuMobile = document.querySelector(".menu-mobile-js");
                            const navTop = document.querySelector(".c-nav-top");

                            hamburger.addEventListener('click', function () {
                                   menuMobile.classList.toggle("active");

                                   if (hamburger.classList.contains("active")) {
                                          hamburger.classList.remove("active");
                                          document.querySelector(".nav-top-js").classList.remove("mobile-active");

                                          if (navTop.classList.contains("sticky")) return;

                                          if (navTop.classList.contains("section-transparent-tmp")) {
                                                 navTop.classList.replace("section-transparent-tmp", "section-transparent");
                                          }

                                   } else {
                                          hamburger.classList.add("active");
                                          document.querySelector(".nav-top-js").classList.add("mobile-active");

                                          if (navTop.classList.contains("sticky")) return;

                                          if (navTop.classList.contains("section-transparent")) {
                                                 navTop.classList.replace("section-transparent", "section-transparent-tmp");
                                          }
                                   }
                            });
                     }
              }
              const hamburger = new Hamburger();

              class StickyHeader {
                     constructor() {
                            this.siteHeader = document.querySelector(".c-nav-top");

                            document.addEventListener("scroll", () => this.scroll());
                     }

                     scroll() {
                            if (window.scrollY > 60) {
                                   this.siteHeader.classList.add("sticky");

                                   if (!this.siteHeader.classList.contains("mobile-active")) {
                                          if (this.siteHeader.classList.contains("section-transparent")) {
                                                 this.siteHeader.classList.replace("section-transparent", "section-transparent-tmp");
                                          }
                                   }


                            } else {
                                   this.siteHeader.classList.remove("sticky");
                                   if (!this.siteHeader.classList.contains("mobile-active")) {
                                          if (this.siteHeader.classList.contains("section-transparent-tmp")) {
                                                 this.siteHeader.classList.replace("section-transparent-tmp", "section-transparent");
                                          }
                                   }

                            }
                     }
              }
              const stickyHeader = new StickyHeader();

              class ScrollDown {
                     constructor() {
                            try {
                                   let scrollLinks = document.querySelectorAll("a[href*=\\#]");
                                   scrollLinks.forEach((item, index) => {

                                          item.addEventListener("click", (e) => {
                                                 let anchor = item.getAttribute("href");

                                                 const currentPageUrl = window.location.href.split("#")[0];

                                                 const hash = anchor.indexOf("#");

                                                 if (hash > 0) { //https://dev.gorilla3pl.co.uk/#services                                                        
                                                        if (!anchor.includes(currentPageUrl)) { //not the same page -> scrolling in footer.php
                                                               return;
                                                        }
                                                 }

                                                 if ($(".mobile-active").length) { //if mobile menu active hide menu
                                                        $(".hamburger-js").click();
                                                 }

                                                 e.preventDefault();
                                                 e.stopPropagation();

                                                 if (hash > 0) { //extract hash if url like https://dev.gorilla3pl.co.uk/#services
                                                        anchor = "#" + item.getAttribute("href").split("#")[1];
                                                 }

                                                 this.scroll(anchor, e);
                                          });

                                   });

                            } catch (err) {
                                   //console.log(err.message);
                            }
                     }

                     scroll(anchor, e) {

                            if (!document.querySelector(anchor)) return;

                            let top = document.querySelector(anchor).getBoundingClientRect().top + window.scrollY;

                            let offset = document.querySelector(anchor).getAttribute("data-offset");

                            if (!offset) {
                                   top = top - 100;
                            } else {
                                   top = top - offset;
                            }

                            jQuery('html, body').animate({
                                   scrollTop: top
                            }, 800);


                     }

              }

              const sd = new ScrollDown();

       });

       function setRatioElements() {
              const ratioElements = document.querySelectorAll(".ratio-js");

              ratioElements.forEach(function (element) {
                     const ratio = parseFloat(element.getAttribute("data-ratio"));
                     const base = element.getAttribute("data-base");

                     if (base === "height") {
                            const width = element.clientHeight * ratio;
                            element.style.width = width + "px";
                     } else {
                            const height = element.clientWidth * ratio;
                            element.style.height = height + "px";
                     }

                     element.classList.add("changed");
              });

       }

       window.setRatioElements = setRatioElements;

       function setMaxHeight(className = "align-h-js") {

              const elementsWithClass = document.querySelectorAll("." + className);

              const uniqueDataBlockValues = new Set();

              elementsWithClass.forEach((element) => {
                     const dataBlock = element.getAttribute('data-block');
                     if (dataBlock) {
                            uniqueDataBlockValues.add(dataBlock);
                     }
              });

              const uniqueDataBlockArray = Array.from(uniqueDataBlockValues);

              uniqueDataBlockArray.forEach((block) => {
                     const elements = document.querySelectorAll('.' + className + '[data-block=' + block + ']');
                     setMaxHeightForElements(elements);
              });

       }

       window.setMaxHeight = setMaxHeight;

       function setMaxHeightForElements(elements) {

              for (var i = 0; i < elements.length; i++) {
                     elements[i].style.height = 'auto';
              }

              var maxHeight = 0;

              var groupHeights = {};

              for (var i = 0; i < elements.length; i++) {
                     var element = elements[i];

                     const groups = ["data-align"];

                     if ($(window).width() >= 576) {
                            groups.push("data-align-sm");
                     }

                     if ($(window).width() >= 768) {
                            groups.push("data-align-md");
                     }

                     if ($(window).width() >= 992) {
                            groups.push("data-align-lg");
                     }

                     if ($(window).width() >= 1200) {
                            groups.push("data-align-xl");
                     }

                     var groupName = false;

                     groups.forEach(group => {
                            if (element.getAttribute(group)) {
                                   groupName = element.getAttribute(group);
                            }
                     });

                     var elementHeight = element.offsetHeight;

                     if (elementHeight > maxHeight) {
                            maxHeight = elementHeight;
                     }

                     if (!groupHeights[groupName] || elementHeight > groupHeights[groupName]) {
                            groupHeights[groupName] = elementHeight;
                     }
              }

              for (var i = 0; i < elements.length; i++) {
                     var element = elements[i];

                     const groups = ["data-align"];

                     if ($(window).width() >= 576) {
                            groups.push("data-align-sm");
                     }

                     if ($(window).width() >= 768) {
                            groups.push("data-align-md");
                     }

                     if ($(window).width() >= 992) {
                            groups.push("data-align-lg");
                     }

                     if ($(window).width() >= 1200) {
                            groups.push("data-align-xl");
                     }

                     var groupName = false;

                     groups.forEach(group => {
                            if (element.getAttribute(group)) {
                                   groupName = element.getAttribute(group);
                            }
                     });

                     element.style.height = groupHeights[groupName] + 'px';
              }


       }

}(jQuery));