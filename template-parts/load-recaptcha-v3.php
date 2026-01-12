<script>
    function loadRecaptchaScript() {
        const script = document.createElement('script');
        script.src = 'https://www.google.com/recaptcha/api.js?render=<?= Configuration::$rc_site_key ?>';
        script.async = true;
        script.defer = true;
        document.body.appendChild(script);
    }

    const rcAll = document.querySelectorAll(".g-recaptcha-v3");

    if (rcAll.length > 0) {
        loadRecaptchaScript();

        setTimeout(() => {
            if (document.querySelector(".grecaptcha-badge")) {
                document.querySelector(".grecaptcha-badge").classList.add("visible");
            }
        }, 1000);
    }
</script>