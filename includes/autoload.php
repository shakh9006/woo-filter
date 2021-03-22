<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once WF_PATH .'/includes/functions.php';
require_once WF_PATH .'/includes/enqueue.php';

if ( is_admin() ) {
    require_once WF_PATH. '/includes/classes/WFAdminMenu.php';
}

require_once WF_PATH .'/includes/init.php';