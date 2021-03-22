<?php
/**
 * @param $file
 * @param array $args
 * @param null $show
 *
 * @return string
 */
function wf_render_template($file, $args = array(), $show = null)
{
    if (!file_exists($file)) {
        return '';
    }

    if (is_array($args)) {
        extract($args);
    }

    ob_start();
    include $file;

    if (!$show)
        return ob_get_clean();
    echo ob_get_clean();
}

/**
 * @param $log
 */
function wf_write_log($log) {
    if (true === WP_DEBUG) {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

/**
 * @param $content
 *
 * @return string
 */
function wf_convert_content($content)
{
    return trim(preg_replace('/\s\s+/', ' ', addslashes($content)));
}

/**
 * @param $other_data
 * @param $deps
 * @return void
 */
function wf_load_admin_scripts($other_data = [], $deps = []) {
    $v = WF_VERSION;
    wp_enqueue_script('wf-admin-app', WF_URL . '/assets/dist/js/admin.js', $deps, $v, true);

    $data = [
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'ajaxNonce' => wp_create_nonce( 'wf-ajax-nonce' ),
    ];

    if ( is_array($other_data) )
        $data = array_merge($data, $other_data);

    wp_add_inline_script('wf-admin-app', "var wf_settings_data = JSON.parse('". wf_convert_content(json_encode($data)) ."');", 'before');
}