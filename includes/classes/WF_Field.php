<?php
namespace wf\Classes;

use wf\Classes\Vendor\ArrayHelper;
use wf\Classes\Vendor\BaseModel;

/**
 * Class WF_Field
 * @package wf\Classes
 */
class WF_Field extends BaseModel {

   const TYPE_INPUT         = 'input';
    const TYPE_SLIDER       = 'slider';
    const TYPE_SELECT       = 'select';
    const TYPE_CHECKBOX     = 'checkbox';
    const TYPE_RADIO_BUTTON = 'radio';
//    const TYPE_RATING       = 'rating';
//    const TYPE_COLOR        = 'color';
    const TYPE_SORT_BY      = 'sort_by';

    const TAG_PRICE         = 'price';
    const TAG_CATEGORIES    = 'product_categories';
    const TAG_TAGS          = 'product_tags';
    const TAG_SORT_BY       = 'sort_by';
//    const TAG_RATING        = 'rating';
//    const TAG_COLOR         = 'color';

    protected $fillable = [
        'id',
        'title',
        'name',
        'type',
        'tag',
        'label_toggle',
        'description',
    ];

    public $id;
    public $title;
    public $name;
    public $type;
    public $tag;
    public $label_toggle;
    public $description;

    public static function get_primary_key() {
        return 'id';
    }

    public static function get_table() {
        global $wpdb;
        return $wpdb->prefix . 'wf_field';
    }

    public static function get_searchable_fields() {
        return [
            'id',
            'title',
            'name',
            'type',
            'tag',
            'label_toggle',
            'description',
        ];
    }

    public function get_field_meta_data() {
        return get_term_meta($this->id, 'options', true);
    }

    public function saveMeta($data) {
        update_term_meta($this->id, 'options', wf_sanitize_array($data));
    }
}