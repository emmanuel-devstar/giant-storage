<script src='https://www.google.com/recaptcha/api.js?render=explicit' async defer></script>
<script>
    window.rcArr = [];
    window.addEventListener("load", function() {
        const forms = document.querySelectorAll(".form-rc-js");        

        forms.forEach((form, index) => {
            form.querySelectorAll(".field-js").forEach(
                (item, index) => {                    
                    item.addEventListener("click", recatpchaOn, false);   
                    item.form = form;                 
                }
            );
                
        });
    });


    function recatpchaOn(evt) {        

        const form = evt.currentTarget.form;                

        var captchaContainer = null;
        const rc = form.querySelectorAll(".recaptcha-js");        

        rc.forEach((item, index) => {
            item.classList.add("mb-4");
            captchaContainer = grecaptcha.render(item, {
                'sitekey': "<?= Configuration::$rc_site_key ?>"
            });             
            window.rcArr[item.id] = captchaContainer;            
        });

        form.querySelectorAll(".field-js").forEach(
            (item, index) => {
                item.removeEventListener("click", recatpchaOn);
                item.form = "";
            }
        );

    };


</script>