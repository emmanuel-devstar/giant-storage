<div class="banner__start">

    <div class="c-banner l-section-top section-transparent l-banner-form <?= $mask_class ?>" <?= $background_image; ?>>

        <div class="container-fluid u-z-index-20  <?= $carousel["horizontal_aligment"] ?>">
            <div class="row u-w-1350-100">
                <div class="col-12 col-left">
                    <div class="banner__content ">

                        <?php if ($content["title"]) : ?>

                            <<?= $heading_tag; ?> class="banner__title custom-title-colour">
                                <?= $content["title"] ?>
                            </<?= $heading_tag; ?>>

                        <?php endif; ?>

                        <?php if ($content["description"]) : ?>

                            <div class="banner__desc u-pb-last-of-type-0 u-mb-last-of-type-0 wysiwyg">
                                <?= $content["description"] ?>
                            </div>

                        <?php endif; ?>

                        <?php
                        if (isset($ctas["button_cta_left"]) && $ctas["button_cta_left"] || isset($ctas["button_cta_right"]) && $ctas["button_cta_right"]) :
                        ?>
                            <div class="desc__bottom"></div>
                        <?php
                        endif;
                        ?>

                        <?php
                        $mr = isset($ctas["button_cta_right"]) && $ctas["button_cta_right"]  ? "mr-3" : "";

                        $btn_class_1 = "std-btn-primary";
                        $btn_class_2 = "std-btn-secondary";

                        echo btn_from_link($ctas["button_cta_left"], $btn_class_1  . " "  . $mr);
                        ?>
                        <?php
                        echo btn_from_link($ctas["button_cta_right"], $btn_class_2);
                        ?>
                    </div>
                    <!-- <div class="u-clearfix"></div> -->
                </div>
                <div class="col-12 col-right bf-desktop">
                    <div class="banner__form u-shadow">
                        <form id="banner-form--desktop-<?= esc_attr($id) ?>" class="c-form pb-0 " action="banner-form" medthod="POST" send="send_ajax" title="Form" data-redirect="<?= $form["redirect_on_submit"] ?>">

                            <input type="hidden" name="recipient_email" value="<?= base64_encode($form["recipient_email"]); ?>">
                            <input type="hidden" name="reply_to_user" value="yes">

                            <div class="row">
                                <div class="col-12">
                                    <div class="form__error"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form__thanks"> <?= $form["thank_you_message"] ?></div>
                                </div>
                            </div>

                            <div class="hide-after-js">

                                <div class="row">
                                    <div class="col-12">
                                        <?php
                                        show_if_exist('<h3 class="bf__title">%s</h3>', $form["heading"]);
                                        show_if_exist('<p class="bf__info">%s</p>', $form["support_text"]);
                                        ?>
                                    </div>
                                </div>

                                <div class="row">

                                    <?php
                                    $index = 0;
                                    foreach ($form["fields"] as $field) :

                                        if ($field["name"]) :
                                            $placeholder = $field["name"] . ($field["required"] ? " *" : "");
                                            $type = $field["type"];
                                            $min = ($type === "number") ? "min='0'" : "";
                                            $required = $field["required"] ? " required" : "";
                                            $index++;
                                            if ($index % 2) {
                                                echo '</div><div class="row">';
                                            };
                                    ?>
                                            <div class="col-12">
                                                <input type="<?= $type; ?>" <?= $min; ?> class="form__input field-js" name="<?= strtolower($field["name"]) ?>" placeholder="<?= $placeholder ?>" <?= $required; ?>>
                                            </div>
                                    <?php
                                        endif;

                                    endforeach;
                                    ?>

                                </div>

                                <div class="row">
                                    <div class="col-12 permission">

                                        <button class="bf__btn btn btn--highlighted hover-outline-highlighted btn-submit-js g-recaptcha-v3" type="submit">Get estimate </button>
                                        <?php
                                        show_if_exist('<div class="bf__gdpr fade-in-js">%s</div>', $form["gdpr_text"]);
                                        ?>

                                    </div>
                                </div>

                                <div class="u-clearfix"></div>

                            </div>


                        </form>

                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="container-fluid bf-mobile u-relative">
        <div class="banner__form u-shadow">
            <form id="banner-form--mobile-<?= esc_attr($id) ?>" class="c-form pb-0 " action="banner-form" medthod="POST" send="send_ajax" title="Form" data-redirect="<?= $form["redirect_on_submit"] ?>">

                <input type="hidden" name="recipient_email" value="<?= base64_encode($form["recipient_email"]); ?>">
                <input type="hidden" name="reply_to_user" value="yes">

                <div class="row">
                    <div class="col-12">
                        <div class="form__error"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form__thanks"> <?= $form["thank_you_message"] ?></div>
                    </div>
                </div>

                <div class="hide-after-js">

                    <div class="row">
                        <div class="col-12">
                            <?php
                            show_if_exist('<h3 class="bf__title">%s</h3>', $form["heading"]);
                            show_if_exist('<p class="bf__info">%s</p>', $form["support_text"]);
                            ?>
                        </div>
                    </div>

                    <div class="row">

                        <?php
                        $index = 0;
                        foreach ($form["fields"] as $field) :

                            if ($field["name"]) :
                                $placeholder = $field["name"] . ($field["required"] ? " *" : "");
                                $type = $field["type"];
                                $min = ($type === "number") ? "min='0'" : "";
                                $required = $field["required"] ? " required" : "";
                                $index++;
                                if ($index % 2) {
                                    echo '</div><div class="row">';
                                };
                        ?>
                                <div class="col-12">
                                    <input type="<?= $type; ?>" <?= $min; ?> class="form__input field-js" name="<?= strtolower($field["name"]) ?>" placeholder="<?= $placeholder ?>" <?= $required; ?>>
                                </div>
                        <?php
                            endif;

                        endforeach;
                        ?>

                    </div>

                    <div class="row">
                        <div class="col-12 permission">

                            <?php
                            show_if_exist('<div class="bf__gdpr fade-in-js mb-4">%s</div>', $form["gdpr_text"]);
                            ?>
                            <button class="bf__btn btn btn--highlighted hover-outline-highlighted btn-submit-js g-recaptcha-v3" type="submit">Get estimate </button>


                        </div>
                    </div>

                    <div class="u-clearfix"></div>

                </div>


            </form>
        </div>
    </div>


