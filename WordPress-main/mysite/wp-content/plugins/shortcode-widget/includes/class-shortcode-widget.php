<?php
/**
 * Contains the Shortcode Widget
 *
 * @package Shortcode_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Shortcode_Widget' ) ) {

	/**
	 * Shortcode Widget Class
	 *
	 * @since 0.1
	 */
	class Shortcode_Widget extends WP_Widget {

		/**
		 * Defines this widgets arguments and calls parent class constructor
		 *
		 * @access public
		 *
		 * @since 0.1
		 */
		public function __construct() {
			$widget_ops  = array(
				'classname'   => 'shortcode_widget',
				'description' => __( 'Shortcode or HTML or Plain Text.', 'shortcode-widget' ),
			);
			$control_ops = array(
				'width'  => 400,
				'height' => 350,
			);
			parent::__construct( 'shortcode-widget', __( 'Shortcode Widget', 'shortcode-widget' ), $widget_ops, $control_ops );
		}

		/**
		 * Echoes the widget content.
		 *
		 * @access public
		 *
		 * @since 0.1
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 *
		 * @param array $instance The settings for the particular instance of the widget.
		 *
		 * @return void
		 */
		public function widget( $args, $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

			$instance['filter'] = ! empty( $instance['filter'] );

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			/** This filter is documented in wp-includes/widgets/class-wp-widget-text.php */
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			$text = do_shortcode( apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance, $this ) );

			// We need $args['before_widget'] value as it is, so no escaping it.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['before_widget'];
			if ( ! empty( $title ) ) {
				// We need $args['before_title'] and $args['after_title'] values as they are, so no escaping it.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $args['before_title'] . $title . $args['after_title'];
			}
			?>
			<?php
			// We need $instance['filter'] value as it is, so no escaping it.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="textwidget"><?php echo ! empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
			<?php
			// We need $args['after_widget'] value as it is, so no escaping it.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['after_widget'];
		}

		/**
		 * Updates a particular instance of a widget.
		 *
		 * @access public
		 *
		 * @since 0.1
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 *
		 * @return array|false        Settings to save or bool false to cancel saving.
		 */
		public function update( $new_instance, $old_instance ) {
			$new_instance = wp_parse_args(
				$new_instance,
				array(
					'title'  => '',
					'text'   => '',
					'filter' => false,
				)
			);
			$instance     = $old_instance;

			$instance['title'] = sanitize_text_field( $new_instance['title'] );
			if ( current_user_can( 'unfiltered_html' ) ) {
				$instance['text'] = $new_instance['text'];
			} else {
				$instance['text'] = wp_kses_post( $new_instance['text'] );
			}
			$instance['filter'] = ! empty( $new_instance['filter'] );

			return $instance;
		}

		/**
		 * Outputs the settings update form.
		 *
		 * @access public
		 *
		 * @since 0.1
		 *
		 * @param array $instance Current settings.
		 *
		 * @return void
		 */
		public function form( $instance ) {
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'title' => '',
					'text'  => '',
				)
			);
			?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'shortcode-widget' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Content:', 'shortcode-widget' ); ?></label>
				<textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>

			<p><input id="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'filter' ) ); ?>" type="checkbox" <?php checked( isset( $instance['filter'] ) ? $instance['filter'] : 0 ); ?> />&nbsp;<label for="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>"><?php esc_html_e( 'Automatically add paragraphs', 'shortcode-widget' ); ?></label></p>
			<?php
		}

	}

}
