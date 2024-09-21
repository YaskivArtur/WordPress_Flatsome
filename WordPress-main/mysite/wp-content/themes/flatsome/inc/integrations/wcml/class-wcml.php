<?php
/**
 * WooCommerce multilingual integration
 *
 * @author      UX Themes
 * @package     Flatsome\Integrations
 * @since       3.17.4
 */

namespace Flatsome\Integrations;

defined( 'ABSPATH' ) || exit;

/**
 * Class WCML
 *
 * @package Flatsome\Integrations
 */
class WCML {

	/**
	 * Static instance
	 *
	 * @var WCML $instance
	 */
	private static $instance = null;

	/**
	 * WCML constructor.
	 */
	private function __construct() {
		add_filter( 'wcml_multi_currency_ajax_actions', [ $this, 'multi_currency_ajax_actions' ] );
	}

	/**
	 * Adds custom actions to the WooCommerce Multilingual multi-currency ajax actions.
	 *
	 * @param array $ajax_actions The existing AJAX actions.
	 *
	 * @return array Returns the modified array of AJAX actions.
	 */
	public function multi_currency_ajax_actions( $ajax_actions ) {
		$ajax_actions[] = 'flatsome_ajax_add_to_cart';
		$ajax_actions[] = 'flatsome_quickview';
		$ajax_actions[] = 'flatsome_ajax_search_products';

		return $ajax_actions;
	}

	/**
	 * Initializes the object and returns its instance.
	 *
	 * @return WCML The object instance
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

WCML::get_instance();

