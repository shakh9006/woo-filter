<?php

namespace wf\Classes\Base;

use wf\Classes\Base\WF_Fields;

/**
 * Class WF_Field_Range
 * @package wf\Classes\Base
 */
abstract class WF_Field_Range extends WF_Fields {

    /**
     * Range field multi toggle
     * @var $is_multi
     */
    protected $is_multi;

    /**
     * Range field min
     * @var $min
     */
    protected $min;

    /**
     * Range field $max
     * @var $max
     */
    protected $max;

    /**
     * Range field $step
     * @var $step
     */
    protected $step;



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
            'types_list'  => $this->get_types_list(),
            'types'       => $this->included_types,
            'is_multi'    => $this->is_multi,
            'min'         => $this->min,
            'max'         => $this->max,
            'step'        => $this->step,
        ];
    }
}