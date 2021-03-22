<?php

function wf_enqueue_scripts_styles() {
    $v = WF_VERSION;
    wp_enqueue_style( 'jquery-ui' );
    wp_enqueue_style('wf-fonts', 'https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap');

    wp_enqueue_script("jquery");
    wp_enqueue_style('font-awesome', WF_URL . '/assets/dist/libs/css/font-awesome.min.css');
    wp_enqueue_style('bootstrap', WF_URL . '/assets/dist/libs/css/bootstrap.min.css');
}

add_action('admin_enqueue_scripts', 'wf_enqueue_scripts_styles');