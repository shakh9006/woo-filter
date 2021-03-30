<?php

namespace wf\Classes;

/**
 * Class WF_Settings_data
 * @package wf\Classes
 */
class WF_Settings_data {

    /**
     * Delete Filter By ID
     * Send Json Data
     */
    public static function wf_delete_filter() {
        $result = [
            'message' => wf_text_domain('Permission denied.'),
            'success' => false,
            'status'  => 'error',
        ];

        if ( current_user_can('manage_options') ) {

            if ( isset($_POST['id']) ) {
                $post_id = $_POST['id'];
                wp_delete_post($post_id);
            }

            $result['message'] = wf_text_domain('Filter deleted successfully');
            $result['success'] = true;
            $result['status']  = 'success';
        }

        wp_send_json($result);
    }

    /**
     * Save Admin Data
     * Send Json Data
     */
    public static function wf_filter_list() {
        $result = [
            'message' => wf_text_domain('Permission denied.'),
            'success' => false,
            'status'  => 'error',
            'filters' => [],
        ];

        if ( current_user_can('manage_options') ) {

            $posts = get_posts([
                'post_type' =>  'wf_filter',
                'post_status' => 'publish',
                'numberposts' => -1,
            ]);

            if ( is_array($posts) || is_object($posts) ) {
                foreach ($posts as $post ) {
                    $result['filters'][] = [
                        'ID'         => wf_isset_helper($post, 'ID'),
                        'post_title' => wf_isset_helper($post, 'ID'),
                        'edit'       => admin_url('admin.php?page=woo-filter-settings&id='.wf_isset_helper($post, 'ID')),
                    ];
                }
            }

            $result['message'] = wf_text_domain('Filter lists got successfully');
            $result['success'] = true;
            $result['status']  = 'success';
        }

        wp_send_json($result);
    }

    /**
     * Save Admin Data
     * Send Json Data
     */
    public static function wf_save_settings() {
        $result = [
            'message' => wf_text_domain('Permission denied.'),
            'success' => false,
            'status'  => 'error',
        ];

        if ( current_user_can('manage_options') ) {

            $title     = wf_isset_helper($_POST, 'title', null);
            $post_id   = wf_isset_helper($_POST, 'id', null);
            $wf_filter = WF_Filter::wf_get_filter($post_id, $title);
            $wf_filter->saveOptions(wf_isset_helper($_POST, 'fields', []));

            $result['message'] = wf_text_domain('Data saved successfully');
            $result['success'] = true;
            $result['status']  = 'success';
        }

        wp_send_json($result);
    }

    /**
     * Admin Data Store
     * Send Json Data
     */
    public static function admin_get_data() {
        $result = [
            'message' => wf_text_domain('Permission denied.'),
            'success' => false,
            'status'  => 'error',
            'data'    => [],
        ];

        if ( current_user_can('manage_options') ) {

            $data = [
                'tabs'   => self::get_tabs(),
                'fields' => self::get_fields(),
                'types'  => self::get_types(),
//                'used'   => self::get_used(),

                'logic_options' => [
                    'and' => wf_text_domain('and'),
                    'or'  => wf_text_domain('or'),
                ],

                'view_options' => [
                    'default'  => wf_text_domain('default'),
                    'switch'   => wf_text_domain('switch'),
                ],
            ];

            $result['message'] = wf_text_domain('Admin data got successfully');
            $result['success'] = true;
            $result['status']  = 'success';
            $result['data']    = $data;
        }

        wp_send_json($result);
    }

