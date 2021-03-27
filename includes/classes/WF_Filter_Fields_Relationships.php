<?php
namespace wf\Classes;

use wf\Classes\Vendor\BaseModel;

class WF_Filter_Fields_Relationships extends BaseModel {
    protected $fillable = [
        'id',
        'filter_id',
        'field_id',
    ];

    public $id;
    public $filter_id;
    public $field_id;

    public static function get_primary_key()
    {
        return 'id';
    }

    public static function get_table()
    {
        global $wpdb;
        return $wpdb->prefix . 'wf_filter_field_relationships';
    }

    public static function get_searchable_fields()
    {
        return [
            'id',
            'filter_id',
            'field_id',
        ];
    }

    public function getAttribute() {
        return WF_Field::query()->where('name', $this->field_id)->findOne();
    }
}