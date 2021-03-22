<?php
namespace wf\Classes;

/**
 * Class WFAdminMenu
 * @package wf\Classes
 */
class WFAdminMenu {

    public function __construct()
    {
        add_action('admin_menu', [$this, 'wf_settings_menu'], 20);
    }

    /**
     * @return WFAdminMenu
     */
    public static function init() {
       return new WFAdminMenu();
    }

    public function wf_settings_menu() {
        add_menu_page(
            'WF Settings',
            'WF Settings',
            'manage_options',
            'woo-filter-settings',
            [$this, 'wf_render_settings'],
            'dashicons-index-card',
            5
        );
    }

    public static function wf_render_settings() {
        wf_render_template(WF_PATH . '/includes/views/settings-page.php', [], true);
    }
}