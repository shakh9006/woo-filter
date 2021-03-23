<?php

namespace wf\Classes\Base\Fields;

use wf\Classes\Base\WF_Field_Input;

class WF_Input extends WF_Field_Input {

    public function __construct($args) {
        $this->label          = wf_isset_helper($args, 'label');
        $this->description    = wf_isset_helper($args, 'description');
        $this->placeholder    = wf_isset_helper($args, 'placeholder');
        $this->toggle_label   = wf_isset_helper($args, 'toggle_label', true);
        $this->included_types = wf_isset_helper($args, 'types', []);
    }
}