<?php
namespace wf\Classes\Base\Factory;

use wf\Classes\Base\WF_Fields;

/**
 * Class WF_Field_Factory
 * @package wf\Classes\Field\Factory
 */

class WF_Field_Factory {
    const __NAME_SPACE = '\wf\Classes\Base\Fields\\';

    public static function get_data( $type, $args ) {
        $result = null;
        $class  = self::__NAME_SPACE . 'WF_Field_' . ucfirst($type); // Field namespace

        if ( class_exists($class) ) {
            /**
             * @var WF_Fields
             */
            $field = new $class($args); // Create new instance if class exists
            if ( method_exists($field, 'get_data') ) {
                $result = $field->get_data(); // get data if method exists
            }
        }

        return $result;
    }
}