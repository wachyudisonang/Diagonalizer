<?php
/**
 * Header bar section.
 *
 */

$nav_pos = ( diagonalizer('nav_pos')==0 )?'navbar-static-top':'navbar-fixed-top';
include FUNCTIONS_DIR . 'header_functions.php';

?>

<header id="headers" class="banner navbar <?php echo $nav_pos; ?>" role="banner">
    <div class="wrapper navbar-default">
        <div class="sub-wrapper">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php diagonalizer_logo(); ?>
            </div>

            <nav class="collapse navbar-collapse" role="navigation">
            <?php
                if (has_nav_menu('primary_navigation')) :
                wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav'));
                endif;
            ?>
            </nav>
        </div>
    </div>
</header>
