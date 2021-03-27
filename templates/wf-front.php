<?php
$v = WF_VERSION;
wp_enqueue_script('wf-admin-app', WF_URL . '/assets/dist/js/script.js', [], $v, true);
$data = [
    'ajaxUrl'   => admin_url('admin-ajax.php'),
    'ajaxNonce' => wp_create_nonce( 'wf-ajax-nonce' ),
    'fields'    => isset($fields) ? $fields : [],
    'id'        => isset($id) ? $id : null,
];

$paged = isset( $_REQUEST['paged'] ) ? intval( $_REQUEST['paged'] ) : get_query_var( 'paged' );

if ( $paged < 1 ) {
    $paged = 1;
}

$meta_query    = WC()->query->get_meta_query();
$query_args    = array(
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'ignore_sticky_posts' => 1,
    'orderby'             => '',
    'order'               => 'DESC',
    'posts_per_page'      => 10,
    'meta_query'          => $meta_query,
    'tax_query'           => WC()->query->get_tax_query(),
    'paged'               => $paged
);

wp_add_inline_script('wf-admin-app', "var wf_front_data = JSON.parse('". wf_convert_content(json_encode($data)) ."');", 'before');
?>

<div id="wf-wrapper">
    <main-filter></main-filter>
</div>
