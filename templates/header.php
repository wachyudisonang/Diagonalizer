<?php
    
?>

<header id="headers" class="banner navbar navbar-big navbar-fixed-top" role="banner">
    <div class="container">

        <nav class="nav-main collapse navbar-collapse" role="navigation">
            <?php
                if (has_nav_menu('primary_navigation')) :
                wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav nav-pills'));
                endif;
            ?>
        </nav>

    </div>
</header>
