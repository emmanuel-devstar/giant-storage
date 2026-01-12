
<div class="modal__wrapper modal-support support-modal-js">
    <div class="modal__container container">

        <div id="support-modal" class="form__wrapper section-white  mx-auto">
            <img class="modal__x modal-close-js" src="<?= IMAGES ?>/x-mark.svg" alt="close">

            <form id="form-offer" action="" enctype="multipart/form-data">
                <h2 class="modal__title mb-4 mb-lg-9">
                    Contact Support
                </h2>                

                <div class="row modal__error">
                    <div class="col-12 u-text-left">
                        <p class="modal__error-text">                              
                        </p>
                    </div>
                </div>

                <div class="row modal__msg">
                    <div class="col-12 u-text-left">
                        <p class="modal__msg-text">                              
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label class="label--modal">Name *</label>
                        <input class="input--modal " type="text" value="" name="full_name" placeholder="Enter your full name" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label class="label--modal">Email *</label>
                        <input class="input--modal " type="text" value="" name="email" placeholder="your@youremail" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label class="label--modal">Message *</label>
                        <textarea class="input--modal " rows="6" value="" name="message" required></textarea>
                    </div>
                </div>
                        
                <div class="g-recaptcha" data-form="support-modal" data-sitekey="6LdryV8UAAAAAIZEvqUaSEttca1anP2GeMviDhjw"></div>

                <div class="row">
                    <div class="col-12">
                        <div class="modal__submit--wrapper">                            

                            <button type="submit" class="btn--round btn--blue">Submit</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>


