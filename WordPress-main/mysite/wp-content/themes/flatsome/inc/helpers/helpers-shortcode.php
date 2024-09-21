<?php

/**
 * Generate position classes for banner content.
 *
 * @param  string $axis
 * @param  string $default
 * @param  string $sm
 * @param  string $md
 * @return string
 */
function flatsome_position_classes ( $axis, $default, $sm, $md ) {
  $classes = array();

  if ( $md && ! strlen( $sm ) ) $sm = $md; // Small should inherit from medium if not set
  if ( ! strlen( $sm ) ) $sm = $default; // Inherit small from default value
  if ( ! strlen( $md ) ) $md = $default; // Inherit medium from default value

  $classes[] = $axis . $sm;
  $classes[] = 'md-' . $axis . $md;
  $classes[] = 'lg-' . $axis . $default;

  return implode( ' ', $classes );
}

function get_flatsome_repeater_start( $atts ) {
    $atts = wp_parse_args( $atts, array(
      'class' => '',
      'visibility' => '',
      'title' => '',
      'style' => '',
      'columns' => '',
      'columns__sm' => '',
      'columns__md' => '',
      'slider_nav_position' => '',
      'slider_bullets' => 'false',
      'slider_nav_color' => '',
      'auto_slide' => 'false',
	  'infinitive' => 'true',
      'format' => '',
	  'attrs' => '',
    ) );

	$row_classes      = array();
	$row_classes_full = array();

	if ( $atts['class'] ) {
		$row_classes[]      = $atts['class'];
		$row_classes_full[] = $atts['class'];
	}

	if ( $atts['visibility'] ) {
		$row_classes[]      = $atts['visibility'];
		$row_classes_full[] = $atts['visibility'];
	}

    if($atts['type'] == 'slider-full'){
      $atts['columns'] = false;
      $atts['columns__sm'] = false;
      $atts['columns__md'] = false;
    }

    if(empty($atts)) return;

    if(!empty($atts['filter'])){
      $row_classes[] = 'row-isotope';
    }

    $rtl = 'false';

    if(is_rtl()) {
      $rtl = 'true';
    }

    if(empty($atts['auto_slide'])) $atts['auto_slide'] = 'false';

    // Group slider cells
    $group_cells = '"100%"';

    // Add column classes
    if(!empty($atts['columns']) && $atts['type'] !== 'grid'){
      if($atts['columns'])  $row_classes[] = 'large-columns-'.$atts['columns'];

      if(empty($atts['columns__md']) && $atts['columns'] > 3) {$row_classes[] = 'medium-columns-3';}
      else{$row_classes[] = 'medium-columns-'.$atts['columns__md'];}

      if(empty($atts['columns__sm']) && $atts['columns'] > 2) {$row_classes[] = 'small-columns-2';}
      else{$row_classes[] = 'small-columns-'.$atts['columns__sm'];}
    }

    // Add Row spacing
    if(!empty($atts['row_spacing'])){
      $row_classes[] = 'row-'.$atts['row_spacing'];
    }

    // Add row width
    if(!empty($atts['row_width'])){
      if($atts['row_width'] == 'full-width') $row_classes[] = 'row-full-width';
    }

    // Add Shadows
    if(!empty($atts['depth'])){
       $row_classes[] = 'has-shadow';
          $row_classes_full[] = 'box-shadow-'.$atts['depth'];
          $row_classes[] = 'row-box-shadow-'.$atts['depth'];
    }
    if(!empty($atts['depth_hover'])){
       $row_classes[] = 'has-shadow';
          $row_classes_full[] = 'box-shadow-'.$atts['depth_hover'].'-hover';
          $row_classes[] = 'row-box-shadow-'.$atts['depth_hover'].'-hover';
    }

    if($atts['type'] == 'masonry'){
      wp_enqueue_script('flatsome-masonry-js');
      $row_classes[] = 'row-masonry';
    }

    if($atts['type'] == 'grid'){
      wp_enqueue_script('flatsome-masonry-js');
      $row_classes[] = 'row-grid';
    }

    if($atts['type'] == 'slider'){
      $row_classes[] = 'slider row-slider';

      if($atts['slider_style']) $row_classes[] = 'slider-nav-'.$atts['slider_style'];

      if($atts['slider_nav_position']) $row_classes[] = 'slider-nav-'.$atts['slider_nav_position'];

      if($atts['slider_nav_color']) $row_classes[] = 'slider-nav-'.$atts['slider_nav_color'];

      // Add slider push class to normal text boxes
      if(!$atts['style'] || $atts['style'] == 'default' || $atts['style'] == 'normal' || $atts['style'] == 'bounce') $row_classes[] = 'slider-nav-push';

      $slider_options = '{"imagesLoaded": true, "groupCells": '.$group_cells.', "dragThreshold" : 5, "cellAlign": "left","wrapAround": '.$atts['infinitive'].',"prevNextButtons": true,"percentPosition": true,"pageDots": '.$atts['slider_bullets'].', "rightToLeft": '.$rtl.', "autoPlay" : '.$atts['auto_slide'].'}';

    } else if($atts['type'] == 'slider-full'){
      $row_classes_full[] = 'slider slider-auto-height row-collapse';

      if($atts['slider_nav_position']) $row_classes_full[] = 'slider-nav-'.$atts['slider_nav_position'];

      if($atts['slider_style']) $row_classes_full[] = 'slider-nav-'.$atts['slider_style'];

      $slider_options = '{"imagesLoaded": true, "dragThreshold" : 5, "cellAlign": "left","wrapAround": '.$atts['infinitive'].',"prevNextButtons": true,"percentPosition": true,"pageDots": '.$atts['slider_bullets'].', "rightToLeft": '.$rtl.', "autoPlay" : '.$atts['auto_slide'].'}';
    }

	$row_classes_full = array_unique( $row_classes_full );
	$row_classes      = array_unique( $row_classes );

	$row_classes_full = implode( ' ', $row_classes_full );
	$row_classes      = implode( ' ', $row_classes );
  ?>

  <?php if($atts['title']){?>
  <div class="row">
    <div class="large-12 col">
      <h3 class="section-title"><span><?php echo $atts['title']; ?></span></h3>
    </div>
  </div>
  <?php } ?>

  <?php if($atts['type'] == 'slider') { // Slider grid ?>
  <div class="row <?php echo $row_classes; ?>"  data-flickity-options='<?php echo $slider_options; ?>' <?php echo $atts['attrs'] ?>>

  <?php } else if($atts['type'] == 'slider-full') { // Full slider ?>
  <div id="<?php echo $atts['id']; ?>" class="<?php echo $row_classes_full; ?>" data-flickity-options='<?php echo $slider_options; ?>'>

  <?php } else if($atts['type'] == 'masonry') { // Masonry grid ?>
  <div id="<?php echo $atts['id']; ?>" class="row <?php echo $row_classes; ?>" data-packery-options='{"itemSelector": ".col", "gutter": 0, "presentageWidth" : true}'>

  <?php } else if($atts['type'] == 'grid') { ?>
  <div id="<?php echo $atts['id']; ?>" class="row <?php echo $row_classes; ?>" data-packery-options='{"itemSelector": ".col", "gutter": 0, "presentageWidth" : true}'>

  <?php } else if($atts['type'] == 'blank') { //Blank type ?>
  <div class="container">

  <?php } else { // Normal Rows ?>
  <div class="row <?php echo $row_classes; ?>" <?php echo $atts['attrs'] ?>>
  <?php }
}

