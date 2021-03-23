<?php

namespace wf\Classes\Base\Fields;

use wf\Classes\Base\WF_Fields;

/**
 * Class WF_Field_Input
 * @package wf\Classes\Base
 */
class WF_Field_Input extends WF_Fields {

    /**
     * WF_Field_Input constructor.
     * @param $args
     */
    public function __construct($args) {
        parent::__construct($args);
        $this->placeholder = wf_isset_helper($args, 'placeholder');
    }

    /**
     * Input field placeholder
     * @var $placeholder
     */
    protected $placeholder;

    /**
     * Implement get_types_list() method.
     * @return array|mixed
     */
    public function get_types_list() {
        return [
            [
                'id'   => self::TYPE_PRICE,
                'text' => wf_text_domain('Price'),
            ]
        ];
    }

    public function get_data() {
        return [
            'label'       => $this->label,
            'description' => $this->description,
            'placeholder' => $this->placeholder,
            'types_list'  => $this->get_types_list(),
            'types'       => $this->included_types,
        ];
    }
}