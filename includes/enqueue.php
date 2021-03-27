<?php

function wf_enqueue_scripts_styles() {
    $pages = ['woo-filter-settings'];
    $page  = wf_isset_helper($_GET, 'page');

    if ( in_array($page, $pages) ) {
        wp_enqueue_script("jquery");
        wp_enqueue_style( 'jquery-ui' );
        wp_enqueue_style('wf-fonts', 'https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap');

        wp_enqueue_style('font-awesome', WF_URL . '/assets/dist/css/icon.css');
        wp_enqueue_style('bootstrap', WF_URL . '/assets/dist/css/bootstrap.min.css');
        wp_enqueue_style('admin-settings', WF_URL . '/assets/dist/css/admin-settings.css');

        wp_enqueue_script('vanilla-toast', WF_URL . '/assets/dist/js/vanilla-toast.min.js', [], time());
    }
}

add_action('admin_enqueue_scripts', 'wf_enqueue_scripts_styles');