function get_flatsome_repeater_end($type){
  echo '</div>';
}

/* Fix Normal Shortcodes */
function flatsome_contentfix($content){
    if ( ! is_string( $content ) ) {
      return $content;
    }

    $fix = array (
            '<p>_____</p>' => '<div class="is-divider large"></div>',
            '<p>____</p>' => '<div class="is-divider medium"></div>',
            '<p>___</p>' => '<div class="is-divider small"></div>',
            '</div></p>' => '</div>',
            '<p><div' => '<div',
            ']<br />' => ']',
            '<br />[' => '[',
            '<p>[' => '[',
            ']</p>' => ']',

            // For Gutenberg blocks that is encoded by UX Builder.
            '&lt;!&#8211;' => '<!--',
            '&#8211;&gt;' => '-->',
    );

    return strtr( $content, $fix );
}

add_filter( 'the_content', 'flatsome_contentfix' );
add_filter( 'widget_text', 'flatsome_contentfix' );
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'the_excerpt', 'flatsome_contentfix' );
add_filter( 'the_excerpt', 'do_shortcode' );

/**
 * Remove whitespace characters \r\n\t\f\v from HTML between > and <
 * (Prevents wpautop from adding <p> or <br>)
 *
 * @param $html
 *
 * @return mixed
 */
function flatsome_sanitize_whitespace_chars( $html ) {
	$html = preg_replace( '/(?<=>)\s+(?=<)/', '', $html );
	return trim( $html );
}

// Get Shortcode Inline CSS
function get_shortcode_inline_css($args){
    $style = '';
      foreach ($args as $key => $value) {
        $unit = array_key_exists( 'unit', $value ) ? $value['unit'] : null;
        if($value['value']) $style .=  $value['attribute'].':'.$value['value'].$unit.';';
       }
    if($style) return 'style="'.$style.'"';
}


