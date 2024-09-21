<?php
/**
 * Flatsome Structure.
 *
 * Header Structure.
 *
 * @package Flatsome\Structures
 */


/**
 * Header Viewport Meta.
 *
 * @return void
 */
function flatsome_viewport_meta() {
	echo apply_filters( 'flatsome_viewport_meta', '<meta name="viewport" content="width=device-width, initial-scale=1" />' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

add_action( 'wp_head', 'flatsome_viewport_meta', 1 );

/**
 * Header Navigation.
 *
 * @param string $nav    Navigation menu position.
 * @param bool   $walker Navigation Class.
 *
 * @return void
 */
function flatsome_header_nav( $nav, $walker = false ) {

	$admin_url = get_admin_url() . 'customize.php?url=' . get_permalink() . '&autofocus%5Bsection%5D=menu_locations';

	// Check if has Custom mobile menu.
	if ($nav == 'primary' && $walker == 'FlatsomeNavSidebar' && has_nav_menu( 'primary_mobile' )) $nav = 'primary_mobile';

	// If single page.
	$page_template                  = get_post_meta( get_the_ID(), '_wp_page_template', true );
	$default_template               = get_theme_mod( 'pages_template', 'default' );
	$is_single_nav_template         = ! empty( $page_template ) && strpos( $page_template, 'single-page-nav' ) !== false;
	$is_single_nav_default_template = ( empty( $page_template ) || $page_template == 'default' ) && strpos( $default_template, 'single-page-nav' ) !== false;
	$is_woo_page                    = is_woocommerce_activated() ? is_cart() || is_checkout() || is_account_page() : false;
	$needs_single_nav_helper        = ( $is_single_nav_template || $is_single_nav_default_template ) && $nav == 'primary' && is_page() && ! $is_woo_page;

	// Add single page nav helper.
	if ( $needs_single_nav_helper ) { ?>
	<li class="nav-single-page hidden"></li>
	<?php
	} elseif ( has_nav_menu( $nav ) ) {

		wp_nav_menu(array(
			'theme_location' => $nav,
			'container'      => false,
			'items_wrap'     => '%3$s',
			'depth'          => 0,
			'walker'         => new $walker(),
		));

	} else {
		echo '<li><a href="' . $admin_url . '">Assign a menu in Theme Options > Menus</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Navigation Builder.
 *
 * @param string $options Theme options.
 * @param string $type    Navigation type.
 *
 * @return void
 */
function flatsome_header_elements( $options, $type = '' ) {
	// Get options.
	$get_options = get_theme_mod( $options );

	$walker                         = 'FlatsomeNavDropdown';
	if ($type == 'sidebar') $walker = 'FlatsomeNavSidebar';

	// Set options.
	if ( is_array( $get_options ) ) {

		foreach ( $get_options as $key => $value ) {

			if ( $value == 'divider' || $value == 'divider_2' || $value == 'divider_3' || $value == 'divider_4' || $value == 'divider_5' ) {
				echo '<li class="header-divider"></li>';
			} elseif ( $value == 'html' || $value == 'html-2' || $value == 'html-3' || $value == 'html-4' || $value == 'html-5' ) {
				flatsome_get_header_html_element( $value );
			} elseif ( $value == 'block-1' || $value == 'block-2' ) {
				echo do_shortcode( '<li class="header-block"><div class="header-block-' . $value . '">[block id="' . get_theme_mod( 'header-' . $value ) . '"]</div></li>' );
			} elseif ( $value == 'nav-top' ) {
				flatsome_header_nav( 'top_bar_nav', $walker );
			} elseif ( $value == 'nav' ) {
				flatsome_header_nav( 'primary', $walker );
			} elseif ( $value == 'nav-secondary' ) {
				flatsome_header_nav( 'secondary', $walker );
			} elseif ( $value == 'nav-vertical' && $type === 'sidebar' ) {
				flatsome_header_nav( 'vertical', $walker );
			} elseif ( $value == 'wpml' ) {
				get_template_part( 'template-parts/header/partials/element-languages', $type );
			} else {
				get_template_part( 'template-parts/header/partials/element-' . $value, $type );
			}
			// Hooked Elements.
			do_action( 'flatsome_header_elements', $value );
		}
	}
}

/**
 * Get Header HTML Elements.
 *
 * @param string $value Header HTML elements.
 *
 * @return void
 */
function flatsome_get_header_html_element( $value ) {
	$mod = array(
		'name'    => '',
		'default' => '',
	);

	if ( $value == 'html' ) {
		$mod['name']    = 'topbar_left';
		$mod['default'] = '<strong class="uppercase">Add anything here or just remove it...</strong>';
	}
	if ( $value == 'html-2' ) $mod['name'] = 'topbar_right';
	if ( $value == 'html-3' ) $mod['name'] = 'top_right_text';
	if ( $value == 'html-4' ) $mod['name'] = 'nav_position_text_top';
	if ( $value == 'html-5' ) $mod['name'] = 'nav_position_text';

	if ( get_theme_mod( $mod['name'], $mod['default'] ) ) {
		echo '<li class="html custom html_' . $mod['name'] . '">' . do_shortcode( get_theme_mod( $mod['name'], $mod['default'] ) ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * FlatsomeNavDropdown Class.
 *
 * Extends Walker_Nav_Menu Class.
 */
class FlatsomeNavDropdown extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		$display_depth = $depth + 1;
		if ( $display_depth == '1' ) {
			$classes[] = 'nav-dropdown';
		} else {
			$classes[] = 'nav-column';
		}

		$classes = $this->ux_add_dropdown_classes( $classes );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Set Active Class.
		if ( in_array( 'current-menu-ancestor', $classes, true ) || in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) ) {
			$classes[] = 'active';
		}

		$design        = get_post_meta( $item->ID, '_menu_item_design', true );
		$width         = get_post_meta( $item->ID, '_menu_item_width', true );
		$height        = get_post_meta( $item->ID, '_menu_item_height', true );
		$block         = get_post_meta( $item->ID, '_menu_item_block', true );
		$behavior      = get_post_meta( $item->ID, '_menu_item_behavior', true );
		$icon_type     = get_post_meta( $item->ID, '_menu_item_icon-type', true );
		$icon_id       = get_post_meta( $item->ID, '_menu_item_icon-id', true );
		$icon_width    = get_post_meta( $item->ID, '_menu_item_icon-width', true );
		$icon_height   = get_post_meta( $item->ID, '_menu_item_icon-height', true );
		$icon_html     = get_post_meta( $item->ID, '_menu_item_icon-html', true );
		$is_top_level  = $depth == 0;
		$is_block_menu = ! empty( $block );

		if ( empty( $design ) ) {
			$design = 'default';
		}

		if ( $is_top_level ) {
			$classes[] = 'menu-item-design-' . $design;

			if ( $is_block_menu ) {
				$classes[] = 'menu-item-has-block';
			}
		}

		if ( $is_top_level && ( $is_block_menu || $item->has_children ) ) {
			$classes[] = 'has-dropdown';

			if ( 'click' === $behavior ) {
				$classes[] = 'nav-dropdown-toggle';
			}
		}

		if ( $item->has_children && $depth == 1 ) {
			$classes[] = 'nav-dropdown-col';
		}

		// LEGACY Add flatsome Icons.
		$menu_icon = '';
		if ( isset( $classes[0] ) && strpos( $classes[0], 'icon-' ) !== false ) {
			$menu_icon  = get_flatsome_icon( $classes[0] );
			$classes[0] = 'has-icon-left';
		}

		if ( $icon_type === 'media' && ! empty( $icon_id )
			 || $icon_type === 'html' && ! empty( $icon_html ) ) {
			$classes[] = 'has-icon-left';
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener noreferrer';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria_current The aria-current attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		// Check if menu item is in main menu.
		if ( $depth == 0 ) {
			// These lines adds your custom class and attribute.
			$attributes .= ' class="nav-top-link"';

			if ( in_array( 'has-dropdown', $classes, true ) ) {
				$attributes .= ' aria-expanded="false" aria-haspopup="menu"';
			}
		}

		// Image Column.
		if ( strpos( $class_names, 'image-column' ) !== false ) {
			$item_output  = '';
			$item_output .= '<a' . $attributes . ' class="dropdown-image-column">';
			$item_output .= '<img width="180" height="480" src="' . $item->description . '" title="' . apply_filters( 'the_title', $item->title, $item->ID ) . '" alt="' . apply_filters( 'the_title', $item->title, $item->ID ) . '"/>';
			$item_output .= '</a>';
		} elseif ( strpos( $class_names, 'category-column' ) !== false ) { // Category Image.
			$item_output = '<div class="category-images-preview">Loading</div>';

		} else {
			// Normal Items.
			$item_output  = $args->before;
			$item_output .= '<a' . $attributes . '>';

			// LEGACY Add menu icon.
			if ( $menu_icon ) {
				$item_output .= $menu_icon;
			}

			switch ( $icon_type ) {
				case 'media':
					if ( ! empty( $icon_id ) ) {
						$item_output .= sprintf( '<img class="%s" width="%s" height="%s" src="%s" alt="%s" />',
							'ux-menu-icon',
							$icon_width ? $icon_width : 20,
							$icon_height ? $icon_height : 20,
							wp_get_attachment_image_src( $icon_id )[0],
							get_post_meta( $icon_id, '_wp_attachment_image_alt', true )
						);
					}
					break;
				case 'html':
					if ( ! empty( $icon_html ) ) {
						$item_output .= do_shortcode( $icon_html );
					}
					break;
			}

			$item_output .= $args->link_before . $title . $args->link_after;

			// Add down arrow.
			$arrow_icon = '';
			if ( $is_top_level && ( $is_block_menu || $item->has_children ) ) {
				$arrow_icon = get_flatsome_icon( 'icon-angle-down' );
			}

			$item_output .= $arrow_icon . '</a>';
			$item_output .= $args->after;

			$css = '';
			if ( $is_top_level && $is_block_menu ) {
				$dropdown_classes = array( 'sub-menu', 'nav-dropdown' );
				$dropdown_classes = implode( ' ', $dropdown_classes );

				$item_output .= '<div class="' . esc_attr( $dropdown_classes ) . '">';
				$item_output .= flatsome_apply_shortcode( 'block', array( 'id' => $block ) );
				$item_output .= '</div>';
			}
			if ( $design == 'custom-size' && ! empty( $width ) ) {
				$css .= '#menu-item-' . $item->ID . ' > .nav-dropdown {';
				$css .= 'width: ' . $width . 'px;';
				if ( ! empty( $height ) ) {
					$css .= 'min-height: ' . $height . 'px;';
				}
				$css .= '}';
			}

			if ( $css != '' ) {
				$item_output .= '<style>';
				$item_output .= $css;
				$item_output .= '</style>';
			}
		}

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method should not be called directly, use the walk() method instead.
	 *
	 * @since 2.5.0
	 *
	 * @param object $element           Data object.
	 * @param array  $children_elements List of elements to continue traversing (passed by reference).
	 * @param int    $max_depth         Max depth to traverse.
	 * @param int    $depth             Depth of current element.
	 * @param array  $args              An array of arguments.
	 * @param string $output            Used to append additional content (passed by reference).
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		// Check, whether there are children for the given ID and append it to the element with a (new) ID.
		$element->has_children = isset( $children_elements[ $element->ID ] ) && ! empty( $children_elements[ $element->ID ] );

		$id_field = $this->db_fields['id'];
		$id       = $element->$id_field;

		// Remove children from block menu items.
		if ( get_post_meta( $id, '_menu_item_block', true ) ) {
			$this->unset_children( $element, $children_elements );
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	/**
	 * Add theme option based classes to a class array.
	 *
	 * @param array $classes The classes.
	 *
	 * @return array
	 */
	private function ux_add_dropdown_classes( array $classes ) {
		$classes[] = 'nav-dropdown-' . get_theme_mod( 'dropdown_style', 'default' );
		if ( get_theme_mod( 'dropdown_text' ) == 'dark' ) {
			$classes[] = 'dark';
		}
		if ( get_theme_mod( 'dropdown_text_style' ) == 'uppercase' ) {
			$classes[] = 'dropdown-uppercase';
		}

		return $classes;
	}
}

/**
 * FlatsomeNavSidebar Class.
 *
 * Extends Walker_Nav_Menu Class.
 *
 * Sidebar Navigation Walker.
 */
class FlatsomeNavSidebar extends Walker_Nav_Menu {

	/**
	 * Display Elements.
	 *
	 * @param object $element           Navigation elements.
	 * @param array  $children_elements Child navigation elements.
	 * @param int    $max_depth         Maximum depth level.
	 * @param int    $depth             Depth of menu item. Used for padding.
	 * @param array  $args              wp_nav_menu() arguments.
	 * @param string $output            Element output.
	 *
	 * @return void
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		// Check, whether there are children for the given ID and append it to the element with a (new) ID.
		$element->has_children = isset( $children_elements[ $element->ID ] ) && ! empty( $children_elements[ $element->ID ] );

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		$display_depth = $depth + 1; // because it counts the first submenu as 0.
		$classes[]     = 'nav-sidebar-ul';
		if ( $display_depth == '1' ) {
			$classes[] = 'children';
		}

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$icon_type   = get_post_meta( $item->ID, '_menu_item_icon-type', true );
		$icon_id     = get_post_meta( $item->ID, '_menu_item_icon-id', true );
		$icon_width  = get_post_meta( $item->ID, '_menu_item_icon-width', true );
		$icon_height = get_post_meta( $item->ID, '_menu_item_icon-height', true );
		$icon_html   = get_post_meta( $item->ID, '_menu_item_icon-html', true );

		// LEGACY if icon.
		if ( isset( $classes[0] ) && strpos( $classes[0], 'icon-' ) !== false ) $classes[0] = '';

		if ( $icon_type === 'media' && ! empty( $icon_id )
			 || $icon_type === 'html' && ! empty( $icon_html ) ) {
			$classes[] = 'has-icon-left';
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener noreferrer';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria_current The aria-current attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';

		switch ( $icon_type ) {
			case 'media':
				if ( ! empty( $icon_id ) ) {
					$item_output .= sprintf( '<img class="%s" width="%s" height="%s" src="%s" alt="%s" />',
						'ux-sidebar-menu-icon',
						$icon_width ? $icon_width : 20,
						$icon_height ? $icon_height : 20,
						wp_get_attachment_image_src( $icon_id )[0],
						get_post_meta( $icon_id, '_wp_attachment_image_alt', true )
					);
				}
				break;
			case 'html':
				if ( ! empty( $icon_html ) ) {
					$item_output .= do_shortcode( $icon_html );
				}
				break;
		}

		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/**
 * Flatsome header classes.
 *
 * @return void
 */
function flatsome_header_classes() {
	// Add / remove hooked classes.
	echo implode( ' ', apply_filters( 'flatsome_header_class', array() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Flatsome main classes.
 *
 * @return void
 */
function flatsome_main_classes() {
	// Add / remove hooked classes.
	echo implode( ' ', apply_filters( 'flatsome_main_class', array() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Flatsome header title classes.
 *
 * @return void
 */
function flatsome_header_title_classes() {
	// Add / remove hooked classes.
	echo implode( ' ', apply_filters( 'flatsome_header_title_class', array() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Flatsome add main classes.
 *
 * @param array $classes Current classes.
 *
 * @return array $classes
 */
function flatsome_add_main_classes( $classes ) {
	// Dark content.
	if ( get_theme_mod( 'content_color' ) == 'dark' ) {
		$classes[] = 'dark dark-page-wrapper';
	}

	return $classes;
}
add_filter( 'flatsome_main_class', 'flatsome_add_main_classes', 10 );

/**
 * Flatsome sticky header classes.
 *
 * @param array $classes Current classes.
 *
 * @return array $classes
 */
function flatsome_sticky_headers( $classes ) {

	$disable_sticky = false;

	// Disable if UX Builder is active.
	$disable_sticky = function_exists( 'ux_builder_is_active' ) && ux_builder_is_active();

	// Add Header width class.
	if (get_theme_mod( 'header_width' ) == 'full-width') $classes[] = 'header-full-width';

	// Disable sticky if no header element is set as sticky.
	if ( ! get_theme_mod( 'header_sticky', 1 ) && ! get_theme_mod( 'topbar_sticky' ) && ! get_theme_mod( 'bottombar_sticky', 1 ) ) {
		$disable_sticky = true;
	}

	// Add Sticky class.
	if ( ! $disable_sticky ) {
		$classes[] = 'has-sticky';
		$classes[] = 'sticky-' . get_theme_mod( 'sticky_style', 'jump' );

		if ( get_theme_mod( 'sticky_hide_on_scroll' ) ) {
			$classes[] = 'sticky-hide-on-scroll';
		}
	}

	return $classes;
}

add_filter( 'flatsome_header_class', 'flatsome_sticky_headers', 11 );





/**
 * Checks if top bar has elements for desktop and/or mobile.
 *
 * @return array $screens
 */
function flatsome_has_top_bar() {
	$screens = array(
		'large'  => false,
		'mobile' => false,
	);
	if ( get_theme_mod( 'topbar_show', 1 ) ) {
		if ( get_theme_mod( 'topbar_elements_center' ) || get_theme_mod( 'topbar_elements_left' ) || get_theme_mod( 'topbar_elements_right' ) ) {
			$screens['large'] = true;
		}
		if ( get_theme_mod( 'header_mobile_elements_top' ) ) {
			$screens['mobile'] = true;
		}
	}
	$screens['large_or_mobile'] = $screens['large'] || $screens['mobile'];
	$screens['mobile_only']     = ! $screens['large'] && $screens['mobile'];

	return $screens;
}

/**
 * Checks if bottom bar has elements for desktop and/or mobile.
 *
 * @return array $screens
 */
function flatsome_has_bottom_bar() {
	$screens = array(
		'large'  => false,
		'mobile' => false,
	);
	if ( get_theme_mod( 'header_elements_bottom_left' ) || get_theme_mod( 'header_elements_bottom_center' ) || get_theme_mod( 'header_elements_bottom_right' ) ) {
		$screens['large'] = true;
	}
	if ( get_theme_mod( 'header_mobile_elements_bottom' ) ) {
		$screens['mobile'] = true;
	}
	$screens['large_or_mobile'] = $screens['large'] || $screens['mobile'];
	$screens['mobile_only']     = ! $screens['large'] && $screens['mobile'];

	return $screens;
}

/**
 * Page Header inner classes.
 *
 * @param string $position Menu position.
 *
 * @return void
 */
function header_inner_class( $position ) {

	$classes      = null;
	$current_page = null;

	// Header main.
	if ( $position == 'main' ) {

		if ( get_theme_mod( 'logo_position' ) == 'center' ) {
			$classes[] = 'show-logo-center';
		}

		if ( ! get_theme_mod( 'header_sticky', 1 )) $classes[] = 'hide-for-sticky';

		if ( get_theme_mod( 'site_logo_sticky' ) ) {
			$classes[] = 'has-sticky-logo';
		}

		if (get_theme_mod( 'header_color' ) == 'dark') $classes[] = 'nav-dark';
	}

	// Header top.
	if ( $position == 'top' ) {
		if ( ! get_theme_mod( 'topbar_sticky' )) $classes[]               = 'hide-for-sticky';
		if (get_theme_mod( 'topbar_color', 'dark' ) == 'dark') $classes[] = 'nav-dark';
		if (get_theme_mod( 'topbar_elements_center' )) $classes[]         = 'flex-has-center';
		if ( ! get_theme_mod( 'header_mobile_elements_top' ) ) {
			$classes[] = 'hide-for-medium';
		} elseif ( get_theme_mod( 'header_mobile_elements_top' ) && flatsome_has_top_bar()['mobile_only'] ) {
			$classes[] = 'show-for-medium';
		}
	}

	// Header bottom.
	if ( $position == 'bottom' ) {
		if ( ! get_theme_mod( 'bottombar_sticky', 1 )) $classes[]              = 'hide-for-sticky';
		if (get_theme_mod( 'nav_position_color' ) == 'dark') $classes[]        = 'nav-dark';
		if (get_theme_mod( 'nav_position_color' ) == 'dark-header') $classes[] = 'nav-dark';
		if (get_theme_mod( 'header_elements_bottom_center' )) $classes[]       = 'flex-has-center';
		if ( ! get_theme_mod( 'header_mobile_elements_bottom' ) ) {
			$classes[] = 'hide-for-medium';
		} elseif ( get_theme_mod( 'header_mobile_elements_bottom' ) && flatsome_has_bottom_bar()['mobile_only'] ) {
			$classes[] = 'show-for-medium';
		}
	}

	// Dark nav on light headers.
	$page_template             = get_post_meta( get_the_ID(), '_wp_page_template', true );
	$default_template          = get_theme_mod( 'pages_template', 'default' );
	$is_light_template         = ! empty( $page_template ) && strpos( $page_template, 'light' ) !== false;
	$is_light_default_template = is_page() && ( empty( $page_template ) || $page_template == 'default' ) && strpos( $default_template, 'light' ) !== false;
	$needs_dark_nav            = ( $is_light_template || $is_light_default_template ) && $position !== 'top';

	if ( $needs_dark_nav && get_theme_mod( 'header_color', 'light' ) !== 'dark' ) {
		$classes[] = 'nav-dark toggle-nav-dark';
	}

	if ( ! $classes) $classes[] = '';

	echo implode( ' ', $classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Build Nav Classes.
 *
 * @param string $position Menu position.
 *
 * @return void
 */
function flatsome_nav_classes( $position ) {
	$classes[] = null;

	if ( $position == 'main' ) {
		if (get_theme_mod( 'nav_style' )) $classes[]   = 'nav-' . get_theme_mod( 'nav_style' );
		if (get_theme_mod( 'nav_size' )) $classes[]    = 'nav-size-' . get_theme_mod( 'nav_size' );
		if (get_theme_mod( 'nav_spacing' )) $classes[] = 'nav-spacing-' . get_theme_mod( 'nav_spacing' );
		if ( get_theme_mod( 'nav_uppercase', 1 ) ) {
			$classes[] = 'nav-uppercase';
		}
		if ( get_theme_mod( 'nav_body_overlay' ) ) $classes[] = 'nav-prompts-overlay';
	}

	if ( $position == 'bottom' ) {
		if (get_theme_mod( 'nav_style_bottom' )) $classes[]   = 'nav-' . get_theme_mod( 'nav_style_bottom' );
		if (get_theme_mod( 'nav_size_bottom' )) $classes[]    = 'nav-size-' . get_theme_mod( 'nav_size_bottom' );
		if (get_theme_mod( 'nav_spacing_bottom' )) $classes[] = 'nav-spacing-' . get_theme_mod( 'nav_spacing_bottom' );

		if ( get_theme_mod( 'nav_uppercase_bottom', 1 ) ) {
			$classes[] = 'nav-uppercase';
		}
		if ( get_theme_mod( 'nav_bottom_body_overlay' ) ) $classes[] = 'nav-prompts-overlay';
	}

	if ( $position == 'top' ) {
		$classes[] = 'nav-' . get_theme_mod( 'nav_style_top', 'divided' );
		if ( get_theme_mod( 'nav_top_body_overlay' ) ) $classes[] = 'nav-prompts-overlay';
		if ( get_theme_mod( 'nav_top_uppercase' ) ) $classes[] = 'nav-uppercase';
	}

	echo implode( ' ', $classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Flatsome HTML classes.
 *
 * @return void
 */
function flatsome_html_classes() {

	// Change Body Layouts.
	$classes[] = 'loading-site no-js';

	// Add background style.
	if (get_theme_mod( 'body_bg_type' ) == 'bg-full-size') $classes[] = 'bg-fill';

	echo implode( ' ', $classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Current classes.
 *
 * @return array $classes
 */
function flatsome_body_classes( $classes ) {

	// Change Body Layouts.
	if (get_theme_mod( 'body_layout' ))  $classes[]                   = get_theme_mod( 'body_layout' );
	if (get_theme_mod( 'box_shadow_header' )) $classes[]              = 'header-shadow';
	if (get_theme_mod( 'body_bg_type' ) == 'bg-full-size') $classes[] = 'bg-fill';
	if (get_theme_mod( 'box_shadow' )) $classes[]                     = 'box-shadow';
	if (get_theme_mod( 'flatsome_lightbox', 1 )) $classes[]           = 'lightbox';
	if (get_theme_mod( 'dropdown_arrow', 1 )) $classes[]              = 'nav-dropdown-has-arrow';
	if (get_theme_mod( 'dropdown_shadow', 1 )) $classes[]             = 'nav-dropdown-has-shadow';
	if (get_theme_mod( 'dropdown_border_enabled', 1 )) $classes[]     = 'nav-dropdown-has-border';
	if (get_theme_mod( 'parallax_mobile', 0 )) $classes[]             = 'parallax-mobile';

	if (
		'center' != get_theme_mod( 'mobile_overlay' ) &&
		'slide' == get_theme_mod( 'mobile_submenu_effect' )
	) {
		$levels    = get_theme_mod( 'mobile_submenu_levels', '1' );
		$classes[] = 'mobile-submenu-slide';
		$classes[] = 'mobile-submenu-slide-levels-' . $levels;
	}

	if ( 'toggle' === get_theme_mod( 'mobile_submenu_parent_behavior' ) ) {
		$classes[] = 'mobile-submenu-toggle';
	}

	// Add the selected page template classes if Default Template is selected.
	$page_template    = get_post_meta( get_the_ID(), '_wp_page_template', true );
	$default_template = get_theme_mod( 'pages_template', 'default' );
	if ( is_page() && ( empty( $page_template ) || $page_template == 'default' ) && $default_template !== 'default' ) {
		$classes[] = 'page-template-' . $default_template;
		$classes[] = 'page-template-' . $default_template . '-php';
	}

	return $classes;
}
add_filter( 'body_class', 'flatsome_body_classes' );

/**
 * Flatsome Dropdown classes.
 *
 * @return void
 */
function flatsome_dropdown_classes() {
	$class_names = array();

	// Add Dropdown Styles.
	$class_names[] = 'nav-dropdown-' . get_theme_mod( 'dropdown_style', 'default' );
	if ( get_theme_mod( 'dropdown_text' ) == 'dark' ) { $class_names[] = 'dark'; }
	if ( get_theme_mod( 'dropdown_text_style' ) == 'uppercase' ) { $class_names[] = 'dropdown-uppercase'; }

	echo implode( ' ', $class_names ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Add Header Backgrounds.
 *
 * @return void
 */
function flatsome_add_header_backgrounds() {
	$page_template = get_post_meta( get_the_ID(), '_wp_page_template', true );

	// Add BG image.
	echo '<div class="header-bg-image fill"></div>';

	// Add BG Color.
	echo '<div class="header-bg-color fill"></div>';

	// Add BG shade to transparent headers.
	if ( ! empty( $page_template ) && strpos( $page_template, 'transparent' ) && get_theme_mod( 'header_bg_transparent_shade' ) ) {
		echo '<div class="shade shade-top hide-for-sticky fill"></div>';
	}
}
add_action( 'flatsome_header_background', 'flatsome_add_header_backgrounds', 10 );

/**
 * Add js class to header if JS is enabled.
 *
 * @return void
 */
function flatsome_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'flatsome_javascript_detection', 0 );

/**
 * Insert custom header script.
 *
 * @return void
 */
function flatsome_custom_header_js() {
	if ( get_theme_mod( 'html_scripts_header' ) && ! is_admin() ) {
		echo get_theme_mod( 'html_scripts_header' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_head', 'flatsome_custom_header_js' );

/**
 * Insert custom body top script.
 *
 * @return void
 */
function flatsome_after_body_open() {
	if ( get_theme_mod( 'html_scripts_after_body' ) && ! is_admin() ) {
		echo get_theme_mod( 'html_scripts_after_body' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'flatsome_after_body_open', 'flatsome_after_body_open' );

/**
 * Set logo position.
 *
 * @return void
 */
function flatsome_logo_position() {
	$classes   = array();
	$classes[] = 'logo-' . get_theme_mod( 'logo_position', 'left' );

	// Mobile logo position.
	$classes[] = 'medium-logo-' . get_theme_mod( 'logo_position_mobile', 'center' );

	echo implode( ' ', $classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * HTML after header.
 *
 * @return void
 */
function flatsome_html_after_header() {
	if ( get_theme_mod( 'html_after_header' ) ) {
		// AFTER HEADER HTML BLOCK.
		echo '<div class="header-block block-html-after-header z-1" style="position:relative;top:-1px;">';
		echo do_shortcode( get_theme_mod( 'html_after_header' ) );
		echo '</div>';
	}
}
add_action( 'flatsome_after_header', 'flatsome_html_after_header', 1 );

if ( get_theme_mod( 'site_loader' ) ) {
	/**
	 * Page loader.
	 *
	 * @return void
	 */
	function flatsome_add_page_loader() {
		if ( get_theme_mod( 'site_loader' ) == 'home' && is_front_page() ) {
			get_template_part( 'template-parts/header/page-loader' );
		} elseif ( get_theme_mod( 'site_loader' ) == 'all' ) {
			get_template_part( 'template-parts/header/page-loader' );
		}
	}
	add_action( 'flatsome_before_header', 'flatsome_add_page_loader', 1 );
}

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Fire the wp_body_open action.
	 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
	 *
	 * @since 3.9.1
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
