<?php
namespace wf\Classes\Base;

/**
 * Class WF_Fields
 * @package wf\Classes\Field
 */
abstract class WF_Fields {

    /**
     * WF_Fields constructor.
     * @param $args
     */
    protected function __construct($args) {
        $this->label          = wf_isset_helper($args, 'label');
        $this->description    = wf_isset_helper($args, 'description');
        $this->toggle_label   = wf_isset_helper($args, 'toggle_label', true);
        $this->included_types = wf_isset_helper($args, 'types', []);
    }

    /**
     * Types List
     */
    const TYPE_PRICE              = 'price';
    const TYPE_COLOR              = 'color';
    const TYPE_RATING             = 'rating';
    const TYPE_ON_SALE            = 'on_sale';
    const TYPE_SORT_BY            = 'sort_by';
    const TYPE_PRODUCT_CATEGORIES = 'product_categories';
    const TYPE_PRODUCT_TAGS       = 'product_tags';

    /**
     * Field Label
     * @var $label
     */
    protected $label;

    /**
     * Field description
     * @var $description
     */
    protected $description;

    /**
     * Field label toggle (show/hide option)
     * @var $toggle_label
     */
    protected $toggle_label;

    /**
     * List of included types
     * @var $included_types array
     */
    protected $included_types = [];

    /**
     * Data Store
     * @return array
     */
    abstract public function get_data();

    /**
     * @return array
     * Get Types List
     */
    abstract public function get_types_list();
}