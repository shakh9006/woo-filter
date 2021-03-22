<?php

namespace wf\Classes;

/**
 * Class WFAjaxActions
 * @package wf\Classes
 */
class WFAjaxActions {
    public static function addAction($tag, $function_to_add, $nopriv = false, $priority = 10, $accepted_args = 1) {
        add_action('wp_ajax_'.$tag, $function_to_add, $priority = 10, $accepted_args = 1);
        if ( $nopriv ) add_action('wp_ajax_nopriv_'.$tag, $function_to_add);
        return true;
    }

    public static function init() {
        // Some actions

        if (is_admin()) {
            // Some actions for admin
        }
    }
}