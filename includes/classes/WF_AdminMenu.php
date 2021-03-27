<?php
namespace wf\Classes;

/**
 * Class WF_AdminMenu
 * @package wf\Classes
 */
class WF_AdminMenu {

    public function __construct()
    {
        add_action('admin_menu', [$this, 'wf_settings_menu'], 20);
    }

    /**
     * @return WF_AdminMenu
     */
    public static function init() {
       return new WF_AdminMenu();
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