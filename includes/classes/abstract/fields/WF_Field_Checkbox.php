<?php

namespace wf\Classes\Base\Fields;

use wf\Classes\Base\WF_Fields;

/**
 * Class WF_Field_Checkbox
 * @package wf\Classes\Base
 */
class WF_Field_Checkbox extends WF_Fields {

    /**
     * WF_Field_Checkbox constructor.
     * @param $args
     */
    public function __construct($args) {
        parent::__construct($args);
        $this->options   = wf_isset_helper($args, 'options', []);
        $this->rules     = wf_isset_helper($args, 'column', []);
        $this->column    = wf_isset_helper($args, 'column');
        $this->is_toggle = wf_isset_helper($args, 'is_toggle', false);
    }

    /**
     * Checkbox $options
     * @var $options array
     */
    protected $options = [];

    /**
     * Checkbox field $rules
     * @var $rules
     */
    protected $rules = [];

    /**
     * Checkbox field $is_toggle option
     * @var $is_toggle
     */
    protected $is_toggle = [];

    /**
     * Checkbox field $column
     * @var $is_toggle
     */
    protected $column = [];


    /**
     * Implement get_types_list() method.
     * @return array|mixed
     */
    public function get_types_list() {
        return [
            [
                'id'   => self::TYPE_PRICE,
                'text' => wf_text_domain('Price'),
            ],

            [
                'id'   => self::TYPE_PRODUCT_CATEGORIES,
                'text' => wf_text_domain('Product Categories'),
            ],

            [
                'id'   => self::TYPE_PRODUCT_TAGS,
                'text' => wf_text_domain('Product Tags'),
            ]
        ];
    }

    public function get_data() {
        return [
            'label'       => $this->label,
            'description' => $this->description,
            'types_list'  => $this->get_types_list(),
            'types'       => $this->included_types,
            'is_toggle'   => $this->is_toggle,
            'options'     => $this->options,
            'rules'       => $this->rules,
            'column'      => $this->column,
        ];
    }
}