<?php
$fields = get_field("additional_fields");
if (!is_array($fields)) {
    $fields = [];
}
?>
<form class="c-form form-rc-js " id="contact-form" medthod="POST" action="contact-form" send="send_ajax" title="Contact Form">

    <input type="hidden" name="reply_to_user" value="yes">
    <input type="hidden" name="recipient_email" value="<?= base64_encode(Configuration::$contact["delivery_emails"]);  ?>">

    <div class="row">
        <div class="col-12">
            <div class="form__error"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form__thanks"> <?= Configuration::$contact["form"]["thank_you_message"] ?></div>
        </div>
    </div>

    <div class="hide-after-js">

        <div class="row">
            <div class="col-12 col-md-6">
                <input type="text" class="form__input field-js" name="name" placeholder="Name *" required>
            </div>
            <div class="col-12 col-md-6">
                <input type="email" class="form__input field-js" name="email" placeholder="Email address *" required>
            </div>
        </div>

        <div class="row">

            <?php
            $index = 0;
            foreach ($fields as $field) :

                if ($field["name"]) :
                    $placeholder = $field["name"] . ($field["required"] ? " *" : "");
                    $required = $field["required"] ? " required" : "";
                    $index++;
                    if ($index % 2) {
                        echo '</div><div class="row">';
                    };
            ?>
                    <div class="col-12 col-md-6">
                        <input type="text" class="form__input field-js" name="<?= strtolower($field["name"]) ?>" placeholder="<?= $placeholder ?>" <?= $required; ?>>
                    </div>
            <?php
                endif;

            endforeach;
            ?>

        </div>

        <div class="row">
            <div class="col-12">
                <textarea name="message" class="form__textarea field-js" rows="6" placeholder="Your message *" required></textarea>
            </div>
        </div>

        <?php
        if ($services && (count($services) > 0)):
        ?>
            <div class="row">
                <div class="col-12">
                    <div class="s__break"></div>
                    <h3 class="s__title  d-block u-w-100 mb-5"> Services interested in</h3>
                </div>

                <?php foreach ($services as $key => $service): ?>

                    <div class="col-12 col-lg-6">
                        <label class="form__label o-custom-check mb-5  permission-wrapper">
                            <input class="service-check-js" type="checkbox" name="service<?= $key; ?>" value="<?= $service["service"] ?>">
                            <span class="center">
                                <div class="checked">
                                    <?= file_get_contents(IMAGES . '/icons/check-mark.svg'); ?>
                                </div>
                                <div class="unchecked">
                                </div>

                                <p class="form__permission ml-4">
                                    <?= $service["service"] ?>
                                </p>
                            </span>
                        </label>

                    </div>

                <?php endforeach; ?>

            </div>
            <div class="mb-5 mb-lg-5"></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="permission-js fade-in-js u-text-right">
                    <label class="form__label o-custom-check mb-5  permission-wrapper">
                        <input class="privacy-policy-js" type="checkbox" name="permissions" value="true" required>
                        <span>
                            <div class="checked">
                                <?= file_get_contents(IMAGES . '/icons/check-mark.svg'); ?>
                            </div>
                            <div class="unchecked">

                            </div>

                            <p class="form__permission ml-4 u-flex-1">
                                <?= Configuration::$contact["form"]["permissions"] ?>
                            </p>
                        </span>
                    </label>
                </div>
            </div>
        </div>


        <button class="form__btn std-btn-quaternary g-recaptcha-v3 btn-submit-js" type="submit">Send </button>

    </div>



</form>