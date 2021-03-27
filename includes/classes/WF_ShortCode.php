<?php

namespace wf\Classes;

class WF_ShortCode {
    public static function init() {
        add_shortcode('wf-filter', [self::class, 'render_short_code']);
    }

    public static function render_short_code($params) {
        if ( isset( $params['id'] ) ) {
            $fields = WF_Filter::find_one($params['id']);
            return wf_render_template(WF_PATH . '/templates/wf-front.php', ['fields' => $fields, 'id' => $params['id']]);
        }
    }
}