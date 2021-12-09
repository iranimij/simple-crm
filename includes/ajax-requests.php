<?php

defined( 'ABSPATH' ) || die();

class Ajax_Requests {
    public function __construct() {
        add_action( 'wp_ajax_handle_form_submitting', [ $this, 'handle_form_submitting'] );
        add_action( 'wp_ajax_nopriv_handle_form_submitting', [ $this, 'handle_form_submitting'] );
    }

    public function handle_form_submitting() {
        $data          = filter_input( INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
        $nonce         = filter_input( INPUT_POST, 'nonce', FILTER_SANITIZE_STRING );

        wp_verify_nonce( $nonce, 'simple-crm-submit-form' );

        $name          = ! empty( $data['name'] ) ? $data['name'] : '';
        $phone         = ! empty( $data['phone'] ) ? $data['phone'] : '';
        $email         = ! empty( $data['email'] ) ? $data['email'] : '';
        $desire_budget = ! empty( $data['desire_budget'] ) ? $data['desire_budget'] : '';
        $message       = ! empty( $data['message'] ) ? $data['message'] : '';
        $current_time  = ! empty( $data['current_time'] ) ? $data['current_time'] : '';


        $my_post = [
            'post_title'    => sanitize_text_field( $name ),
            'post_status'   => 'publish',
            'post_type'   => 'customer',
            'post_content'   => sanitize_text_field( $message ),
        ];

        $post_id = wp_insert_post( $my_post );

        if ( ! empty( $post_id ) ) {
            update_post_meta( $post_id, 'phone', sanitize_text_field( $phone ) );
            update_post_meta( $post_id, 'email', sanitize_text_field( $email ) );
            update_post_meta( $post_id, 'desire_budget', sanitize_text_field( $desire_budget ) );
            update_post_meta( $post_id, 'current_time', sanitize_text_field( $current_time ) );
        }

        wp_send_json_success( $post_id );
    }
}

new Ajax_Requests();