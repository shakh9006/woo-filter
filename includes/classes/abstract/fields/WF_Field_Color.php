<?php

namespace wf\Classes\Base\Fields;

use wf\Classes\Base\WF_Fields;

/**
 * Class WF_Field_Color
 * @package wf\Classes\Base
 */
class WF_Field_Color extends WF_Fields {

    /**
     * WF_Field_Color constructor.
     * @param $args
     */
    public function __construct($args) {
        parent::__construct($args);
        $this->color     = wf_isset_helper($args, 'color', '#000000');
        $this->tooltip   = wf_isset_helper($args, 'tooltip', true);
    }

    /**
     * Color field color has color property
     * @var string
     */
    protected $color = '#000000';

    /**
     * @var bool
     */
    protected $tooltip = true;

    /**
     * Implement get_types_list() method.
     * @return array|mixed
     */
    public function get_types_list() {
        return [
            [
                'id'   => self::TYPE_COLOR,
                'text' => wf_text_domain('Rating'),
            ]
        ];
    }

    public function get_data() {
        return [
            'label'       => $this->label,
            'description' => $this->description,
            'types_list'  => $this->get_types_list(),
            'types'       => $this->included_types,
            'color'       => $this->color,
            'tooltip'     => $this->tooltip,
        ];
    }
}