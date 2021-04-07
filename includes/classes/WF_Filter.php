<?php

namespace wf\Classes;

use wf\Classes\Vendor\BaseModel;

/**
 * Class WF_Filter
 * @package wf\Classes
 */
class WF_Filter extends BaseModel {
    private $query_args;
    private $taxonomies = [];
    private $params = [];
    const STATUS_PUBLISH = 'publish';
    const STATUS_PENDING = 'pending';
    const STATUS_PRIVATE = 'private';
    const STATUS_DRAFT = 'draft';

    public static function init() {
        add_filter('posts_clauses_request', array(self::class, 'posts_clauses'), 10, 2);
        add_action('after_delete_post', [self::class, 'after_delete_post']);
    }

    public static function get_table() {
        global $wpdb;
        return $wpdb->prefix . 'posts';
    }

    public $ID;
    public $post_author;
    public $post_date;
    public $post_date_gmt;
    public $post_content;
    public $post_title;
    public $post_excerpt;
    public $post_status;
    public $comment_status;
    public $ping_status;
    public $post_password;
    public $post_name;
    public $to_ping;
    public $post_modified;
    public $post_modified_gmt;
    public $post_content_filtered;
    public $post_parent;
    public $guid;
    public $menu_order;
    public $post_type;
    public $post_mime_type;
    public $comment_count;
    public $post;

    protected $fillable = [
        'ID',
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count'
    ];
    
    public static function get_searchable_fields() {
        return [
            'ID',
            'post_author',
            'post_date',
            'post_date_gmt',
            'post_content',
            'post_title',
            'post_excerpt',
            'post_status',
            'comment_status',
            'ping_status',
            'post_password',
            'post_name',
            'to_ping',
            'post_modified',
            'post_modified_gmt',
            'post_content_filtered',
            'post_parent',
            'guid',
            'menu_order',
            'post_type',
            'post_mime_type',
            'comment_count',
        ];
    }

    /**
     * @param $clauses
     * @param $queries
     *
     * @return mixed
     */
    public static function posts_clauses($clauses, $queries)
    {
        if ( $queries->get('post_type') == 'listing' ) {
            if ($wf_query = $queries->get('wf_query') OR (isset($queries->query['wf_query']) AND $wf_query = $queries->query['wf_query'])) {

                if ( isset($wf_query['fields']) AND !empty($wf_query['fields']) )
                    $clauses['fields'] .= " , ".  $wf_query['fields'];

                if ( isset($wf_query['join']) )
                    $clauses['join'] .= $wf_query['join'];

                if ( isset($wf_query['where']) )
                    $clauses['where'] .= $wf_query['where'];

                if ( isset($wf_query['orderby']) AND !empty($wf_query['orderby']) )
                    $clauses['orderby'] = $wf_query['orderby'];

                if ( isset($wf_query['groupby']) AND !empty($wf_query['groupby']) )
                    $clauses['groupby'] = $wf_query['groupby'];
            }
        }
        return $clauses;
    }

    /**
     * @param $postid
     */
    public static function after_delete_post($postid)
    {
        WF_Filter_Fields_Relationships::query()->where('filter_id', $postid)->delete();
    }

    /**
     * @param $id
     * @param $title
     * @return false|BaseModel|null
     */
    public static function wf_get_filter($id, $title) {
        $post_id   = null;
        $wf_filter = null;
        if ( empty($id) || !($wf_filter = WF_Filter::find_one($id)) ) {
            $post_data = array(
                'post_title'   => sanitize_text_field($title),
                'post_content' => '',
                'post_status'  => 'publish',
                'post_type'    => 'wf_filter'
            );
            $post_id   = wp_insert_post($post_data);
            $wf_filter = WF_Filter::find_one($post_id);
        } else {
            $wf_post = array(
                'ID'           => $id,
                'post_title'   => sanitize_text_field($title),
            );
            wp_update_post( $wf_post );
        }

        return $wf_filter;
    }

