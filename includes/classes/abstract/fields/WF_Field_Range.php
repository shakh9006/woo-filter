<?php

namespace wf\Classes\Base\Fields;

use wf\Classes\Base\WF_Fields;

/**
 * Class WF_Field_Range
 * @package wf\Classes\Base
 */
class WF_Field_Range extends WF_Fields {

    /**
     * WF_Field_Range constructor.
     * @param $args
     */
    public function __construct($args) {
        parent::__construct($args);
        $this->is_multi = wf_isset_helper($args, 'is_multi');
        $this->min      = wf_isset_helper($args, 'min');
        $this->max      = wf_isset_helper($args, 'max');
        $this->step     = wf_isset_helper($args, 'step');
    }

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