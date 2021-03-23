<?php

namespace wf\Classes;

use wf\Classes\WF_Fields;

/**
 * Class WF_Settings_data
 * @package wf\Classes
 */
class WF_Settings_data {

    /**
     * Admin Data Store
     * Send json data
     */
    public static function admin_get_data() {
        $result = [
            'message' => wf_text_domain('Permission denied.'),
            'success' => false,
            'status'  => 'error',
            'data'    => [],
        ];

        if ( current_user_can('manage_options') ) {

            $data = [
                'fields' => self::get_fields(),
            ];


            $result['message'] = wf_text_domain('Admin data got successfully');
            $result['success'] = true;
            $result['status']  = 'success';
        }
    }

    /**
     * Fields store
     * @return array
     */
    private static function get_fields() {
//        $wf_fields = new WF_Fields();


    }
}