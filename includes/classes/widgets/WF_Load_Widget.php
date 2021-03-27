<?php

namespace wf\Classes\widget;

$vendor = new WF_VC();

add_action('vc_after_set_mode', array(
    $vendor,
    'load',
));


class WF_VC
{
    public function load()
    {
        vc_lean_map('wf-filter', array(
            $this,
            'wf_vc_elements',
        ));
    }

    function wf_vc_elements()
    {
        $args = array(
            'post_type' => 'wf_filter',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        $data = new \WP_Query($args);
        $data = $data->posts;

        $lists = array(esc_html__('Keep empty', 'wf-filter') => 'select');

        if (count($data) > 0)
            foreach ($data as $value) {
                $title = !empty($value->post_title) ? $value->post_title : 'Empty';
                $lists[$title] = $value->ID;
            }
        else
            $lists = array(esc_html__('No filter was found', 'wf-filter') => 'select');

        return array(
            'base' => "woo_filter",
            'name' => esc_html__('Woo Filter', 'wf-filter'),
            'icon' => 'icon-wpb-woocommerce',
            'category' => esc_html__('Content', 'wf-filter'),
            'description' => esc_html__('Place Woo Filter', 'wf-filter'),
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Select Filter', 'wf-filter'),
                    'param_name' => 'id',
                    'value' => $lists,
                    'save_always' => true,
                    'description' => '',
                ),
            )
        );
    }
}