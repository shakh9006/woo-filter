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
        wp_enqueue_style('wf-select-2', WF_URL . '/assets/dist/css/select2.min.css');
        wp_enqueue_style('wf-select-bootstrap-2', WF_URL . '/assets/dist/css/select2-bootstrap.css');

        wp_enqueue_script('wf-select-2', WF_URL . '/assets/dist/js/select2.min.js', ['jquery'], time(), true);
        wp_enqueue_script('vanilla-toast', WF_URL . '/assets/dist/js/vanilla-toast.min.js', [], time());
    }
}

function wp_wf_enqueue_scripts() {
    wp_enqueue_script("jquery");
    wp_enqueue_style( 'jquery-ui' );
    wp_enqueue_style('bootstrap', WF_URL . '/assets/dist/css/bootstrap.min.css');
    wp_enqueue_style('wf-front-styles', WF_URL . '/assets/dist/css/front-styles.css');
    wp_enqueue_style('wf-material', WF_URL . '/assets/dist/css/material.css');
    wp_enqueue_style('wf-material-styles', WF_URL . '/assets/dist/css/material-styles.css');
    wp_enqueue_style('wf-select-2', WF_URL . '/assets/dist/css/select2.min.css');
    wp_enqueue_style('wf-select-bootstrap-2', WF_URL . '/assets/dist/css/select2-bootstrap.css');

    wp_enqueue_script('wf-select-2', WF_URL . '/assets/dist/js/select2.min.js', ['jquery'], time(), true);
}

add_action('admin_enqueue_scripts', 'wf_enqueue_scripts_styles');
add_action('wp_enqueue_scripts', 'wp_wf_enqueue_scripts');