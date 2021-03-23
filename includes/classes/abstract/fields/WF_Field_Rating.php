<?php

namespace wf\Classes\Base\Fields;

use wf\Classes\Base\WF_Fields;

/**
 * Class WF_Field_Rating
 * @package wf\Classes\Base
 */
class WF_Field_Rating extends WF_Fields {
    /**
     * WF_Field_Rating constructor.
     * @param $args
     */
    public function __construct($args) {
        parent::__construct($args);
    }

    /**
     * Implement get_types_list() method.
     * @return array|mixed
     */
    public function get_types_list() {
        return [
            [
                'id'   => self::TYPE_RATING,
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
        ];
    }
}