<?php
/**
 * Registers the `share` shortcode.
 *
 * @package flatsome
 */

/**
 * Renders the `follow` shortcode.
 *
 * @param array  $atts    An array of attributes.
 * @param string $content The shortcode content.
 * @param string $tag     The name of the shortcode, provided for context to enable filtering.
 *
 * @return string
 */
function flatsome_follow( $atts, $content = null, $tag = '' ) {
	extract( $atts = shortcode_atts(
		array(
			'title'      => '',
			'class'      => '',
			'visibility' => '',
			'style'      => 'outline',
			'align'      => '',
			'scale'      => '',
			'facebook'   => '',
			'instagram'  => '',
			'tiktok'     => '',
			'snapchat'   => '',
			'twitter'    => '',
			'linkedin'   => '',
			'email'      => '',
			'phone'      => '',
			'pinterest'  => '',
			'rss'        => '',
			'youtube'    => '',
			'flickr'     => '',
			'vkontakte'  => '',
			'px500'      => '',
			'telegram'   => '',
			'discord'    => '',
			'twitch'     => '',
			// Deprecated.
			'size'       => '',
		),
		$atts,
		$tag
	) );

	$wrapper_class = array( 'social-icons', 'follow-icons' );

	if ( $class ) $wrapper_class[]      = $class;
	if ( $visibility ) $wrapper_class[] = $visibility;
	if ( $align ) {
		$wrapper_class[] = 'full-width';
		$wrapper_class[] = 'text-' . $align;
	}

	$_social_links = array(
		'facebook'  => $facebook,
		'instagram' => $instagram,
		'tiktok'    => $tiktok,
		'snapchat'  => $snapchat,
		'twitter'   => $twitter,
		'email'     => $email,
		'phone'     => $phone,
		'pinterest' => $pinterest,
		'rss'       => $rss,
		'linkedin'  => $linkedin,
		'youtube'   => $youtube,
		'flickr'    => $flickr,
		'px500'     => $px500,
		'vkontakte' => $vkontakte,
		'telegram'  => $telegram,
		'twitch'    => $twitch,
		'discord'   => $discord,
	);

	$social_links        = apply_filters( "flatsome_shortcode_${tag}_social_links", $_social_links, $atts );
	$custom_social_links = array_diff( $_social_links, $social_links );
	$use_global_link     = count( array_filter( $social_links ) ) === 0 && count( array_filter( $custom_social_links ) ) === 0;

	// Use global follow links if non is set individually.
	if ( $use_global_link ) {
		$facebook  = get_theme_mod( 'follow_facebook' );
		$instagram = get_theme_mod( 'follow_instagram' );
		$tiktok    = get_theme_mod( 'follow_tiktok' );
		$snapchat  = get_theme_mod( 'follow_snapchat' );
		$twitter   = get_theme_mod( 'follow_twitter' );
		$email     = get_theme_mod( 'follow_email' );
		$phone     = get_theme_mod( 'follow_phone' );
		$pinterest = get_theme_mod( 'follow_pinterest' );
		$rss       = get_theme_mod( 'follow_rss' );
		$linkedin  = get_theme_mod( 'follow_linkedin' );
		$youtube   = get_theme_mod( 'follow_youtube' );
		$flickr    = get_theme_mod( 'follow_flickr' );
		$px500     = get_theme_mod( 'follow_500px' );
		$vkontakte = get_theme_mod( 'follow_vk' );
		$telegram  = get_theme_mod( 'follow_telegram' );
		$twitch    = get_theme_mod( 'follow_twitch' );
		$discord   = get_theme_mod( 'follow_discord' );
	}

	if ( $size === 'small' ) {
		$style = 'small';
	}
	$style = get_flatsome_icon_class( $style );

	// Scale.
	if ( $scale ) $scale = 'style="font-size:' . $scale . '%"';

	$follow_links = apply_filters( 'flatsome_follow_links', array(
		'facebook'  => array(
			'enabled'  => ! empty( $facebook ),
			'atts'     => array(
				'href'       => $facebook,
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'data-label' => 'Facebook',
				'class'      => $style . ' facebook tooltip',
				'title'      => esc_attr__( 'Follow on Facebook', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on Facebook', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-facebook' ),
			'priority' => 10,
		),
		'instagram' => array(
			'enabled'  => ! empty( $instagram ),
			'atts'     => array(
				'href'       => $instagram,
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'data-label' => 'Instagram',
				'class'      => $style . ' instagram tooltip',
				'title'      => esc_attr__( 'Follow on Instagram', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on Instagram', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-instagram' ),
			'priority' => 20,
		),
		'tiktok'    => array(
			'enabled'  => ! empty( $tiktok ),
			'atts'     => array(
				'href'       => $tiktok,
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'data-label' => 'TikTok',
				'class'      => $style . ' tiktok tooltip',
				'title'      => esc_attr__( 'Follow on TikTok', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on TikTok', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-tiktok' ),
			'priority' => 30,
		),
		'snapchat'  => array(
			'enabled'  => ! empty( $snapchat ),
			'atts'     => array(
				'href'       => '#',
				'data-open'  => '#follow-snapchat-lightbox',
				'data-color' => 'dark',
				'data-pos'   => 'center',
				'data-label' => 'SnapChat',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' snapchat tooltip',
				'title'      => esc_attr__( 'Follow on SnapChat', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on SnapChat', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-snapchat' ),
			'after'    => sprintf(
				'<div id="follow-snapchat-lightbox" class="mfp-hide"><div class="text-center">%1$s<p>%2$s</p></div></div>',
				do_shortcode( flatsome_get_image( $snapchat ) ),
				esc_html__( 'Point the SnapChat camera at this to add us to SnapChat.', 'flatsome' )
			),
			'priority' => 40,
		),
		'twitter'   => array(
			'enabled'  => ! empty( $twitter ),
			'atts'     => array(
				'href'       => $twitter,
				'data-label' => 'Twitter',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' twitter tooltip',
				'title'      => esc_attr__( 'Follow on Twitter', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on Twitter', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-twitter' ),
			'priority' => 50,
		),
		'email'     => array(
			'enabled'  => ! empty( $email ),
			'atts'     => array(
				'href'       => 'mailto:' . $email,
				'data-label' => 'E-mail',
				'target'     => '_blank',
				'rel'        => 'nofollow',
				'class'      => $style . ' email tooltip',
				'title'      => esc_attr__( 'Send us an email', 'flatsome' ),
				'aria-label' => esc_attr__( 'Send us an email', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-envelop' ),
			'priority' => 60,
		),
		'phone'     => array(
			'enabled'  => ! empty( $phone ),
			'atts'     => array(
				'href'       => 'tel:' . $phone,
				'data-label' => 'Phone',
				'target'     => '_blank',
				'rel'        => 'nofollow',
				'class'      => $style . ' phone tooltip',
				'title'      => esc_attr__( 'Call us', 'flatsome' ),
				'aria-label' => esc_attr__( 'Call us', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-phone' ),
			'priority' => 70,
		),
		'pinterest' => array(
			'enabled'  => ! empty( $pinterest ),
			'atts'     => array(
				'href'       => $pinterest,
				'data-label' => 'Pinterest',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' pinterest tooltip',
				'title'      => esc_attr__( 'Follow on Pinterest', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on Pinterest', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-pinterest' ),
			'priority' => 80,
		),
		'rss'       => array(
			'enabled'  => ! empty( $rss ),
			'atts'     => array(
				'href'       => $rss,
				'data-label' => 'RSS Feed',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' rss tooltip',
				'title'      => esc_attr__( 'Subscribe to RSS', 'flatsome' ),
				'aria-label' => esc_attr__( 'Subscribe to RSS', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-feed' ),
			'priority' => 90,
		),
		'linkedin'  => array(
			'enabled'  => ! empty( $linkedin ),
			'atts'     => array(
				'href'       => $linkedin,
				'data-label' => 'LinkedIn',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' linkedin tooltip',
				'title'      => esc_attr__( 'Follow on LinkedIn', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on LinkedIn', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-linkedin' ),
			'priority' => 100,
		),
		'youtube'   => array(
			'enabled'  => ! empty( $youtube ),
			'atts'     => array(
				'href'       => $youtube,
				'data-label' => 'YouTube',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' youtube tooltip',
				'title'      => esc_attr__( 'Follow on YouTube', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on YouTube', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-youtube' ),
			'priority' => 110,
		),
		'flickr'    => array(
			'enabled'  => ! empty( $flickr ),
			'atts'     => array(
				'href'       => $flickr,
				'data-label' => 'Flickr',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' flickr tooltip',
				'title'      => esc_attr__( 'Flickr', 'flatsome' ),
				'aria-label' => esc_attr__( 'Flickr', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-flickr' ),
			'priority' => 120,
		),
		'500px'     => array(
			'enabled'  => ! empty( $px500 ),
			'atts'     => array(
				'href'       => $px500,
				'data-label' => '500px',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' px500 tooltip',
				'title'      => esc_attr__( 'Follow on 500px', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on 500px', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-500px' ),
			'priority' => 130,
		),
		'vkontakte' => array(
			'enabled'  => ! empty( $vkontakte ),
			'atts'     => array(
				'href'       => $vkontakte,
				'data-label' => 'VKontakte',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' vk tooltip',
				'title'      => esc_attr__( 'Follow on VKontakte', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on VKontakte', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-vk' ),
			'priority' => 140,
		),
		'telegram'  => array(
			'enabled'  => ! empty( $telegram ),
			'atts'     => array(
				'href'       => $telegram,
				'data-label' => 'Telegram',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' telegram tooltip',
				'title'      => esc_attr__( 'Follow on Telegram', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on Telegram', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-telegram' ),
			'priority' => 150,
		),
		'twitch'    => array(
			'enabled'  => ! empty( $twitch ),
			'atts'     => array(
				'href'       => $twitch,
				'data-label' => 'Twitch',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' twitch tooltip',
				'title'      => esc_attr__( 'Follow on Twitch', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on Twitch', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-twitch' ),
			'priority' => 160,
		),
		'discord'   => array(
			'enabled'  => ! empty( $discord ),
			'atts'     => array(
				'href'       => $discord,
				'data-label' => 'Discord',
				'target'     => '_blank',
				'rel'        => 'noopener noreferrer nofollow',
				'class'      => $style . ' discord tooltip',
				'title'      => esc_attr__( 'Follow on Discord', 'flatsome' ),
				'aria-label' => esc_attr__( 'Follow on Discord', 'flatsome' ),
			),
			'icon'     => get_flatsome_icon( 'icon-discord' ),
			'priority' => 170,
		),
	), array( // phpcs:ignore PEAR.Functions.FunctionCallSignature.Indent
		'style'           => $style,
		'atts'            => $atts,
		'use_global_link' => $use_global_link,
	) );

	// Sort links based on priority.
	uasort( $follow_links, 'flatsome_sort_on_priority' );

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $wrapper_class ) ); ?>" <?php echo $scale; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php
		if ( $title ) echo "<span>{$title}</span>";
		foreach ( $follow_links as $follow_link ) {
			if ( isset( $follow_link['enabled'] ) && $follow_link['enabled'] == false ) continue;
			printf( '<a %1$s>%2$s</a>',
				flatsome_html_atts( $follow_link['atts'] ),
				! empty( $follow_link['icon'] ) ? $follow_link['icon'] : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			if ( ! empty( $follow_link['after'] ) ) {
				echo $follow_link['after']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		?>
	</div>
	<?php
	$content = ob_get_clean();

	return flatsome_sanitize_whitespace_chars( $content );
}

add_shortcode( 'follow', 'flatsome_follow' );
