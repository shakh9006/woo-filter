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