    /**
     * @param $options array
     */
    public function saveOptions($options) {
        $listingAttributeRelationships = WF_Filter_Fields_Relationships::query()
            ->where('filter_id', $this->ID)
            ->find();

        foreach ($listingAttributeRelationships as $item) {
            $field_id = $item->field_id;
            WF_Field::find_one($field_id)->delete();
            $item->delete();
        }


        foreach ($options as $key => $value) {
            $field    = WF_Field::create($value['save_data'])->save();
            $field->saveMeta(wf_isset_helper($value, 'meta', []));
            WF_Filter_Fields_Relationships::create([
                'filter_id' => $this->ID,
                'field_id'  => $field->id,
            ])->save();
        }
    }

    public function get_used() {
        $filters = WF_Filter_Fields_Relationships::query()
            ->where('filter_id', $this->ID)
            ->find();

        $result = [];
        foreach ($filters as $filter) {
            $field = WF_Field::find_one($filter->field_id);
            $meta  = $field->get_field_meta_data();

            if ( ! empty($field) ) {
                $data = [
                    'id'           => $field->id,
                    'tag'          => $field->tag,
                    'type'         => $field->type,
                    'name'         => $field->name,
                    'title'        => $field->title,
                    'label_toggle' => $field->label_toggle,
                    'description'  => $field->description,
                    'used'         => isset( $meta['used'] ) ? array_values($meta['used']) : [],
                ];

                if ( ! empty( $meta ) )   {
                    foreach ($meta as $k => $v) {
                        if ( $k !== 'used' )
                            $data[$k] = $v;
                    }
                }

                $result[] = $data;
            }
        }

        return $result;
    }

    public function saveSettings($data) {
        update_post_meta($this->ID, 'filter_settings_data', wf_sanitize_array($data));
    }

    public function render_settings() {
        $data = get_post_meta($this->ID, 'filter_settings_data', true);
        return ! empty( $data ) ? $data : WF_Settings_data::get_filter_settings_data();
    }

    public function get_products($param = '', $relations = '') {
        $paged = isset( $_REQUEST['paged'] ) ? intval( $_REQUEST['paged'] ) : get_query_var( 'paged' );
        if ( $paged < 1 ) {
            $paged = 1;
        }

        $meta_query       = WC()->query->get_meta_query();
        $this->query_args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby'             => '',
            'order'               => 'DESC',
            'posts_per_page'      => -1,
            'meta_query'          => $meta_query,
            'tax_query'           => WC()->query->get_tax_query(),
            'paged'               => $paged
        );

        $this->taxonomies = [];
        if ( is_array($param) ) {
            foreach ($param as $k => $v) {
                $this->params[$k] = $v;
                if ( in_array($k, ['product_cat', 'product_tag']) ) {
                    $v = urldecode($v);
                    $v = explode(",", $v);
                    $relation = wf_isset_helper($relations, $k, 'IN');
                    $relation = strtolower($relation) === 'and' ? 'AND' : 'IN';
                    $this->taxonomies[] = [
                        'taxonomy' => $k,
                        'field'    => 'slug',
                        'terms'    => $v,
                        'operator' => $relation,
                        'include_children' => 1,
                    ];
                }
            }
        }

        $this->taxonomies[] = [
            'taxonomy' => 'product_visibility',
            'field'    => 'slug',
            'terms'    => ['exclude-from-catalog'],
            'operator' => 'NOT IN'
        ];

        $this->query_args['wf_filter_active'] = true;


        $columns = apply_filters( 'loop_shop_columns', 4 );
        if ( isset( $this->render_settings()['column'] ) )
            $columns = $this->render_settings()['column'];

        wc_set_loop_prop( 'columns', $columns );

        add_filter( 'pre_get_posts',   [$this, 'wf_pre_get_posts'], 99999, 1 );
        add_filter( 'parse_tax_query', [$this, 'wf_parse_tax_query'], 99999, 1 );

        $products = new \WP_Query( $this->query_args );

