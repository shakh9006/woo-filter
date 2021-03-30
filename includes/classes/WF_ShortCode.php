<?php

namespace wf\Classes;

class WF_ShortCode {
    public static function init() {
        add_shortcode('wf-filter', [self::class, 'render_short_code']);
    }

    public static function render_short_code($params) {
        if ( isset( $params['id'] ) ) {
            $wf_filter = WF_Filter::find_one($params['id']);
            $fields    = $wf_filter->get_used();
            return wf_render_template(WF_PATH . '/templates/wf-front.php', ['fields' => $fields, 'id' => $params['id']]);
        }
    }
}