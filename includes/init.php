<?php
/**
 * add ajax action and menu page
 */

if ( is_admin() ) {
    add_action('init', function (){
//    \wf\Classes\WFAdminMenu::init();

    });

    add_action('init', function (){
        \wf\Classes\WFAdminMenu::init();
    });
}

