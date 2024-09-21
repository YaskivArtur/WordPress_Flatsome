<?php

function flatsome_team_member($atts, $content = null, $tag = ''){
  extract( $atts = shortcode_atts( array(
      '_id' => null,
      'class' => '',
      'visibility' => '',
      'img' => '',
      'name' => '',
      'title' => '',
      'icon_style' => 'outline',
      'facebook' => '',
      'instagram' => '',
      'tiktok' => '',
      'snapchat' => '',
      'twitter' => '',
      'email' => '',
      'phone' => '',
      'pinterest' => '',
      'linkedin' => '',
      'youtube' => '',
      'flickr' => '',
      'px500' => '',
	  'vkontakte'  => '',
      'telegram' => '',
	  'twitch' => '',
      'discord' => '',
      'style' => '',
      'depth' => '',
      'depth_hover' => '',
      'link' => '',
      'target' => '',
      'rel' => '',
      // Box styles
      'animate' => '',
      'text_pos' => 'bottom',
      'text_padding' => '',
      'text_bg' => '',
      'text_color' => '',
      'text_hover' => '',
      'text_align' => 'center',
      'text_size' => '',
      'image_size' => '',
      'image_width' => '',
      'image_radius' => '',
      'image_height' => '100%',
      'image_hover' => '',
      'image_hover_alt' => '',
      'image_overlay' => '',
  ), $atts, $tag ) );


    ob_start();

     // Set Classes
    $classes_box = array();
    $classes_text = array();
    $classes_image = array();
    $classes_image_inner = array();

    if ( $class ) $classes_box[] = $class;
    if ( $visibility ) $classes_box[] = $visibility;

  	$link_atts = array(
  		'target' => $target,
  		'rel'    => array( $rel ),
  	);

    // Fix old
    if($style == 'text-overlay'){
      $image_hover = 'zoom';
    }

    $style = str_replace('text-', '', $style);

    // Set box style
    $classes_box[] = 'has-hover';
    if($depth) $classes_box[] = 'box-shadow-'.$depth;
    if($depth_hover) $classes_box[] = 'box-shadow-'.$depth_hover.'-hover';

	$link_start = '<a href="' . $link . '"' . flatsome_parse_target_rel( $link_atts ) . '>';
	$link_end   = '</a>';

    if($style) $classes_box[] = 'box-'.$style;
    if($style == 'overlay') $classes_box[] = 'dark';
    if($style == 'shade') $classes_box[] = 'dark';
    if($style == 'badge') $classes_box[] = 'hover-dark';
    if($text_pos) $classes_box[] = 'box-text-'.$text_pos;
    if($style == 'overlay' && !$image_overlay) $image_overlay = 'rgba(0,0,0,.2)';

    if($image_hover)  $classes_image[] = 'image-'.$image_hover;
    if($image_hover_alt)  $classes_image[] = 'image-'.$image_hover_alt;

    if($image_height)  $classes_image_inner[] = 'image-cover';

    // Text classes
    if($text_hover) $classes_text[] = 'show-on-hover hover-'.$text_hover;
    if($text_align) $classes_text[] = 'text-'.$text_align;
    if($text_size) $classes_text[] = 'is-'.$text_size;
    if($text_color == 'dark') $classes_text[] = 'dark';

    if($animate) {$animate = 'data-animate="'.$animate.'"';}

     $css_args = array(
        array( 'attribute' => 'background-color', 'value' => $text_bg ),
        array( 'attribute' => 'padding', 'value' => $text_padding ),
     );

    $css_image = array(
        array( 'attribute' => 'width', 'value' => $image_width,'unit' => '%' ),
    );

    $css_image_inner = array(
        array( 'attribute' => 'border-radius', 'value' => $image_radius,'unit' => '%' ),
        array( 'attribute' => 'padding-top', 'value' => $image_height),
    );

	$social_links = apply_filters( "flatsome_shortcode_${tag}_social_links", array(
		'facebook'  => $facebook,
		'instagram' => $instagram,
		'tiktok'    => $tiktok,
		'snapchat'  => $snapchat,
		'twitter'   => $twitter,
		'email'     => $email,
		'phone'     => $phone,
		'pinterest' => $pinterest,
		'linkedin'  => $linkedin,
		'youtube'   => $youtube,
		'flickr'    => $flickr,
		'px500'     => $px500,
		'vkontakte' => $vkontakte,
		'telegram'  => $telegram,
		'twitch'    => $twitch,
		'discord'   => $discord,
	), $atts );
    ?>
    <div class="box has-hover <?php echo implode(' ', $classes_box); ?>" <?php echo $animate; ?>>

         <?php if($link) echo $link_start; ?>
         <div class="box-image <?php echo implode(' ', $classes_image); ?>" <?php echo get_shortcode_inline_css($css_image); ?>>
           <div class="box-image-inner <?php echo implode(' ', $classes_image_inner); ?>" <?php echo get_shortcode_inline_css($css_image_inner); ?>>
              <?php echo flatsome_get_image($img, $image_size); ?>
              <?php if($image_overlay) { ?><div class="overlay" style="background-color:<?php echo $image_overlay; ?>"></div><?php } ?>
           </div>
          </div>
         <?php if($link) echo $link_end; ?>

          <div class="box-text <?php echo implode(' ', $classes_text); ?>" <?php echo get_shortcode_inline_css($css_args); ?>>
                <div class="box-text-inner">
                  <h4 class="uppercase">
                    <span class="person-name"><?php echo $name; ?></span><br/>
                    <span class="person-title is-small thin-font op-7">
                      <?php echo $title; ?>
                    </span>
                  </h4>
					<?php if ( count( array_filter( $social_links ) ) > 0 ) echo flatsome_apply_shortcode( 'follow', array_merge( array( 'style' => $icon_style ), $social_links ) );
					if($style  !== 'overlay' && $style  !== 'shade') echo do_shortcode($content);
					?>
                </div>
          </div>
    </div>

	<?php if ( $style == 'overlay' || $style == 'shade' ) echo '<div class="team-member-content pt-half text-' . $text_align . '">' . do_shortcode( $content ) . '</div>'; ?>

    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode('team_member','flatsome_team_member');
