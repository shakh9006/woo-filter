<?php
namespace wf\Classes\Base;

/**
 * Class WF_Fields
 * @package wf\Classes\Field
 */
abstract class WF_Fields {
    const TYPE_PRICE = 'price';
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