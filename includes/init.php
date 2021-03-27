<?php
/**
 * add ajax action and menu page
 */


add_action('init', function () {
    \wf\Classes\WF_ShortCode::init();
    if ( is_admin() ) {
        \wf\Classes\WF_AjaxActions::init();
        \wf\Classes\WF_AdminMenu::init();
    }

});


add_action( 'plugins_loaded', function () {
    add_action( 'vc_before_init', function () {
        require_once WF_PATH. '/includes/classes/widgets/WF_Load_Widget.php';
    }, 0);
});

wf\Classes\WF_Filter::init();
