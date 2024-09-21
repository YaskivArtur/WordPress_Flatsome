<?php
/**
 * Shortcode Widget Setup
 *
 * @package Shortcode_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Shortcode_Widget_Plugin' ) ) {

	/**
	 * Handles most of the interaction of the plugin with WordPress
	 *
	 * @since 1.5
	 */
	class Shortcode_Widget_Plugin {

		/**
		 * The instance of the class Shortcode_Widget_Plugin
		 *
		 * @since 1.5
		 *
		 * @access protected
		 *
		 * @var Shortcode_Widget_Plugin
		 */
		protected static $instance = null;

		/**
		 * Require the necessary files and calls register hooks method.
		 *
		 * @access public
		 *
		 * @since 1.5
		 */
		public function __construct() {
			/** The main widget class */
			require_once SHORTCODE_WIDGET_PATH . 'includes/class-shortcode-widget.php';
			$this->register_hooks();
		}

		/**
		 * Returns the current instance of the class, in case some other
		 * plugin needs to use its public methods.
		 *
		 * @since 1.5
		 *
		 * @access public
		 *
		 * @return Shortcode_Widget_Plugin Returns the current instance of the class
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Registers the shortcode and actions required for this plugin.
		 *
		 * @access public
		 *
		 * @since 1.5
		 *
		 * @return void
		 */
		public function register_hooks() {
			/** Registering our own little test shortcode */
			add_shortcode( 'shortcode_widget_test', array( $this, 'test_widget' ) );

			/** Will register the Shortcode_Widget */
			add_action( 'widgets_init', array( $this, 'widget_init' ) );

			/** Lets load translations */
			add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
		}

		/**
		 * Registers the Shortcode_Widget at widget_init
		 *
		 * @access public
		 *
		 * @since 1.5
		 *
		 * @return void
		 */
		public function widget_init() {
			register_widget( 'Shortcode_Widget' );
		}

		/**
		 * Loads the text domain for the plugin.
		 *
		 * @access public
		 *
		 * @since 1.5
		 *
		 * @return void
		 */
		public function load_text_domain() {
			load_plugin_textdomain( 'shortcode-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Returns the output of the test shortcode.
		 *
		 * @access public
		 *
		 * @since 1.5
		 *
		 * @return string Returns a string "It works", or a translated string if
		 *                available for the language of the WordPress site.
		 */
		public function test_widget() {
			return __( 'It works', 'shortcode-widget' );
		}

	}

	/** Initialises an object of this class */
	Shortcode_Widget_Plugin::get_instance();
}

