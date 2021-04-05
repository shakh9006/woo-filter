<?php
$v = WF_VERSION;
wp_enqueue_script('wf-admin-app', WF_URL . '/assets/dist/js/script.js', [], $v, true);
$data = [
    'ajaxUrl'   => admin_url('admin-ajax.php'),
    'ajaxNonce' => wp_create_nonce( 'wf-ajax-nonce' ),
    'id'        => isset($id) ? $id : null,
];


wp_add_inline_script('wf-admin-app', "var wf_front_data = JSON.parse('". wf_convert_content(json_encode($data)) ."');", 'before');
?>

<div id="wf-wrapper">
    <main-filter v-if="getStateByName('ajaxUrl')"></main-filter>
</div>
