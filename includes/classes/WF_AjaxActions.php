<?php

namespace wf\Classes;

/**
 * Class WF_AjaxActions
 * @package wf\Classes
 */
class WF_AjaxActions {
    public static function addAction($tag, $function_to_add, $nopriv = false, $priority = 10, $accepted_args = 1) {
        add_action('wp_ajax_'.$tag, $function_to_add, $priority = 10, $accepted_args = 1);
        if ( $nopriv ) add_action('wp_ajax_nopriv_'.$tag, $function_to_add);
             return true;
    }

    public static function init() {
        self::addAction('wf_save_settings',     [ \wf\Classes\WF_Settings_data::class,  'wf_save_settings'],  true);
        self::addAction('wf_filter_front_data',     [ \wf\Classes\WF_Settings_data::class,  'wf_filter_front_data'],  true);
        self::addAction('wf_filter_update',     [ \wf\Classes\WF_Settings_data::class,  'wf_filter_update'],  true);

        if ( is_admin() ) {
            self::addAction('wf_get_settings_data', [ \wf\Classes\WF_Settings_data::class,  'admin_get_data'],  true);
            self::addAction('wf_filter_list', [ \wf\Classes\WF_Settings_data::class,  'wf_filter_list'],  true);
            self::addAction('wf_delete_filter', [ \wf\Classes\WF_Settings_data::class,  'wf_delete_filter'],  true);
        }
    }
}