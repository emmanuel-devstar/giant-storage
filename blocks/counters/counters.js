(function ($) {
    class Counters {
        constructor() {
            this.countersScroll();

            document.addEventListener("scroll", () => this.countersScroll());
        }

        countersScroll() {
            const counters = document.querySelector(".counters-js");
            if (!counters) return;

            let scrollPercent = (counters.getBoundingClientRect().top / window.innerHeight) * 100;

            if ((scrollPercent < 90) && !counters.classList.contains("done")) {
                $('.counter-js').countTo({
                    formatter: function (value, options) {

                        let decimals = counters.getAttribute("data-decimals");
                        const regex = new RegExp(`\\B(?=(\\d{${decimals}})+(?!\\d))`, "g");
                        return value.toFixed(0).replace(regex, ",");
                    }
                });
                counters.classList.add("done");
            }
        }

    }

    $(document).ready(function () {
        var counters = new Counters();
    });

}(jQuery));