// Get Parallax Options
function get_parallax_option($strength){
    return 'data-velocity="0.'.$strength.'"';
}

function flatsome_get_image_url($id, $size = 'large'){

    if(!$id) return get_template_directory_uri().'/assets/img/missing.jpg';

    if (!is_numeric($id)) {
        return $id;
    } else {
        $image = wp_get_attachment_image_src($id, $size);
        $image = $image ? $image[0] : '';
        return $image;
    }
}

function flatsome_get_image( $id, $size = 'large', $alt = 'bg_image', $inline = false, $image_title = false ) {

    if(!$id) return '<img src="'.get_template_directory_uri().'/assets/img/missing.jpg'.'" />';

	$attr       = array();
	$title_html = '';

	if ( $image_title ) {
		$the_title     = get_the_title( $id );
		$attr['title'] = $the_title;
		$title_html    = ' title="' . esc_attr( $the_title ) . '" ';
	}

    if (!is_numeric($id)) {
        return '<img src="' . $id . '" alt="' . $alt . '"' . $title_html . '/>';
    } else {
        $meta = get_post_mime_type($id);

        if ( $meta == 'image/svg+xml' && $inline ){
          $file = get_attached_file( $id );
          if ( $file && file_exists( $file ) ) {
            return preg_replace(
              '#<script(.*?)>(.*?)</script>#is',
              '',
              file_get_contents( $file )
            );
          }
        }

        return wp_get_attachment_image( $id, $size, false, $attr );
    }
}

function flatsome_string_limit_words($string, $word_limit) {
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}



/* Create RGBA color of a #HEX color */
function flatsome_hex2rgba($color, $opacity = false) {
  $default = 'rgb(0,0,0)';
  //Return default if no color provided
  if(empty($color))
          return $default;

  //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
          $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
          if(abs($opacity) > 1)
            $opacity = 1.0;
          $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
          $output = 'rgb('.implode(",",$rgb).')';
        }
        //Return rgb(a) color string
        return $output;
}

function flatsome_fix_span($span){
  switch ($span) {
    case "1/1":
        $span = '12'; break;
    case "1/4":
        $span = '3'; break;
    case "2/4":
         $span ='6'; break;
    case "3/4":
        $span = '9'; break;
    case "1/3":
        $span = '4'; break;
    case "2/3":
         $span = '8'; break;
    case "1/2":
        $span = '6'; break;
    case "1/6":
        $span = '2'; break;
    case "2/6":
         $span = '4'; break;
    case "3/6":
        $span = '6'; break;
    case "4/6":
        $span = '8'; break;
    case "5/6":
        $span = '10'; break;
    case "1/12":
        $span = '1'; break;
    case "2/12":
        $span = '2'; break;
    case "3/12":
        $span = '3'; break;
    case "4/12":
        $span = '4'; break;
    case "5/12":
        $span = '5'; break;
    case "6/12":
        $span = '6'; break;
    case "7/12":
        $span = '7'; break;
    case "8/12":
        $span = '8'; break;
    case "9/12":
        $span = '9'; break;
    case "10/12":
        $span = '10'; break;
     case "11/12":
        $span = '11'; break;
  }
  return $span;
}


function flatsome_smart_links($link){
    if($link == 'shop' && is_woocommerce_activated()){
      $link = get_permalink( wc_get_page_id( 'shop' ) );
    }
    else if($link == 'cart' && is_woocommerce_activated()) {
      $link = wc_get_cart_url();
    }
    else if($link == 'checkout' && is_woocommerce_activated()) {
      $link = wc_get_checkout_url();
    }
    else if($link == 'account' && is_woocommerce_activated()){
      $link = get_permalink( get_option('woocommerce_myaccount_page_id') );
    }

    else if($link == 'home'){
      $link = get_home_url();
    }

    else if($link == 'blog'){
      $link = get_permalink( get_option( 'page_for_posts' ) );
    }

    else if($link == 'wishlist' && class_exists('YITH_WCWL')){
      $link =  YITH_WCWL()->get_wishlist_url();
    }
    // Get link by page title
    else if(strpos($link, '/') === false && !is_numeric($link)){
      $get_page = flatsome_get_page_by_title($link);
      if( $get_page ) $link = get_permalink($get_page->ID);
    }

	return esc_url( $link );
}

function flatsome_to_dashed($className) {
   return strtolower(preg_replace('/([\S\s])\s/', '$1-', $className));
}

