<?php

// [row]
function ux_row($atts, $content = null) {
  extract( shortcode_atts( array(
    '_id' => 'row-'.rand(),
    'style' => '',
    'col_style' => '',
    'label' => '',
    'border_color' => '',
    'width' => '',
    'custom_width' => '',
    'class' => '',
    'visibility' => '',
    'v_align' => '',
    'h_align' => '',
    'depth' => '',
    'depth_hover' => '',
    // Paddings
    'padding' => '',
    'col_bg' => '',
	'col_bg_radius' => ''
  ), $atts ) );

  // Stop if visibility is hidden
  if($visibility == 'hidden') return;

  $classes[] = 'row';

  // Add Row style
  if($style) $classes[] = 'row-'.$style;

  // Add Row Width
  if($width == 'full-width') $classes[] = 'row-full-width';

  // Column Vertical Align
  if($v_align) $classes[] = 'align-'.$v_align;

  // Column Horizontal Align
  if($h_align) $classes[] = 'align-'.$h_align;

  // Column style
  if($col_style) $classes[] = 'row-'.$col_style;

  // Custom Class
  if($class) $classes[] = $class;
  if($visibility) $classes[] = $visibility;


  // Depth
  if($depth) $classes[] = 'row-box-shadow-'.$depth;
  if($depth_hover) $classes[] = 'row-box-shadow-'.$depth_hover.'-hover';

  // Add Custom Widths
  if($width !== 'custom'){
    $custom_width = '';
  } else{
    $custom_width = 'style="max-width:'.$custom_width.'"';
  }

  $args = array(
     'padding' => array(
        'selector' => '> .col > .col-inner',
        'property' => 'padding',
      ),
     'col_bg' => array(
        'selector' => '> .col > .col-inner',
        'property' => 'background-color',
      ),
	 'col_bg_radius' => array(
		 'selector' => '> .col > .col-inner',
		 'property' => 'border-radius',
		 'unit'      => 'px',
	 ),
  );

  $classes =  implode(" ", $classes);

  return '<div class="'.$classes.'" '.$custom_width.' id="'.$_id.'">'.do_shortcode( $content ).ux_builder_element_style_tag($_id, $args, $atts).'</div>';
}


// [col]
function ux_col($atts, $content = null) {
	extract( $atts = shortcode_atts( array(
		'_id' => 'col-'.rand(),
    'label' => '',
    'span' => '12',
    'span__md' => isset( $atts['span'] ) ? $atts['span'] : '',
    'span__sm' => '',
    'small' => '12',
    'visibility' => '',
    'divider' => '',
    'animate' => '',
    'padding' => '',
    'padding__md' => '',
    'padding__sm' => '',
    'margin' => '',
    'margin__md' => '',
    'margin__sm' => '',
    'tooltip' => '',
    'max_width' => '',
	'max_width__md' => '',
	'max_width__sm' => '',
    'hover' => '',
    'class' => '',
    'align' => '',
    'color' => '',
	'sticky' => '',
	'sticky_mode' => '',
    'parallax' => '',
    'force_first' => '',
    'bg' => '',
    'bg_color' => '',
    'bg_radius' => '',
    'depth' => '',
    'depth_hover' => '',
    'text_depth' => '',
	// Border Control.
    'border'        => '',
    'border_margin' => '',
    'border_style'  => '',
    'border_radius' => '',
    'border_color'  => '',
    'border_hover'  => '',
  ), $atts ) );

  // Hide if visibility is hidden
  if($visibility == 'hidden') return;

  $classes[] = 'col';
  $classes_inner[] = 'col-inner';

  // Fix old cols
  if(strpos($span, '/')) $span = flatsome_fix_span($span);

  // add custom class
  if($class) $classes[] = $class;
  if($visibility) $classes[] = $visibility;

  if($span__md) $classes[] = 'medium-'.$span__md;
  if($span__sm) $classes[] = 'small-'.$span__sm;
  if($span) $classes[] = 'large-'.$span;
  if ( $border_hover ) $classes[] = 'has-hover';

  // Force first position
  if($force_first) $classes[] = $force_first.'-col-first';

  // Add divider
  if($divider) $classes[] = 'col-divided';

  // Add Animation Class
  if($animate) { $animate = 'data-animate="'.$animate.'"'; }

  // Add Align Class
  if($align) $classes_inner[] = 'text-'.$align;

  // Add Hover Class
  if($hover) $classes[] = 'col-hover-'.$hover;

  // Add Depth Class
  if($depth) $classes_inner[] = 'box-shadow-'.$depth;
  if($depth_hover) $classes_inner[] = 'box-shadow-'.$depth_hover.'-hover';
  if($text_depth) $classes_inner[] = 'text-shadow-'.$text_depth;

  // Add Color class
  if($color == 'light') $classes_inner[] = 'dark';

  // Add Toolip Html
  $tooltip_class = '';
  if($tooltip) {
    $tooltip = 'title="'.$tooltip.'"';
    $classes[] = 'tip-top';
  }

  // Parallax
  if($parallax) $parallax = 'data-parallax-fade="true" data-parallax="'.$parallax.'"';

	// Inline CSS
	$css_args = array(
		'bg_color' => array(
			'attribute' => 'background-color',
			'value'     => $bg_color,
		),
	);

	$col_inner = $sticky ? '> .is-sticky-column > .is-sticky-column__inner > .col-inner' : '> .col-inner';

	$args = array(
		'padding'   => array(
			'selector' => $col_inner,
			'property' => 'padding',
		),
		'margin'    => array(
			'selector' => $col_inner,
			'property' => 'margin',
		),
		'bg_radius' => array(
			'selector' => $col_inner,
			'property' => 'border-radius',
			'unit'     => 'px',
		),
		'max_width' => array(
			'selector' => $col_inner,
			'property' => 'max-width',
		),
	);

	$classes          = implode( ' ', $classes );
	$classes_inner    = implode( ' ', $classes_inner );
	$attributes       = implode( ' ', array( $tooltip, $animate ) );
	$attributes_inner = $parallax;

	ob_start();
	?>

	<div id="<?php echo $_id; ?>" class="<?php echo esc_attr( $classes ); ?>" <?php echo $attributes; ?>>
		<?php if ( $sticky ) flatsome_sticky_column_open('', $sticky_mode ); ?>
		<div class="<?php echo esc_attr( $classes_inner ); ?>" <?php echo get_shortcode_inline_css( $css_args ); ?> <?php echo $attributes_inner; ?>>
			<?php require __DIR__ . '/commons/border.php'; ?>
			<?php echo do_shortcode( $content ); ?>
		</div>
		<?php if ( $sticky ) flatsome_sticky_column_close(); ?>
		<?php echo ux_builder_element_style_tag( $_id, $args, $atts ); ?>
	</div>

	<?php
	return ob_get_clean();
}

add_shortcode('col', 'ux_col');
add_shortcode('col_inner', 'ux_col');
add_shortcode('col_inner_1', 'ux_col');
add_shortcode('col_inner_2', 'ux_col');
add_shortcode('row', 'ux_row');
add_shortcode('row_inner', 'ux_row');
add_shortcode('row_inner_1', 'ux_row');
add_shortcode('row_inner_2', 'ux_row');
add_shortcode('background', 'ux_section');
add_shortcode('section', 'ux_section');
add_shortcode( 'section_inner', 'ux_section' );
