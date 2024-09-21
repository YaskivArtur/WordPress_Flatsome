<?php
/**
 * Registers the `ux_menu_link` shortcode.
 *
 * @package flatsome
 */

/**
 * Renders the `ux_menu_link` shortcode.
 *
 * @param array  $atts    An array of attributes.
 * @param string $content The shortcode content.
 * @param string $tag     The name of the shortcode, provided for context to enable filtering.
 *
 * @return string
 */
function flatsome_render_ux_menu_link_shortcode( $atts, $content, $tag ) {
	global $wp_query;

	$atts = shortcode_atts(
		array(
			'visibility' => '',
			'class'      => '',
			'text'       => '',
			'label'      => '',
			'icon'       => '',
			'post'       => '',
			'term'       => '',
			'link'       => '',
			'target'     => '_self',
			'rel'        => '',
		),
		$atts,
		$tag
	);

	$object            = null;
	$object_id         = null;
	$object_type       = null;
	$queried_object    = $wp_query->get_queried_object();
	$queried_object_id = (int) $wp_query->queried_object_id;

	$classes = array( 'ux-menu-link flex menu-item' );
	$link    = trim( $atts['link'] );
	$icon    = $atts['icon'] ? get_flatsome_icon( 'ux-menu-link__icon text-center ' . $atts['icon'] ) : '';

	if ( ! empty( $atts['post'] ) ) {
		$object_type = 'post_type';
		$object_id   = (int) $atts['post'];
		$object      = get_post( $object_id );
		$link        = get_permalink( $object_id );
	} elseif ( ! empty( $atts['term'] ) ) {
		$object_type = 'taxonomy';
		$object_id   = (int) $atts['term'];
		$object      = get_term_by( 'term_taxonomy_id', $object_id );
		$link        = get_term_link( $object_id );
	}

	if ( ! is_string( $link ) ) {
		$link = '';
	}

	$protocols         = array_diff( wp_allowed_protocols(), array( 'http', 'https' ) );
	$protocol          = explode( ':', $link )[0];
	$skip_validate_url = substr( $link, 0, 1 ) === '#' || in_array( $protocol, $protocols, true );

	// Ensure paths (except hash & specific protocols (f.ex: mailto:, sms:, ...) are rendered as full URLs.
	if ( ! $skip_validate_url && ! wp_http_validate_url( $link ) ) {
		$link = site_url( $link );
	}

	if ( ! empty( $atts['class'] ) )      $classes[] = $atts['class'];
	if ( ! empty( $atts['label'] ) )      $classes[] = $atts['label'];
	if ( ! empty( $atts['visibility'] ) ) $classes[] = $atts['visibility'];

	if (
		$object &&
		$queried_object &&
		$queried_object_id === $object_id &&
		(
			( $object_type === 'post_type' && $wp_query->is_singular ) ||
			( $object_type === 'taxonomy' && $queried_object->taxonomy === $object->taxonomy )
		)
	) {
		$classes[] = 'ux-menu-link--active';
	}

	$link_rels = explode( ' ', $atts['rel'] );
	$link_atts = array(
		'target' => $atts['target'],
		'rel'    => array_filter( $link_rels ),
	);

	ob_start();

	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<a class="ux-menu-link__link flex" href="<?php echo esc_url( $link ); ?>" <?php echo flatsome_parse_target_rel( $link_atts, true ); ?>>
			<?php echo $icon; ?>
			<span class="ux-menu-link__text">
				<?php echo esc_html( $atts['text'] ); ?>
			</span>
		</a>
	</div>
	<?php

	return ob_get_clean();
}
add_shortcode( 'ux_menu_link', 'flatsome_render_ux_menu_link_shortcode' );