function flatsome_to_underscore( $className ) {
	return strtolower( preg_replace( '/([\S\s])\s/', '$1_', $className ) );
}

/*
function flatsome_get_gradient($primary){ ?>
  <style>
    .target{
      background: <?php echo $primary; ?>
      background: -moz-linear-gradient(<?php echo $direction; ?>, <?php echo $primary; ?> 0%, <?php echo $secondary; ?> 100%);
      background: -webkit-linear-gradient(<?php echo $direction; ?>, <?php echo $primary; ?> 0%, <?php echo $secondary; ?> 100%);
      background: linear-gradient(<?php echo $direction; ?>, <?php echo $primary; ?> 0%, <?php echo $secondary; ?> 100%);
    }
  </style>
  <?php
} */

/**
 * Parse rel attribute values based on target value.
 * Adds 'noopener noreferrer' to rel when target is _blank.
 *
 * @param array $link_atts Link attributes 'target' and 'rel'.
 * @param bool  $trim      Trim start and end whitespaces?
 *
 * @return string Parsed target/rel string.
 */
function flatsome_parse_target_rel( array $link_atts, $trim = false ) {
	$attrs = array();

	if ( $link_atts['target'] == '_blank' ) {
		$attrs[]            = sprintf( 'target="%s"', esc_attr( $link_atts['target'] ) );
		$link_atts['rel'][] = 'noopener';
		$link_atts['rel'][] = 'noreferrer';
	}

	if ( isset( $link_atts['rel'] ) && is_array( $link_atts['rel'] ) && ! empty( array_filter( $link_atts['rel'] ) ) ) {
		$relations = array_unique( array_filter( $link_atts['rel'] ) );
		$rel       = implode( ' ', $relations );
		$attrs[]   = sprintf( 'rel="%s"', esc_attr( $rel ) );
	}

	$attrs = ! empty( $attrs ) ? ' ' . implode( ' ', $attrs ) . ' ' : ' ';

	return $trim ? trim( $attrs ) : $attrs;
}

/**
 * Returns the collection of ux_products shortcode box item hooks.
 *
 * @return array
 */
function flatsome_ux_product_box_items() {

	return array(
		'cat'              => array(
			'tag'      => 'woocommerce_shop_loop_item_title',
			'function' => 'flatsome_woocommerce_shop_loop_category',
		),
		'title'            => array(
			'tag'      => 'woocommerce_shop_loop_item_title',
			'function' => 'woocommerce_template_loop_product_title',
		),
		'rating'           => array(
			'tag'      => 'woocommerce_after_shop_loop_item_title',
			'function' => 'woocommerce_template_loop_rating',
		),
		'price'            => array(
			'tag'      => 'woocommerce_after_shop_loop_item_title',
			'function' => 'woocommerce_template_loop_price',
		),
		'add_to_cart'      => array(
			'tag'      => 'flatsome_product_box_after',
			'function' => 'flatsome_woocommerce_shop_loop_button',
		),
		'add_to_cart_icon' => array(
			'tag'      => 'flatsome_product_box_actions',
			'function' => 'flatsome_product_box_actions_add_to_cart',
		),
		'quick_view'       => array(
			'tag'      => 'flatsome_product_box_actions',
			'function' => 'flatsome_lightbox_button',
		),
	);
}

/**
 * Starts the toggle of box item hooks.
 *
 * @param array $items A collection of box items of a specific element.
 *
 * @return array $items Box items with additional data.
 */
function flatsome_box_item_toggle_start( $items ) {

	foreach ( $items as $key => $data ) {
		if ( isset( $data['show'] ) && ! $data['show'] ) {
			$priority = has_action( $data['tag'], $data['function'] );
			if ( $priority !== false ) {
				remove_action( $data['tag'], $data['function'], $priority );
				$items[ $key ]['priority'] = $priority;
				$items[ $key ]['re_hook']  = true;
			}
		}
	}

	return $items;
}

/**
 * Ends the toggle of box item hooks.
 *
 * @param array $items A collection of box items of a specific element.
 */
function flatsome_box_item_toggle_end( $items ) {
	foreach ( $items as $item ) {
		if ( isset( $item['re_hook'] ) && $item['re_hook'] == true ) {
			add_action( $item['tag'], $item['function'], $item['priority'] );
		}
	}
}

/**
 * Inserts items at offset in an associative array.
 *
 * @param array $array
 * @param array $values
 * @param int $offset
 * @return array
 */
function flatsome_array_insert( array $array, array $values, $offset ) {
  return array_slice( $array, 0, $offset, true ) + $values + array_slice( $array, $offset, null, true );
}