    /**
     * Fields store
     * @return array
     */
    private static function get_fields() {
        $result = [
            'input' => [
                'label'        => '',
                'name'         => wf_text_domain('Input'),
                'placeholder'  => '',
                'description'  => '',
                'label_toggle' => true,
                'type'         => WF_Field::TYPE_INPUT,
                'tag'          => WF_Field::TAG_PRICE,
                'types'        => [
                    WF_Field::TAG_PRICE => wf_text_domain('Price'),
                ]
            ],

            'slider' => [
                'label'        => '',
                'name'         => wf_text_domain('Slider'),
                'description'  => '',
                'label_toggle' => true,
                'min'          => 0,
                'max'          => 100,
                'step'         => 1,
                'is_multi'     => false,
                'tag'          => WF_Field::TAG_PRICE,
                'type'         => WF_Field::TYPE_SLIDER,
                'types'        => [
                    WF_Field::TAG_PRICE => wf_text_domain('Price'),
                ]
            ],

            'select' => [
                'label'        => '',
                'name'         => wf_text_domain('Select'),
                'description'  => '',
                'label_toggle' => true,
                'options'      => [],
                'is_multi'     => false,
                'logic'        => 'or',
                'tag'          => WF_Field::TAG_PRICE,
                'type'         => WF_Field::TYPE_SELECT,
                'types'        => [
                    WF_Field::TAG_PRICE      => wf_text_domain('Price'),
                    WF_Field::TAG_CATEGORIES => wf_text_domain('Product Categories'),
                    WF_Field::TAG_TAGS       => wf_text_domain('Product Tags'),
                ],
            ],

            'radio' => [
                'label'        => '',
                'description'  => '',
                'options'      => [],
                'label_toggle' => true,
                'logic'        => 'or',
                'view_type'    => 'default',
                'tag'          => WF_Field::TAG_PRICE,
                'type'         => WF_Field::TYPE_RADIO_BUTTON,
                'name'         => wf_text_domain('Radio'),
                'types'        => [
                    WF_Field::TAG_PRICE      => wf_text_domain('Price'),
                    WF_Field::TAG_CATEGORIES => wf_text_domain('Product Categories'),
                    WF_Field::TAG_TAGS       => wf_text_domain('Product Tags'),
                ]
            ],

            'checkbox' => [
                'label'        => '',
                'name'         => wf_text_domain('Checkbox'),
                'description'  => '',
                'label_toggle' => true,
                'options'      => [],
                'logic'        => 'or',
                'view_type'    => 'default',
                'tag'          => WF_Field::TAG_PRICE,
                'type'         => WF_Field::TYPE_CHECKBOX,
                'types'        => [
                    WF_Field::TAG_PRICE      => wf_text_domain('Price'),
                    WF_Field::TAG_CATEGORIES => wf_text_domain('Product Categories'),
                    WF_Field::TAG_TAGS       => wf_text_domain('Product Tags'),
                ],
            ],


            'sort_by' => [
                'label'        => '',
                'name'         => wf_text_domain('Sort By'),
                'description'  => '',
                'label_toggle' => true,
                'tag'          => WF_Field::TAG_SORT_BY,
                'type'         => WF_Field::TYPE_SORT_BY,
                'sort_options' => [
                    ''
                ],
                'types'        => [
                    WF_Field::TAG_SORT_BY  => wf_text_domain('Sort By'),
                ]
            ],

            'color' => [
                'label'        => '',
                'name'         => wf_text_domain('Color'),
                'description'  => '',
                'label_toggle' => true,
                'colour'       => '#000000',
                'tooltip'      => false,
                'tag'          => WF_Field::TAG_COLOR,
                'type'         => WF_Field::TYPE_COLOR,
                'types'        => [
                    WF_Field::TAG_COLOR => wf_text_domain('Color'),
                ]
            ],

            'rating' => [
                'label'        => '',
                'name'         => wf_text_domain('Rating'),
                'description'  => '',
                'label_toggle' => true,
                'tag'          => WF_Field::TAG_RATING,
                'type'         => WF_Field::TYPE_RATING,
                'types'        => [
                    WF_Field::TAG_RATING => wf_text_domain('Rating'),
                ]
            ],
        ];

        return  $result;
    }

    private static function get_tabs() {
        return [
            'custom-fields' => [
                'title'     => wf_text_domain('Custom Fields'),
                'icon'      => 'icon--350',
                'component' => 'custom-fields',
            ],

            'settings' => [
                'title'     => wf_text_domain('Settings'),
                'icon'      => 'icon-adjust',
                'component' => 'settings-tab',
            ],

            'customize' => [
                'title'     => wf_text_domain('Customize'),
                'icon'      => 'icon-files',
                'component' => 'customize-tab',
            ]
        ];
    }

    private static function get_types() {
        return [
            WF_Field::TYPE_INPUT        => wf_text_domain('Input'),
            WF_Field::TYPE_SLIDER       => wf_text_domain('Slider'),
            WF_Field::TYPE_SELECT       => wf_text_domain('Select'),
            WF_Field::TYPE_CHECKBOX     => wf_text_domain('Checkbox'),
            WF_Field::TYPE_RATING       => wf_text_domain('Rating'),
            WF_Field::TYPE_COLOR        => wf_text_domain('Color'),
            WF_Field::TYPE_RADIO_BUTTON => wf_text_domain('Radio'),
            WF_Field::TYPE_SORT_BY      => wf_text_domain('Sort By'),
        ];
    }

    private static function get_used() {
        $post_id = wf_isset_helper($_GET, 'id');
        $wf_field = WF_Filter::find_one($post_id);
        return $wf_field->get_used();
    }
}