</div>

<script>
    (function($) {

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

                $(this.form).find(".field-js").on("keyup", () => {
                    var filled = false;

                    $(this.form).find(".field-js").each(
                        function() {
                            if ($(this).val().length > 0) {
                                filled = true;
                            }
                        }
                    );

                    if (filled) {
                        $(".fade-in-js").fadeIn();
                    } else {
                        $(".fade-in-js").fadeOut();
                    }

                });

                this.form.addEventListener("submit", (e) => {
                    /* this.submit.addEventListener("click", (e) => { */
                    e.preventDefault();
                    e.stopPropagation();

                    const form = $(this.form);

                    grecaptcha.ready(() => {
                        if (this.form.checkValidity()) {

                            grecaptcha.execute("<?= Configuration::$rc_site_key ?>", {
                                action: 'submit'
                            }).then(function(token) {

                                const action = form.attr("send");
                                const title = form.attr("title");
                                const redirectOnSubmit = form.attr("data-redirect");

                                $.ajax({
                                    type: 'POST',
                                    url: ajaxUrl,
                                    dataType: 'html',
                                    data: form.serialize() + '&action=' + action + '&title=' + title + '&g-recaptcha-response=' + token,
                                    success: function(resp) {

                                        if (resp === 'ok') {

                                            form.parent().find('.form__error').removeClass("active");

                                            form.find(".field-js").each(
                                                function() {
                                                    $(this).val("");
                                                }
                                            );

                                            if (redirectOnSubmit) {
                                                window.location.href = redirectOnSubmit;
                                            } else {
                                                form.find(".form__thanks").addClass("active");
                                                setTimeout(() => {
                                                    form.find(".form__thanks").removeClass("active");
                                                }, 5000);
                                            }

                                        } else {
                                            form.parent().find('.form__error').html(resp).addClass("active");
                                            form.parent().find('.form__thanks').removeClass("active");
                                        }
                                    },
                                    error: function() {
                                        form.parent().find('.form__error').html("There was an error trying to send your message. Please try again later.").addClass("active");
                                        form.parent().find('.form__error').addClass("active");
                                        form.parent().find('.form__thanks').removeClass("active");
                                    }
                                }).always(function() {})
                            });
                        } else {
                            this.form.reportValidity();
                        }
                    });

                });
            }

        }

        jQuery(document).ready(function() {
            const bfd = new ContactFormRcV3("#banner-form--desktop-<?= esc_attr($id) ?>");
            const bfm = new ContactFormRcV3("#banner-form--mobile-<?= esc_attr($id) ?>");
        });
    }(jQuery));
</script>