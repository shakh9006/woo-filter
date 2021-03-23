<?php

namespace wf\Classes\Base\Fields;

use wf\Classes\Base\WF_Fields;

/**
 * Class WF_Field_Select
 * @package wf\Classes\Base
 */
class WF_Field_Select extends WF_Fields {

    /**
     * WF_Field_Select constructor.
     * @param $args
     */
    public function __construct($args) {
        parent::__construct($args);
        $this->options  = wf_isset_helper($args, 'options', []);
        $this->rules    = wf_isset_helper($args, 'rules', []);
        $this->is_multi = wf_isset_helper($args, 'is_multi', false);
    }

    /**
     * Drop Down field multi toggle
     * @var $is_multi
     */
    protected $is_multi;

    /**
     * Drop Down $options
     * @var $options array
     */
    protected $options = [];

    /**
     * Drop Down field $rules
     * @var $rules = []
     */
    protected $rules = [];


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
            'is_multi'    => $this->is_multi,
            'options'     => $this->options,
            'rules'       => $this->rules,
        ];
    }
}