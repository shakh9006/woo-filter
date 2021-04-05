<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once WF_PATH .'/includes/functions.php';
require_once WF_PATH .'/includes/enqueue.php';

if ( is_admin() ) {
    require_once WF_PATH. '/includes/classes/WF_AdminMenu.php';
    require_once WF_PATH. '/includes/classes/WF_Settings_data.php';
    require_once WF_PATH .'/includes/install.php';
}

require_once WF_PATH. '/includes/classes/vendor/autoload.php';
require_once WF_PATH. '/includes/classes/WF_AjaxActions.php';
require_once WF_PATH. '/includes/classes/WF_Field.php';
require_once WF_PATH. '/includes/classes/WF_Filter.php';
require_once WF_PATH. '/includes/classes/WF_Filter_Fields_Relationships.php';
require_once WF_PATH. '/includes/classes/WF_ShortCode.php';
require_once WF_PATH. '/includes/init.php';
