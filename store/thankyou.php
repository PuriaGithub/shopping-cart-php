<?php
// start the session
session_start();
include '../functions.php'; ?>

<?php
// header_section($activate_carousel, $title);
echo header_section(false, 'Thank You - Retro Records'); ?>

<main>
    <div class="content">
        <section class="col-full">
            <p class="text-center mp-3">
                <i class="fa fa-shopping-cart fa-3x"></i>
            </p>
            <h2 class="text-center">Thank you for Shopping with us !</h2>
        </section>
    </div>
</main>

<?php echo footer_section() ?>