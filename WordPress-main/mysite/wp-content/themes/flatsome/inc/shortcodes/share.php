<?php
/**
 * Registers the `share` shortcode.
 *
 * @package flatsome
 */

/**
 * Renders the `share` shortcode.
 *
 * @param array  $atts    An array of attributes.
 * @param string $content The shortcode content.
 * @param string $tag     The name of the shortcode, provided for context to enable filtering.
 *
 * @return string
 */
function flatsome_share( $atts, $content = null, $tag = '' ) {
	extract( shortcode_atts(
		array(
			'title'      => '',
			'class'      => '',
			'visibility' => '',
			'size'       => '',
			'align'      => '',
			'scale'      => '',
			'style'      => '',
		),
		$atts,
		$tag
	) );

	// Get custom share icons if set.
	if ( get_theme_mod( 'custom_share_icons' ) ) {
		return do_shortcode( get_theme_mod( 'custom_share_icons' ) );
	}

	$wrapper_class = array( 'social-icons', 'share-icons', 'share-row', 'relative' );

	if ( $class ) $wrapper_class[] = $class;
	if ( $visibility ) $wrapper_class[] = $visibility;
	if ( $align ) {
		$wrapper_class[] = 'full-width';
		$wrapper_class[] = 'text-' . $align;
	}
	if ( $style ) $wrapper_class[] = 'icon-style-' . $style;

	$link = get_permalink();

	if ( is_woocommerce_activated() ) {
		if ( is_shop() ) {
			$link = get_permalink( wc_get_page_id( 'shop' ) );
		}
		if ( is_product_category() || is_category() ) {
			$link = get_category_link( get_queried_object()->term_id );
		}
	}

	if ( is_home() && ! is_front_page() ) {
		$link = get_permalink( get_option( 'page_for_posts' ) );
	}

	$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
	$share_img      = $featured_image ? $featured_image['0'] : '';
	$post_title     = rawurlencode( get_the_title() );
	$whatsapp_text  = $post_title . ' - ' . $link;
	$window_open    = "window.open(this.href,this.title,'width=500,height=500,top=300px,left=300px'); return false;";

	if ( $title ) $title = '<span class="share-icons-title">' . $title . '</span>';
	// Get custom theme style.
	if ( ! $style ) $style = get_theme_mod( 'social_icons_style', 'outline' );

	$classes = get_flatsome_icon_class( $style );
	$classes = $classes . ' tooltip';

	$share = get_theme_mod( 'social_icons', array( 'facebook', 'twitter', 'email', 'linkedin', 'pinterest', 'whatsapp' ) );

	// Scale.
	if ( $scale ) $scale = 'style="font-size:' . $scale . '%"';

	// Fix old deprecated.
	if ( ! isset( $share[0] ) ) {
		$fix_share = array();
		foreach ( $share as $key => $value ) {
			if ( $value == '1' ) $fix_share[] = $key;
		}
		$share = $fix_share;
	}

	$share_links = apply_filters( 'flatsome_share_links', array(
		'whatsapp'  => array(
			'enabled'  => in_array( 'whatsapp', $share, true ),
			'atts'     => array(
				'href'        => 'whatsapp://send?text=' . $whatsapp_text,
				'data-action' => 'share/whatsapp/share',
				'class'       => $classes . ' whatsapp show-for-medium',
				'title'       => esc_attr__( 'Share on WhatsApp', 'flatsome' ),
				'aria-label'  => esc_attr__( 'Share on WhatsApp', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-whatsapp' ),
			'priority' => 10,
		),
		'facebook'  => array(
			'enabled'  => in_array( 'facebook', $share, true ),
			'atts'     => array(
				'href'       => 'https://www.facebook.com/sharer.php?u=' . $link,
				'data-label' => 'Facebook',
				'onclick'    => $window_open,
				'rel'        => 'noopener noreferrer nofollow',
				'target'     => '_blank',
				'class'      => $classes . ' facebook',
				'title'      => esc_attr__( 'Share on Facebook', 'flatsome' ),
				'aria-label' => esc_attr__( 'Share on Facebook', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-facebook' ),
			'priority' => 20,
		),
		'twitter'   => array(
			'enabled'  => in_array( 'twitter', $share, true ),
			'atts'     => array(
				'href'       => 'https://twitter.com/share?url=' . $link,
				'onclick'    => $window_open,
				'rel'        => 'noopener noreferrer nofollow',
				'target'     => '_blank',
				'class'      => $classes . ' twitter',
				'title'      => esc_attr__( 'Share on Twitter', 'flatsome' ),
				'aria-label' => esc_attr__( 'Share on Twitter', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-twitter' ),
			'priority' => 30,
		),
		'email'     => array(
			'enabled'  => in_array( 'email', $share, true ),
			'atts'     => array(
				'href'       => add_query_arg( array(
					'subject' => $post_title,
					/* translators: %s: the share link. */
					'body'    => rawurlencode( sprintf( esc_html__( 'Check this out: %s', 'flatsome' ), $link ) ),
				), 'mailto:' ),
				'rel'        => 'nofollow',
				'class'      => $classes . ' email',
				'title'      => esc_attr__( 'Email to a Friend', 'flatsome' ),
				'aria-label' => esc_attr__( 'Email to a Friend', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-envelop' ),
			'priority' => 40,
		),
		'pinterest' => array(
			'enabled'  => in_array( 'pinterest', $share, true ),
			'atts'     => array(
				'href'       => add_query_arg( array(
					'url'         => $link,
					'media'       => $share_img,
					'description' => $post_title,
				), 'https://pinterest.com/pin/create/button' ),
				'onclick'    => $window_open,
				'rel'        => 'noopener noreferrer nofollow',
				'target'     => '_blank',
				'class'      => $classes . ' pinterest',
				'title'      => esc_attr__( 'Pin on Pinterest', 'flatsome' ),
				'aria-label' => esc_attr__( 'Pin on Pinterest', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-pinterest' ),
			'priority' => 50,
		),
		'vk'        => array(
			'enabled'  => in_array( 'vk', $share, true ),
			'atts'     => array(
				'href'       => 'https://vkontakte.ru/share.php?url=' . $link . '&title' . $post_title,
				'target'     => '_blank',
				'onclick'    => $window_open,
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $classes . ' vk',
				'title'      => esc_attr__( 'Share on VKontakte', 'flatsome' ),
				'aria-label' => esc_attr__( 'Share on VKontakte', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-vk' ),
			'priority' => 60,
		),
		'linkedin'  => array(
			'enabled'  => in_array( 'linkedin', $share, true ),
			'atts'     => array(
				'href'       => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $link . '&title=' . $post_title,
				'onclick'    => $window_open,
				'rel'        => 'noopener noreferrer nofollow',
				'target'     => '_blank',
				'class'      => $classes . ' linkedin',
				'title'      => esc_attr__( 'Share on LinkedIn', 'flatsome' ),
				'aria-label' => esc_attr__( 'Share on LinkedIn', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-linkedin' ),
			'priority' => 70,
		),
		'tumblr'    => array(
			'enabled'  => in_array( 'tumblr', $share, true ),
			'atts'     => array(
				'href'       => 'https://tumblr.com/widgets/share/tool?canonicalUrl=' . $link,
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $classes . ' tumblr',
				'onclick'    => $window_open,
				'title'      => esc_attr__( 'Share on Tumblr', 'flatsome' ),
				'aria-label' => esc_attr__( 'Share on Tumblr', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-tumblr' ),
			'priority' => 80,
		),
		'telegram'  => array(
			'enabled'  => in_array( 'telegram', $share, true ),
			'atts'     => array(
				'href'       => 'https://telegram.me/share/url?url=' . $link,
				'onclick'    => $window_open,
				'rel'        => 'noopener noreferrer nofollow',
				'target'     => '_blank',
				'class'      => $classes . ' telegram',
				'title'      => esc_attr__( 'Share on Telegram', 'flatsome' ),
				'aria-label' => esc_attr__( 'Share on Telegram', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-telegram' ),
			'priority' => 90,
		),
	), array( // phpcs:ignore PEAR.Functions.FunctionCallSignature.Indent
		'link'       => $link,
		'post_title' => $post_title,
		'classes'    => $classes,
		'on_click'   => $window_open,
		'image'      => $share_img,
	) );

	// Sort links based on priority.
	uasort( $share_links, 'flatsome_sort_on_priority' );

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $wrapper_class ) ); ?>" <?php echo $scale; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php
		echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		foreach ( $share_links as $key => $share_link ) {
			if ( isset( $share_link['enabled'] ) && $share_link['enabled'] == false ) continue;
			printf( '<a %1$s>%2$s</a>',
				flatsome_html_atts( $share_link['atts'] ),
				! empty( $share_link['icon'] ) ? $share_link['icon'] : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		?>
	</div>
	<?php
	$content = ob_get_clean();

	return flatsome_sanitize_whitespace_chars( $content );
}

add_shortcode( 'share', 'flatsome_share' );



