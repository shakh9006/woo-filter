<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once WF_PATH .'/includes/functions.php';
require_once WF_PATH .'/includes/enqueue.php';
require_once WF_PATH . '/includes/classes/abstract/autoload.php';

if ( is_admin() ) {
    require_once WF_PATH. '/includes/classes/WF_AdminMenu.php';
    require_once WF_PATH. '/includes/classes/WF_AjaxActions.php';
    require_once WF_PATH. '/includes/classes/WF_Settings_data.php';
}

require_once WF_PATH .'/includes/init.php';