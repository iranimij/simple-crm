<?php

defined( 'ABSPATH' ) || die();

class Customers {

    /**
     * Customers constructor.
     *
     * @since NEXT
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 10 );
        add_action( 'add_meta_boxes', [ $this, 'add_customer_meta_boxes'] );
        add_action( 'save_post', [ $this, 'save_customer_post'] );
    }

    /**
     * Adds customer meta boxes.
     *
     * @since NEXT
     */
    public function add_customer_meta_boxes() {
        add_meta_box(
            'customer_data_box',
            'Customer data box',
            [ $this, 'add_fields'],
            'customer'
        );
    }

    /**
     * Adds some field to the meta box
     *
     * @since NEXt
     */
    public function add_fields( $post ) {
        $phone         = get_post_meta( $post->ID, 'phone', true );
        $email         = get_post_meta( $post->ID, 'email', true );
        $desire_budget = get_post_meta( $post->ID, 'desire_budget', true );
        $current_time  = get_post_meta( $post->ID, 'current_time', true );
        ?>
        <div class="simple-crm-meta-row">
            <label for="phone"><?php esc_html_e( 'Phone', 'simple-crm' ); ?></label>
            <input type="text" name="phone" id="phone" class="postbox" value="<?php echo esc_html( $phone ) ?>">
        </div>
        <div class="simple-crm-meta-row">
            <label for="email"><?php esc_html_e( 'Email', 'simple-crm' ); ?></label>
            <input type="email" name="email" id="email" class="postbox" value="<?php echo esc_html( $email ) ?>">
        </div>
        <div class="simple-crm-meta-row">
            <label for="desire_budget"><?php esc_html_e( 'Desire Budget', 'simple-crm' ); ?></label>
            <input type="text" name="desire_budget" id="desire_budget" class="postbox" value="<?php echo esc_html( $desire_budget ) ?>">
        </div>
        <div class="simple-crm-meta-row">
            <label for="current_time"><?php esc_html_e( 'Sent time', 'simple-crm' ); ?></label>
            <input type="datetime-local" name="current_time" id="current_time" class="postbox" value="<?php echo date( 'Y-m-d\TH:i', $current_time ); ?>">
        </div>
        <?php
    }

    /**
     * Enqueue styles.
     *
     * @since NEXT
     */
    public function admin_enqueue_scripts() {
        if ( 'customer' !== get_post_type() ) {
            return;
        }

        wp_enqueue_style(
            'simple-crm-customer-admin-area',
            simple_crm()->plugin_url() . 'assets/css/admin/customer.css',
            [],
            simple_crm()->version()
        );
    }

    /**
     * Save customer post.
     *
     * @since NEXT
     */
    public function save_customer_post( $post_id ) {
        if ( 'customer' !== get_post_type( $post_id ) ) {
            return;
        }

        $phone         = filter_input( INPUT_POST, 'phone', FILTER_SANITIZE_STRING );
        $email         = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING );
        $desire_budget = filter_input( INPUT_POST, 'desire_budget', FILTER_SANITIZE_STRING );

        update_post_meta( $post_id, 'phone', sanitize_text_field( $phone ) );
        update_post_meta( $post_id, 'email', sanitize_text_field( $email ) );
        update_post_meta( $post_id, 'desire_budget', sanitize_text_field( $desire_budget ) );
    }
}

new Customers();