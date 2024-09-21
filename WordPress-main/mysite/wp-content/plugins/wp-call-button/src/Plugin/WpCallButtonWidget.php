<?php

/**
 * WP Call Button widget.
 *
 * @link       http://wpbeginner.com
 * @since      1.0.0
 *
 * @package    WpCallButton
 * @subpackage WpCallButton/Plugin
 */
namespace WpCallButton\Plugin;

class WpCallButtonWidget extends \WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Holds Call button config settings.
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Plugin slug.
		$plugin_slug = 'wp-call-button';

		// Get settings.
		$this->settings = WpCallButtonHelpers::get_settings();

		// Widget defaults.
		$this->defaults = [
			'title'           => '',
			'form_id'         => '',
			'cta_text'        => $this->settings['wpcallbtn_button_text'],
			'cta_color'       => $this->settings['wpcallbtn_button_color'],
			'cta_txt_color'   => '#fff',
			'show_phone_icon' => true,
			'description'     => '',
		];

		// Widget Slug.
		$widget_slug = $plugin_slug . '-widget-main-a';

		// Widget basics.
		$widget_ops = [
			'classname'   => $widget_slug,
			'description' => esc_html_x( 'Display a call button.', 'Widget', 'wp-call-button' ),
		];

		// Widget controls.
		$control_ops = [
			'id_base' => $widget_slug,
		];

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );

		// Load widget.
		parent::__construct( $widget_slug, esc_html_x( 'WP Call Button', 'Widget', 'wp-call-button' ), $widget_ops, $control_ops );
	}

	/**
	 * Enqueue scripts and styles for the widget editor.
	 *
	 * @param string $hook_suffix The name of the admin page.
	 */
	function admin_scripts( $hook_suffix ) {
		if ( $hook_suffix !== 'widgets.php' ) {
			return;
		}

		// Enqueue scripts.
		WpCallButtonHelpers::enqueue_admin_scripts( true );

		// Enqueue styles.
		WpCallButtonHelpers::enqueue_admin_styles();

	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array $args An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	public function widget( $args, $instance ) {

		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];

		// Title.
		if ( ! empty( $instance['title'] ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $instance['title'] ) ) . $args['after_title'];
		}

		// Description.
		if ( ! empty( $instance['description'] ) ) {
			echo '<p>' . esc_html( $instance['description'] ) . '</p>';
		}

		// Print Call button.

		// Get the call button.
		$call_button = WpCallButtonHelpers::get_call_button( $this->settings );

		// The call button text.
		$call_button_text = '<span style="color:' . esc_attr( $instance['cta_txt_color'] ) . '">' . ( $instance['show_phone_icon'] ? '<img style="width: 70px; height: 30px; display: inline; vertical-align: middle; box-shadow: none; border: 0;" src="' . WpCallButtonHelpers::get_phone_image( $instance['cta_txt_color'] ) . '" />' : '' ) . esc_html( $instance['cta_text'] ) . '</span>';

		// Get the google analytics click tracking.
		$click_tracking = $call_button['tracking'];

		// Build the styles for the call button.
		$call_button_markup = 'margin-top: 20px; display: inline-block; box-sizing: border-box; border-radius: 5px;' .
				'color: white !important; width: auto; text-align: center !important; font-size: 24px !important; ' .
			'font-weight: bold !important; ' .
				( $instance['show_phone_icon'] ? 'padding: 15px 20px 15px 0 !important; ' : 'padding: 15px 20px !important;' ) .
				'text-decoration: none !important;' .
				'background: ' . esc_attr( $instance['cta_color'] ) . ' !important;';

		// Print the call button.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo ( $this->settings['wpcallbtn_button_enabled'] === 'yes' && ! empty( $this->settings['wpcallbtn_phone_num'] ) ) ? '<a style="' . $call_button_markup . '" class="wp-call-button-widget-btn" href="tel:' . esc_attr( $this->settings['wpcallbtn_phone_num'] ) . '"' . $click_tracking . '>' . $call_button_text . '</a>' : '';

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget'];
	}

	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 *
	 * @param array $new_instance An array of new settings as submitted by the admin.
	 * @param array $old_instance An array of the previous settings.
	 *
	 * @return array The validated and (if necessary) amended settings
	 */
	public function update( $new_instance, $old_instance ) {

		$new_instance['title']           = sanitize_text_field( $new_instance['title'] );
		$new_instance['description']     = sanitize_text_field( $new_instance['description'] );
		$new_instance['cta_text']        = sanitize_text_field( $new_instance['cta_text'] );
		$new_instance['cta_color']       = sanitize_text_field( $new_instance['cta_color'] );
		$new_instance['cta_txt_color']   = sanitize_text_field( $new_instance['cta_txt_color'] );
		$new_instance['show_phone_icon'] = ( isset( $new_instance['show_phone_icon'] ) && ( sanitize_text_field( $new_instance['show_phone_icon'] ) === 'yes' ) ) ? true : false;

		return $new_instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array $instance An array of the current settings for this widget.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		?>
		<div class="wpcb-widget-item">
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php echo esc_html( _x( 'Title:', 'Widget', 'wp-call-button' ) ); ?>
			</label>
			<input
				type="text"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat"
			/>
		</div>
		<div class="wpcb-widget-item">
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>">
				<?php echo esc_html( _x( 'Description:', 'Widget', 'wp-call-button' ) ); ?>
			</label>
			<textarea
				id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"
				class="widefat"><?php echo esc_html( $instance['description'] ); ?></textarea>
		</div>
		<a style="cursor: pointer; margin: 0 0 12px 0; display: inline-block;" data-show-msg="<?php echo esc_attr_x( 'Show Advanced Settings', 'Widget', 'wp-call-button' ); ?>" data-hide-msg="<?php echo esc_attr_x( 'Hide Advanced Settings', 'Widget', 'wp-call-button' ); ?>" class="wpcb-link-adv-show"><?php echo esc_html_x( 'Show Advanced Settings', 'Widget', 'wp-call-button' ); ?></a>
		<div class="wpcb-widget-settings" style="display: none;">
			<div class="wpcb-widget-item">
				<label for="<?php echo esc_attr( $this->get_field_id( 'cta_text' ) ); ?>">
					<?php echo esc_html( _x( 'Call Button Text:', 'Widget', 'wp-call-button' ) ); ?>
				</label>
				<input
					type="text"
					id="<?php echo esc_attr( $this->get_field_id( 'cta_text' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'cta_text' ) ); ?>"
					value="<?php echo esc_attr( $instance['cta_text'] ); ?>" class="widefat"
				/>
			</div>
			<div class="wpcb-widget-item">
				<label for="<?php echo esc_attr( $this->get_field_id( 'cta_color' ) ); ?>">
					<?php echo esc_html( _x( 'Call Button Color:', 'Widget', 'wp-call-button' ) ); ?>
				</label>
				<input
					type="text"
					id="wpcallbtn_button_color"
					class="input_wpcallbtn_button_color"
					name="<?php echo esc_attr( $this->get_field_name( 'cta_color' ) ); ?>"
					value="<?php echo esc_attr( $instance['cta_color'] ); ?>"
					class="widefat"
				/>
			</div>
			<div class="wpcb-widget-item">
				<label for="<?php echo esc_attr( $this->get_field_id( 'cta_txt_color' ) ); ?>">
					<?php echo esc_html( _x( 'Call Button Text Color:', 'Widget', 'wp-call-button' ) ); ?>
				</label>
				<input
					type="text"
					id="wpcallbtn_button_color_static"
					class="input_wpcallbtn_button_color_static"
					name="<?php echo esc_attr( $this->get_field_name( 'cta_txt_color' ) ); ?>"
					value="<?php echo esc_attr( $instance['cta_txt_color'] ); ?>"
					class="widefat"
				/>
			</div>
			<div class="wpcb-widget-item">
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_phone_icon' ) ); ?>">
					<?php echo esc_html( _x( 'Show the phone icon?', 'Widget', 'wp-call-button' ) ); ?>
				</label>
				<input type="checkbox"
					<?php echo ( $instance['show_phone_icon'] ? ' checked="checked" ' : '' ); ?>
					id="<?php echo esc_attr( $this->get_field_id( 'show_phone_icon' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'show_phone_icon' ) ); ?>"
					value="yes" class="widefat wpcb-switch-checkbox"
				/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_phone_icon' ) ); ?>" class="wpcb-switch-toggle"></label>
			</div>
		</div>
		<?php
	}
}
