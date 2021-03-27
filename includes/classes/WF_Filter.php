<?php

namespace wf\Classes;

use wf\Classes\Vendor\BaseModel;

/**
 * Class WF_Filter
 * @package wf\Classes
 */
class WF_Filter extends BaseModel {

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
            $wf_filter->post_title = sanitize_text_field($title);
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
            if ( ! empty($field) ) {
                $data = [
                    'tag'          => $field->tag,
                    'type'         => $field->type,
                    'name'         => $field->name,
                    'label'        => $field->title,
                    'label_toggle' => $field->label_toggle,
                    'description'  => $field->description,
                ];
                $result[] = $data;
            }
        }

        return $result;
    }
}