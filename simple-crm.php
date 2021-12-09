<?php
/**
 * Plugin Name: Simple CRM
 * Plugin URI: https://iranimij.com
 * Description: Simple CRM system
 * Version: 1.0.0
 * Author: Iman Heydari
 * Author URI: https://iranimij.com
 * License: GPL2
 *
 */

defined( 'ABSPATH' ) || die();

/**
 * Check If Simple_Crm Class exists.
 *
 */
if ( ! class_exists( 'simple_crm' ) ) {

    /**
     * Simple Crm class.
     *
     * @since NEXT
     */
    class Simple_Crm {

        /**
         * Class instance.
         *
         * @since NEXT
         * @var Simple_Crm
         */
        private static $instance = null;

        /**
         * The plugin version number.
         *
         * @since NEXT
         *
         * @access private
         * @var string
         */
        private static $version;

        /**
         * The plugin basename.
         *
         * @since NEXT
         *
         * @access private
         * @var string
         */
        private static $plugin_basename;

        /**
         * The plugin name.
         *
         * @since NEXT
         *
         * @access private
         * @var string
         */
        private static $plugin_name;

        /**
         * The plugin directory.
         *
         * @since NEXT
         *
         * @access private
         * @var string
         */
        public static $plugin_dir;

        /**
         * The plugin URL.
         *
         * @since NEXT
         *
         * @access private
         * @var string
         */
        private static $plugin_url;

        /**
         * The plugin assets URL.
         *
         * @since NEXT
         * @access public
         *
         * @var string
         */
        public static $plugin_assets_url;

        /**
         * Get a class instance.
         *
         * @since NEXT
         *
         * @return Simple_Crm Class
         */
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Class constructor.
         *
         * @since NEXT
         */
        public function __construct() {
            $this->define_constants();

            add_action( 'init', [ $this, 'init' ] );
            add_action( 'admin_init', [ $this, 'admin_init' ] );

            add_action( 'wp_enqueue_scripts', [ $this, 'frontend_enqueue_scripts' ], 10 );
        }

        /**
         * Defines constants used by the plugin.
         *
         * @since NEXT
         */
        protected function define_constants() {
            $plugin_data = get_file_data( __FILE__, array( 'Plugin Name', 'Version' ), 'sellkit' );

            self::$plugin_basename   = plugin_basename( __FILE__ );
            self::$plugin_name       = array_shift( $plugin_data );
            self::$version           = array_shift( $plugin_data );
            self::$plugin_dir        = trailingslashit( plugin_dir_path( __FILE__ ) );
            self::$plugin_url        = trailingslashit( plugin_dir_url( __FILE__ ) );
            self::$plugin_assets_url = trailingslashit( self::$plugin_url . 'assets' );
        }

        /**
         * Initialize.
         *
         * @since NEXT
         */
        public function init() {
            $this->load_files( [ 'ajax-requests' ] );
            $this->register_post_types();
            $this->create_shortcodes();
        }

        /**
         * Admin init.
         *
         * @since NEXT
         */
        public function admin_init() {
            $this->load_files( [ 'admin/customers' ] );
            $this->register_post_types();
        }

        /**
         * Create all shortcodes.
         *
         * @since NEXT
         */
        public function create_shortcodes() {
            add_shortcode( 'simple_crm_form',[ $this, 'create_form_by_shortcode' ] );
        }

        /**
         * Creates form by shortcode.
         *
         * @since NEXT
         */
        public function create_form_by_shortcode( $atts ) {
            $attributes = shortcode_atts( [
                'name_label' => 'Name', // translation method should be applied.
                'phone_label' => 'Phone',
                'email_label' => 'Email',
                'desire_budget_label' => 'Desire Budget',
                'message_label' => 'Message',
                'name_length' => 100,
                'phone_length' => 100,
                'email_length' => 100,
                'desire_budget_length' => 10,
                'message_length' => 500,
                'message_rows' => 10,
                'message_columns' => 30,
            ], $atts );

            $attributes['current_time'] = $this->get_time()['unixtime'];

            set_query_var( 'simple_crm_form_attributes', $attributes );

            ob_start();

            $this->load_files( [ 'templates/form-template' ] );

            return ob_get_clean();
        }

        /**
         * Register admin menu.
         *
         * @since NEXT
         */
        public function register_post_types() {
           register_post_type( 'customer', [
               'label' => 'Customer', // a translation function should be added.
               'public' => false,
               'show_ui' => true,
               'taxonomies' => [ 'category', 'post_tag' ],
           ] );
        }

        /**
         * Loads specified PHP files from the plugin includes directory.
         *
         * @since NEXT
         *
         * @param array $file_names The names of the files to be loaded in the includes directory.
         */
        public function load_files( $file_names = array() ) {
            foreach ( $file_names as $file_name ) {
                $path = self::plugin_dir() . 'includes/' . $file_name . '.php';

                if ( file_exists( $path ) ) {
                    require_once $path;
                }
            }
        }

        /**
         * Returns the version number of the plugin.
         *
         * @since NEXT
         *
         * @return string
         */
        public function version() {
            return self::$version;
        }

        /**
         * Returns the plugin basename.
         *
         * @since NEXT
         *
         * @return string
         */
        public function plugin_basename() {
            return self::$plugin_basename;
        }

        /**
         * Returns the plugin name.
         *
         * @since NEXT
         *
         * @return string
         */
        public function plugin_name() {
            return self::$plugin_name;
        }

        /**
         * Returns the plugin directory.
         *
         * @since NEXT
         *
         * @return string
         */
        public function plugin_dir() {
            return self::$plugin_dir;
        }

        /**
         * Returns the plugin URL.
         *
         * @since NEXT
         *
         * @return string
         */
        public function plugin_url() {
            return self::$plugin_url;
        }

        /**
         * Returns the plugin assets URL.
         *
         * @since NEXT
         *
         * @return string
         */
        public function plugin_assets_url() {
            return self::$plugin_assets_url;
        }

        /**
         * Enqueue frontend scripts.
         *
         * @since NEXt
         */
        public function frontend_enqueue_scripts() {
            wp_enqueue_script(
                'simple-crm',
                $this->plugin_url() . 'assets/js/form.js',
                [ 'jquery', 'wp-util' ],
                $this->version(),
                true
            );

            wp_enqueue_style(
                'simple-crm',
                $this->plugin_url() . 'assets/css/form.css',
                [],
                $this->version()
            );
        }

        public function get_time() {
            $response = wp_remote_get( 'http://worldtimeapi.org/api/timezone/Europe/London' );

            if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
                $response = json_decode( $response['body'] );

                return (array) $response;
            }

            return false;
        }
    }
}

if ( ! function_exists( 'simple_crm' ) ) {
    /**
     * Initialize the Sellkit.
     *
     * @since NEXT
     */
    function simple_crm() {
        return Simple_Crm::get_instance();
    }
}

/**
 * Initialize the Sellkit application.
 *
 * @since NEXT
 */
simple_crm();
