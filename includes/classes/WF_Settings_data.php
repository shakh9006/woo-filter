<?php

namespace wf\Classes;

/**
 * Class WF_Settings_data
 * @package wf\Classes
 */
class WF_Settings_data {

    public static function wf_filter_front_data() {
        $result = [
            'message' => wf_text_domain('No Filter with this id'),
            'success' => false,
            'data'    => [],
        ];

        if ( ! empty(  $_GET['id'] ) ) {
            $data             = [];
            $filter_id        = $_GET['id'];
            $wf_filter        = WF_Filter::find_one($filter_id);
            $data['fields']   = $wf_filter->get_used();
            $data['settings'] = $wf_filter->render_settings();
            $data['products'] = $wf_filter->get_products();
                wf_write_log($data['products']);
            /**
             * Woo Data
             */
            $data['product_categories'] = self::get_woo_categories();
            $data['product_tags']       = self::get_tags();
            $data['sort_by']            = self::get_sort_by_data();

            $result['message'] = wf_text_domain('Filter Data got successfully');
            $result['success'] = true;
            $result['data']    = $data;
        }

        wp_send_json($result);
    }

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
                'post_type'   =>  'wf_filter',
                'post_status' => 'publish',
                'numberposts' => -1,
            ]);

            if ( is_array($posts) || is_object($posts) ) {
                foreach ($posts as $post ) {
                    $result['filters'][] = [
                        'ID'         => wf_isset_helper($post, 'ID'),
                        'post_title' => wf_isset_helper($post, 'post_title'),
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

            if ( ! empty( $wf_filter ) ) {
                $wf_filter->saveOptions(wf_isset_helper($_POST, 'fields', []));
                $wf_filter->saveSettings(wf_isset_helper($_POST, 'settings', self::get_filter_settings_data()));
                $result['filter_id'] = $wf_filter->ID;
            }

            $result['status']    = 'success';
            $result['success']   = true;
            $result['message']   = wf_text_domain('Data saved successfully');
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

            $title    = '';
            $settings = self::get_filter_settings_data();
            if ( !empty($_GET['id']) ) {
                /**
                 * @var $filter WF_Filter
                 */
                $filter   = WF_Filter::find_one($_GET['id']);
                $title    = $filter->post_title;
                $settings = $filter->render_settings();
            }

            $data = [
                'title'     => $title,
                'filter_id' => wf_isset_helper($_GET, 'id', null),
                'tabs'      => self::get_tabs(),
                'fields'    => self::get_fields(),
                'types'     => self::get_types(),
                'used'      => self::get_used(),

                'logic_options' => [
                    'and' => wf_text_domain('and'),
                    'or'  => wf_text_domain('or'),
                ],

                'view_options' => [
                    'default'   => wf_text_domain('default'),
                    'switch'    => wf_text_domain('switch'),
                ],

                'tags'          => self::get_tags(),
                'categories'    => self::get_woo_categories(),
                'sort_type'     => self::get_sort_by_data(),
                'order_list'    => self::wf_order_list(),
                'order_by_list' => self::wf_order_by_list(),
                'position_list' => self::wf_position_list(),
                'ajax_list'     => self::wf_ajax_list(),

                'filter_settings' => $settings // current filter_settings
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
        $sort_by_options = [];
        foreach (self::get_sort_by_data() as $sort)
            $sort_by_options[] = wf_isset_helper($sort, 'id');

        $result = [
            'input' => [
                'title'        => '',
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
                'title'        => '',
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
                'title'        => '',
                'name'         => wf_text_domain('Select'),
                'description'  => '',
                'label_toggle' => true,
                'is_multi'     => false,
                'logic'        => 'or',
                'tag'          => WF_Field::TAG_CATEGORIES,
                'type'         => WF_Field::TYPE_SELECT,
                'used'         => [],
                'types'        => [
                    WF_Field::TAG_CATEGORIES => wf_text_domain('Product Categories'),
                    WF_Field::TAG_TAGS       => wf_text_domain('Product Tags'),
                ],
            ],

            'radio' => [
                'title'        => '',
                'description'  => '',
                'label_toggle' => true,
                'tag'          => WF_Field::TAG_CATEGORIES,
                'type'         => WF_Field::TYPE_RADIO_BUTTON,
                'used'         => [],
                'name'         => wf_text_domain('Radio'),
                'types'        => [
                    WF_Field::TAG_CATEGORIES => wf_text_domain('Product Categories'),
                    WF_Field::TAG_TAGS       => wf_text_domain('Product Tags'),
                ]
            ],

            'checkbox' => [
                'title'        => '',
                'name'         => wf_text_domain('Checkbox'),
                'description'  => '',
                'label_toggle' => true,
                'logic'        => 'or',
                'view_type'    => 'default',
                'used'         => [],
                'tag'          => WF_Field::TAG_CATEGORIES,
                'type'         => WF_Field::TYPE_CHECKBOX,
                'types'        => [
                    WF_Field::TAG_CATEGORIES => wf_text_domain('Product Categories'),
                    WF_Field::TAG_TAGS       => wf_text_domain('Product Tags'),
                ],
            ],


            'sort_by' => [
                'title'        => '',
                'name'         => wf_text_domain('Sort By'),
                'description'  => '',
                'label_toggle' => true,
                'used'         => $sort_by_options,
                'tag'          => WF_Field::TAG_SORT_BY,
                'type'         => WF_Field::TYPE_SORT_BY,
                'types'        => [
                    WF_Field::TAG_SORT_BY  => wf_text_domain('Sort By'),
                ]
            ],

//            'color' => [
//                'title'        => '',
//                'name'         => wf_text_domain('Color'),
//                'description'  => '',
//                'label_toggle' => true,
//                'colour'       => '#000000',
//                'tooltip'      => false,
//                'tag'          => WF_Field::TAG_COLOR,
//                'type'         => WF_Field::TYPE_COLOR,
//                'types'        => [
//                    WF_Field::TAG_COLOR => wf_text_domain('Color'),
//                ]
//            ],
//
//            'rating' => [
//                'title'        => '',
//                'name'         => wf_text_domain('Rating'),
//                'description'  => '',
//                'label_toggle' => true,
//                'tag'          => WF_Field::TAG_RATING,
//                'type'         => WF_Field::TYPE_RATING,
//                'types'        => [
//                    WF_Field::TAG_RATING => wf_text_domain('Rating'),
//                ]
//            ],
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
//            WF_Field::TYPE_RATING       => wf_text_domain('Rating'),
//            WF_Field::TYPE_COLOR        => wf_text_domain('Color'),
            WF_Field::TYPE_RADIO_BUTTON => wf_text_domain('Radio'),
            WF_Field::TYPE_SORT_BY      => wf_text_domain('Sort By'),
        ];
    }

    private static function get_used() {
        $post_id = wf_isset_helper($_GET, 'id');
        if ( !empty( $post_id ) ) {
            $wf_field = WF_Filter::find_one($post_id);
            return $wf_field->get_used();
        }

        return [];
    }

    public static function get_woo_categories() {
        $taxonomy     = 'product_cat';
        $order_by     = 'name';
        $show_count   = 0;
        $pad_counts   = 0;
        $hierarchical = 1;
        $title        = '';
        $empty        = 0;

        $args = array(
            'taxonomy'     => $taxonomy,
            'orderby'      => $order_by,
            'show_count'   => $show_count,
            'pad_counts'   => $pad_counts,
            'hierarchical' => $hierarchical,
            'title_li'     => $title,
            'hide_empty'   => $empty
        );

        $data           = [];
        $all_categories = get_categories( $args );

        if ( !empty( $all_categories ) ) {
            foreach ( $all_categories as $category )
                $data[] = ['id' => $category->term_id, 'text' => $category->name];
        }

        return $data;
    }

    public static function get_tags() {
        $terms = get_terms( 'product_tag' );
        $term_array = array();

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
            foreach ( $terms as $term ) {
                $term_array[] = ['id' => $term->term_id, 'text' => $term->name];
            }
        }

        return $term_array;
    }

    public static function get_sort_by_data() {
        return [
            [ 'id' => 'default',    'text' => wf_text_domain('Default')],
            [ 'id' => 'popularity', 'text' => wf_text_domain('Popularity')],
            [ 'id' => 'rating',     'text' => wf_text_domain('Rating')],
            [ 'id' => 'date',       'text' => wf_text_domain('Newness')],
            [ 'id' => 'price',      'text' => wf_text_domain('Price: low to high')],
            [ 'id' => 'price-desc', 'text' => wf_text_domain('Price: high to low')],
            [ 'id' => 'rand',       'text' => wf_text_domain('Random')],
            [ 'id' => 'title',      'text' => wf_text_domain('Name A to Z')],
            [ 'id' => 'title-desc', 'text' => wf_text_domain('Name Z to A')],
        ];
    }

    public static function wf_order_by_list() {
        return [
            ['id' => 'id',            'text' => 'ID'],
            ['id' => 'title',         'text' => 'title'],
            ['id' => 'author',        'text' => 'author'],
            ['id' => 'name',          'text' => 'name'],
            ['id' => 'date',          'text' => 'date'],
            ['id' => 'rand',          'text' => 'rand'],
            ['id' => 'comment_count', 'text' => 'comment_count'],
        ];
    }

    public static function wf_order_list() {
        return [
            ['id' => 'asc', 'text' => 'ASC'],
            ['id' => 'desc', 'text' => 'DESC'],
        ];
    }

    public static function wf_position_list() {
        return [
            [ 'id' => 'left',   'text' => wf_text_domain('Left') ],
            [ 'id' => 'right',  'text' => wf_text_domain('Right') ],
            [ 'id' => 'top',    'text' => wf_text_domain('Top') ],
            [ 'id' => 'bottom', 'text' => wf_text_domain('Bottom') ],
        ];
    }

    public static function wf_ajax_list() {
        return [
            ['id' => 'yes', 'text' => wf_text_domain('Yes')],
            ['id' => 'no',  'text' => wf_text_domain('No')],
        ];
    }

    public static function get_filter_settings_data() {
        return [
            'order'    => 'asc',
            'order_by' => 'id',
            'count'    => -1,
            'position' => 'left',
            'is_ajax'  => 'yes',
        ];
    }
}