<?php
/**
 * Flatsome Cookie notice extension
 *
 * @author     UX Themes
 * @category   Extension
 * @package    Flatsome/Extensions
 * @since      3.12.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue extensions scripts.
 */
function flatsome_cookie_notice_scripts() {
	global $extensions_uri;

	wp_enqueue_script( 'flatsome-cookie-notice', $extensions_uri . '/flatsome-cookie-notice/flatsome-cookie-notice.js', array( 'jquery', 'flatsome-js' ), '3.12.0', true );
}

add_action( 'wp_enqueue_scripts', 'flatsome_cookie_notice_scripts' );

/**
 * Html template for cookie notice.
 */
function flatsome_cookie_notice_template() {
	if ( ! get_theme_mod( 'cookie_notice' ) ) {
		return;
	}

	$classes = array( 'flatsome-cookies' );
	if ( get_theme_mod( 'cookie_notice_text_color' ) === 'dark' ) {
		$classes[] = 'dark';
	}
	$text = get_theme_mod( 'cookie_notice_text' );
	$id   = get_theme_mod( 'privacy_policy_page' );
	$page = $id ? get_post( $id ) : false;
	$text = $text ? $text : __( 'This site uses cookies to offer you a better browsing experience. By browsing this website, you agree to our use of cookies.', 'flatsome' );
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<div class="flatsome-cookies__inner">
			<div class="flatsome-cookies__text">
				<?php echo do_shortcode( $text ); ?>
			</div>
			<div class="flatsome-cookies__buttons">
				<?php
				if ( $page ) {
					echo flatsome_apply_shortcode( 'button', array(
						'text'  => _x( 'More info', 'cookie notice', 'flatsome' ),
						'style' => get_theme_mod( 'cookie_notice_button_style', '' ),
						'link'  => get_permalink( $page->ID ),
						'color' => 'secondary',
						'class' => 'flatsome-cookies__more-btn',
					) );
				}
				?>
				<?php
				echo flatsome_apply_shortcode( 'button', array(
					'text'  => _x( 'Accept', 'cookie notice', 'flatsome' ),
					'style' => get_theme_mod( 'cookie_notice_button_style', '' ),
					'link'  => '#',
					'color' => 'primary',
					'class' => 'flatsome-cookies__accept-btn',
				) );
				?>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'wp_footer', 'flatsome_cookie_notice_template' );
