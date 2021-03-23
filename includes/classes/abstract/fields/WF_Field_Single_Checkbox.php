<?php

namespace wf\Classes\Base\Fields;

use wf\Classes\Base\WF_Fields;

/**
 * Class WF_Field_Single_Checkbox
 * @package wf\Classes\Base
 */
class WF_Field_Single_Checkbox extends WF_Fields {

    /**
     * WF_Field_Single_Checkbox constructor.
     * @param $args
     */
    public function __construct($args) {
        parent::__construct($args);
        $this->option    = wf_isset_helper($args, 'options', []);
        $this->is_toggle = wf_isset_helper($args, 'is_toggle', false);
    }

    /**
     * Checkbox $option
     * @var $options array
     */
    protected $option = [];


    /**
     * Checkbox field $is_toggle option
     * @var $is_toggle
     */
    protected $is_toggle = [];


    /**
     * Implement get_types_list() method.
     * @return array|mixed
     */
    public function get_types_list() {
        return [
            [
                'id'   => self::TYPE_SORT_BY,
                'text' => wf_text_domain('Sort By'),
            ],

            [
                'id'   => self::TYPE_ON_SALE,
                'text' => wf_text_domain('On Sale'),
            ],
        ];
    }

    public function get_data() {
        return [
            'label'       => $this->label,
            'description' => $this->description,
            'types_list'  => $this->get_types_list(),
            'types'       => $this->included_types,
            'is_toggle'   => $this->is_toggle,
            'option'      => $this->option,
        ];
    }
}