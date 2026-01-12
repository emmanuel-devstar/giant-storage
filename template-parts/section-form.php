<div class="c-section">
    <div class="container-fluid">
        <div class="col-12">            
            <h3 class="section__title text-left mb-5"><?= get_field("c_title","option"); ?></h3>

            <div class="row">
                <div class="col-12 mb-4">
                    <p>W celu uzyskania dodatkowych informacji wypełnij poniższy formularz. </p>
                </div>
            </div>            

            <?php
            $mail = new Mail();
            $mail->make();   
            $mail->error = 'There was an error trying to send your message. Please try again later.';                  
            $mail->view();                   
            /* $mail->error = false;                  
            $mail->success = true;
            $mail->view();         */
            ?>
            
        </div>
    </div>
</div>