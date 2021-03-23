<?php

namespace wf\Classes\Base\Fields;

use wf\Classes\Base\WF_Fields;

/**
 * Class WF_Field_Radio
 * @package wf\Classes\Base
 */
class WF_Field_Radio extends WF_Fields {

    /**
     * WF_Field_Radio constructor.
     * @param $args
     */
    public function __construct($args) {
        parent::__construct($args);
        $this->options  = wf_isset_helper($args, 'options', []);
        $this->column   = wf_isset_helper($args, 'column');
    }

    /**
     * Radio $options
     * @var $options array
     */
    protected $options = [];

    /**
     * Radio field $column
     * @var $column
     */
    protected $column;


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
            'options'     => $this->options,
            'column'      => $this->column,
        ];
    }
}