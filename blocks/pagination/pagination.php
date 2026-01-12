<?php

/**
 * Block Single - pagination
 */

$settings = get_field("settings");

$data = block_start("pagination", $block, $settings, "section-white");
$id = $data["id"];
$color_schema = $data["color_schema"];
?>
<div class="c-section l-section-padding <?= $color_schema; ?> " id="pagination-<?php echo esc_attr($block["id"]); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="u-nav">
                    <?php
                    $previous = get_previous_post(false);
                    $url_prev = get_permalink($previous);
                    if($previous):
                    ?>
                    <div class="nav-previous ml-0 mr-auto">
                        <a class="std-btn-tertiary btn--small " href="<?= $url_prev; ?>"> <span class="icon mr-1">&#171;</span>Previous </a>
                    </div>
                    <?php 
                    endif;
                    ?>
                    
                    <?php
                    $next = get_next_post(false);
                    $url_next = get_permalink($next);
                    if ($next) :
                    ?>
                        <div class="nav-next mr-0">
                            <a class="std-btn-tertiary btn--small " href="<?= $url_next; ?>">Next <span class="icon ml-1">&#187;</span> </a>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>