        ob_start();
        woocommerce_product_loop_start();
        $loop_start = ob_get_clean();

        ob_start();

       if ( $products->have_posts() ) {
           while ( $products->have_posts() )  {
               $products->the_post();
               do_action( 'woocommerce_shop_loop' );
               wc_get_template( 'content-product.php' );
           }

           $products = ob_get_clean();

           ob_start();
           woocommerce_product_loop_end();
           $loop_end = ob_get_clean();

           return $loop_start . $products . $loop_end;
       } else {
           ob_start();

           wc_get_template( 'loop/loop-start.php' );

           do_action( 'woocommerce_no_products_found' );

           wc_get_template( 'loop/loop-end.php' );

           return ob_get_clean();
       }
    }

    public function wf_pre_get_posts($query) {
        if ( $query->query_vars['wf_filter_active'] ) {
            // For order by
            $curr_args = [];
            if (  !empty( $this->params['orderby'] ) ) {
                $_order = isset($this->render_settings()['order']) ? $this->render_settings()['order'] : '';
                $default_order = apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
                $orderby_value = isset( $this->params['orderby'] ) ? wc_clean( (string) $this->params['orderby'] ) : $default_order;
                $orderby_value = explode( '-', $orderby_value );
                $orderby       = esc_attr( $orderby_value[0] );

                if ( !empty( $orderby_value[1]) &&  $orderby_value[1] === 'desc' ) {
                    $order = 'DESC';
                } else {
                    $order = $_order ? ( $_order == 'DESC' ? 'DESC' : 'ASC' ) : ( isset( $orderby_value[1] ) && !empty( $orderby_value[1] ) ? $orderby_value[1] : '' );

                }
                $orderby = strtolower( $orderby );
                $order   = strtoupper( $order );

                switch ( $orderby ) {
                    case 'rand' :
                        $curr_args['orderby']  = 'rand';
                        break;
                    case 'date' :
                    case 'date ID' :
                        $curr_args['orderby']  = 'date';
                        $curr_args['order']    = $order == 'ASC' ? 'ASC' : 'DESC';
                        break;
                    case 'price' :
                        if ( 'DESC' === $order ) {
                            add_filter( 'posts_clauses', array( WC()->query, 'order_by_price_desc_post_clauses' ) );
                        } else {
                            add_filter( 'posts_clauses', array( WC()->query, 'order_by_price_asc_post_clauses' ) );
                        }
                        break;
                    case 'popularity' :
                        $curr_args['meta_key'] = 'total_sales';
                        add_filter( 'posts_clauses', array( WC()->query, 'order_by_popularity_post_clauses' ) );
                        break;
                    case 'rating' :
                        $curr_args['orderby']  = array( "meta_value_num" => "DESC", "ID" => "ASC" );
                        $curr_args['order']  = "ASC";
                        $curr_args['meta_key'] = '_wc_average_rating';
                        break;
                    case 'title' :
                        $curr_args['orderby']  = 'title';
                        $curr_args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
                        break;
                    case 'menu_order' :
                    case 'menu_order title' :
                    case '' :
                        $curr_args['orderby'] = 'menu_order title';
                        $curr_args['order'] = $order == 'DESC' ? 'DESC' : 'ASC';
                        break;
                    case 'comment_count' :
                        $curr_args['orderby'] = 'comment_count';
                        $curr_args['order']   = $order == 'ASC' ? 'ASC' : 'DESC';
                        break;
                    default :
                        $curr_args['orderby'] = $orderby;
                        $curr_args['order']   = $order == 'ASC' ? 'ASC' : 'DESC';
                        break;
                }
            }

            //			 price filter
            if (  has_filter( 'post_clauses', array( 'WC_Query', 'price_filter_post_clauses') ) === false ) {
                add_filter( 'posts_clauses', [$this, 'price_filter_post_clauses'], 10, 2 );
            }

            foreach ( $curr_args as $k => $v ) {
                switch( $k ) {
                    case 'post__in' :
                        $v = array_unique( $v );
                        $postIn = isset( $query->query_vars[$k] ) && !empty( $query->query_vars[$k] ) ? $query->query_vars[$k] : array();
                        $ins = ( empty( $postIn ) ? $v : array_intersect( $postIn, $v ) );
                        $query->set( $k, $ins );
                        break;
                    default:
                        $query->set( $k, $v );
                        break;
                }
            }
        }
    }

    public function price_filter_post_clauses($args, $wp_query) {
        $prices = $this->get_prices( $wp_query->query_vars );
        if ( empty( $prices['max_price'] ) && empty( $prices['min_price'] ) ) {
            return $args;
        }

        $current_min_price = isset( $prices['min_price'] ) ? floatval( wp_unslash( $prices['min_price'] ) ) : 0;
        $current_max_price = isset( $prices['max_price'] ) ? floatval( wp_unslash( $prices['max_price'] ) ) : PHP_INT_MAX;

        if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
            $tax_class = apply_filters( 'woocommerce_price_filter_widget_tax_class', '' );
            $tax_rates = \WC_Tax::get_rates( $tax_class );

            if ( $tax_rates ) {
                $current_min_price -= \WC_Tax::get_tax_total( \WC_Tax::calc_inclusive_tax( $current_min_price, $tax_rates ) );
                $current_max_price -= \WC_Tax::get_tax_total( \WC_Tax::calc_inclusive_tax( $current_max_price, $tax_rates ) );
            }
        }

        global $wpdb;

        $args['join']   = $this->append_product_sorting_table_join( $args['join'] );
        $args['where'] .= $wpdb->prepare(
            ' AND wc_product_meta_lookup.min_price >= %f AND wc_product_meta_lookup.max_price <= %f ',
            $current_min_price,
            $current_max_price
        );
        return $args;
    }

    public function append_product_sorting_table_join( $sql ) {
        global $wpdb;

        if ( !strstr( $sql, 'wc_product_meta_lookup' ) ) {
            $sql .= " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";
        }
        return $sql;
    }

    public function wf_parse_tax_query($query) {
        if ( $query->query_vars['wf_filter_active'] ) {
            $this->taxonomies['relation'] = 'AND';
            $now = !empty( $query->tax_query->queries ) ? $query->tax_query->queries : array();

            if ( !empty( $now ) ) {
                $query->query_vars['tax_query'] = $query->tax_query->queries = array_unique( array_merge( $this->taxonomies, $now ), SORT_REGULAR );
            }
            else {
                $query->query_vars['tax_query'] = $query->tax_query->queries = array_unique( $this->taxonomies, SORT_REGULAR );
            };
        }
    }

    public function get_prices( $query ) {
        
        $_min_price = null;

        if ( isset( $query['min_price'] ) ) {
            $_min_price =  $query['min_price'];
        }
        if ( isset( $this->params['rng_min_price'] ) ) {
            $_min_price = $this->params['rng_min_price'];
        }
        if ( isset( $this->params['min_price'] ) ) {
            $_min_price =  $this->params['min_price'];
        }

        $_max_price = null;

        if ( isset( $query['max_price'] ) ) {
            $_max_price =  $query['max_price'];
        }
        if ( isset( $this->params['rng_max_price'] ) ) {
            $_max_price = $this->params['rng_max_price'];
        }
        if ( isset( $this->params['max_price'] ) ) {
            $_max_price =  $this->params['max_price'];
        }

        if ( isset( $_min_price ) && !isset( $_max_price ) ) {
            $_max_price = PHP_INT_MAX;
        }

        if ( isset( $_max_price ) && !isset( $_min_price ) ) {
            $_min_price = 0;
        }

        if ( isset( $_min_price ) ) {
            $_min_price = floatval( $_min_price ) - 0.01;
        }

        if ( isset( $_max_price ) ) {
            $_max_price = floatval( $_max_price ) + 0.01;
        }

        return array(
            'min_price' => $_min_price,
            'max_price' => $_max_price
        );

    }
}