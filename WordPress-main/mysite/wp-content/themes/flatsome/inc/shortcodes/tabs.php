<?php
// [tabgroup]
function ux_tabgroup( $params, $content = null, $tag = '' ) {
	$GLOBALS['tabs'] = array();
	$GLOBALS['tab_count'] = 0;
	$i = 1;

	extract(shortcode_atts(array(
		'id' => 'panel-'.rand(),
		'title' => '',
		'style' => 'line',
		'align' => 'left',
		'class' => '',
		'visibility' => '',
		'type' => '', // horizontal, vertical
		'nav_style' => 'uppercase',
		'nav_size' => 'normal',
		'history' => 'false',
		'event' => '',
	), $params));

	if($tag == 'tabgroup_vertical'){
		$type = 'vertical';
	}

	$content = do_shortcode( $content );

	$wrapper_class[] = 'tabbed-content';
	if ( $class ) $wrapper_class[] = $class;
  if ( $visibility ) $wrapper_class[] = $visibility;

	$classes[] = 'nav';

	if($style) $classes[] = 'nav-'.$style;
	if($type == 'vertical') $classes[] = 'nav-vertical';
	if($nav_style) $classes[] = 'nav-'.$nav_style;
	if($nav_size) $classes[] = 'nav-size-'.$nav_size;
	if($align) $classes[] = 'nav-'.$align;
	if($event) $classes[] = 'active-on-' . $event;


	$classes = implode(' ', $classes);

	$return = '';

	if( is_array( $GLOBALS['tabs'] )){

		foreach( $GLOBALS['tabs'] as $key => $tab ){
			$id = $tab['title'] ? flatsome_to_dashed( $tab['title'] ) : wp_rand();
			$active = $key == 0 ? ' active' : ''; // Set first tab active by default.
			$tabs[] = '<li id="tab-'.$id.'" class="tab'.$active.' has-icon" role="presentation"><a href="#tab_'.$id.'"'.($key != 0 ? ' tabindex="-1"' : '').' role="tab" aria-selected="'.($key == 0 ? 'true' : 'false').'" aria-controls="tab_'.$id.'"><span>'.$tab['title'].'</span></a></li>';
			$panes[] = '<div id="tab_'.$id.'" class="panel'.$active.' entry-content" role="tabpanel" aria-labelledby="tab-'.$id.'">'.do_shortcode( $tab['content'] ).'</div>';
			$i++;
		}
			if($title) $title = '<h4 class="uppercase text-'.$align.'">'.$title.'</h4>';
			$return = '
		<div class="'.implode(' ', $wrapper_class).'">
			'.$title.'
			<ul class="'.$classes.'" role="tablist">'.implode( "\n", $tabs ).'</ul><div class="tab-panels">'.implode( "\n", $panes ).'</div></div>';
	}


	return $return;
}

function ux_tab( $params, $content = null) {
	extract(shortcode_atts(array(
			'title' => '',
			'title_small' => ''
	), $params));

	$x = $GLOBALS['tab_count'];
	$GLOBALS['tabs'][ $x ] = array( 'title' => $title, 'content' => $content );
	$GLOBALS['tab_count']++;
}


add_shortcode('tabgroup', 'ux_tabgroup');
add_shortcode('tabgroup_vertical', 'ux_tabgroup');
add_shortcode('tab', 'ux_tab' );
