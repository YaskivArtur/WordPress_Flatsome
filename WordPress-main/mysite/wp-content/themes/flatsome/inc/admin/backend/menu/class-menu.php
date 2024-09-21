<?php
/**
 * Menu options handler
 *
 * @author     UX Themes
 * @package    Flatsome
 * @since      3.13.0
 */

namespace Flatsome\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Class Menu
 *
 * @package Flatsome\Admin
 */
class Menu {

	/**
	 * Option field identifiers
	 *
	 * @var array
	 */
	private $fields = array(
		'design',
		'width',
		'height',
		'block',
		'behavior',
		'icon-type',
		'icon-id',
		'icon-width',
		'icon-height',
		'icon-html',
	);

	/**
	 * Holds all UX blocks by ID.
	 *
	 * @var array
	 */
	private $ux_blocks = array();

	/**
	 * Menu constructor.
	 */
	public function __construct() {
		if ( flatsome_wp_version_check( '5.4' ) && apply_filters( 'flatsome_admin_menu_items_enabled', true ) ) {
			$this->ux_blocks = flatsome_get_block_list_by_id();

			add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
			add_action( 'wp_nav_menu_item_custom_fields', [ $this, 'add_menu_fields' ], 10, 5 );
			add_action( 'wp_update_nav_menu_item', [ $this, 'update_menu_fields' ], 10, 3 );
		}
	}

	/**
	 * Adds menu item custom fields.
	 *
	 * @param int       $item_id Menu item ID.
	 * @param \WP_Post  $item    Menu item data object.
	 * @param int       $depth   Depth of menu item. Used for padding.
	 * @param \stdClass $args    An object of menu item arguments.
	 * @param int       $id      Nav menu ID.
	 */
	public function add_menu_fields( $item_id, $item, $depth, $args, $id ) {  //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$design      = get_post_meta( $item_id, '_menu_item_design', true );
		$width       = get_post_meta( $item_id, '_menu_item_width', true );
		$height      = get_post_meta( $item_id, '_menu_item_height', true );
		$block       = get_post_meta( $item_id, '_menu_item_block', true );
		$behavior    = get_post_meta( $item_id, '_menu_item_behavior', true );
		$icon_type   = get_post_meta( $item_id, '_menu_item_icon-type', true );
		$icon_id     = get_post_meta( $item_id, '_menu_item_icon-id', true );
		$icon_width  = get_post_meta( $item_id, '_menu_item_icon-width', true );
		$icon_height = get_post_meta( $item_id, '_menu_item_icon-height', true );
		$icon_html   = get_post_meta( $item_id, '_menu_item_icon-html', true );

		ob_start();
		?>
		<?php $this->menu_divider(); ?>
		<div class="ux-menu-item-options">
			<h3>
				<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" style="margin-top: -3px; vertical-align: middle">
					<path d="M10.005 16.476L7.51713 13.9894L10.005 11.5027V7.11759L5.32346 11.7968L3.49745 9.97169L10.005 3.4674V0L0 10L10.005 20V16.476Z" fill="black"/>
					<g opacity="0.502624">
						<path opacity="0.387069" d="M9.995 16.476L12.4829 13.9894L9.995 11.5027V7.11759L14.6765 11.7968L16.5025 9.97169L9.995 3.4674V0L20 10L9.995 20V16.476Z" fill="black"/>
					</g>
				</svg>
				<?php esc_html_e( 'Flatsome menu item options', 'flatsome' ); ?>
			</h3>

			<div class="ux-menu-item-options__section-dropdown">
				<?php $this->section_title( __( 'Menu dropdown', 'flatsome' ) ); ?>
				<p class="description description-wide ux-menu-item-options__design">
					<label for="edit-menu-item-design-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Design', 'flatsome' ); ?><br>
						<select id="edit-menu-item-design-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="menu-item-design[<?php echo esc_attr( $item_id ); ?>]">
							<option value="default" <?php selected( $design, 'default', true ); ?>><?php esc_html_e( 'Default', 'flatsome' ); ?></option>
							<option value="custom-size" <?php selected( $design, 'custom-size', true ); ?>><?php esc_html_e( 'Default (custom size)', 'flatsome' ); ?></option>
							<option value="container-width" <?php selected( $design, 'container-width', true ); ?>><?php esc_html_e( 'Container width', 'flatsome' ); ?></option>
							<option value="full-width" <?php selected( $design, 'full-width', true ); ?>><?php esc_html_e( 'Full width', 'flatsome' ); ?></option>
						</select>
					</label>
					<?php $this->field_description( __( 'Select dropdown design.', 'flatsome' ) ); ?>
				</p>
				<p class="description description-thin ux-menu-item-options__width">
					<label for="edit-menu-item-width-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Width', 'flatsome' ); ?> (px)<br>
						<input type="number" id="edit-menu-item-width-<?php echo esc_attr( $item_id ); ?>" class="widefat" min="0" name="menu-item-width[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $width ); ?>">
					</label>
				</p>
				<p class="description description-thin ux-menu-item-options__height">
					<label for="edit-menu-item-height-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Height (optional)', 'flatsome' ); ?> (px)<br>
						<input type="number" id="edit-menu-item-height-<?php echo esc_attr( $item_id ); ?>" class="widefat" min="0" name="menu-item-height[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $height ); ?>">
					</label>
				</p>
				<p class="description description-wide ux-menu-item-options__block">
					<label for="edit-menu-item-block-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'UX Block', 'flatsome' ); ?><br>
						<select id="edit-menu-item-block-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="menu-item-block[<?php echo esc_attr( $item_id ); ?>]">
							<option value="" <?php selected( $block, '', true ); ?>><?php echo '-- None --'; ?></option>
							<?php foreach ( $this->ux_blocks as $block_id => $title ) : ?>
								<option value="<?php echo esc_attr( $block_id ); ?>" <?php selected( $block, $block_id, true ); ?>><?php echo esc_html( $title ); ?></option>
							<?php endforeach ?>
						</select>
					</label>
					<?php $this->field_description( 'Select UX Block as dropdown content.' ); ?>
				</p>
				<p class="description description-wide ux-menu-item-options__behavior">
					<label for="edit-menu-item-behavior-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Reveal', 'flatsome' ); ?><br>
						<select id="edit-menu-item-behavior-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="menu-item-behavior[<?php echo esc_attr( $item_id ); ?>]">
							<option value="hover" <?php selected( $behavior, 'hover', true ); ?>><?php esc_html_e( 'On hover', 'flatsome' ); ?></option>
							<option value="click" <?php selected( $behavior, 'click', true ); ?>><?php esc_html_e( 'On click', 'flatsome' ); ?></option>
						</select>
					</label>
				</p>
			</div>

			<?php $this->section_title( __( 'Menu icon', 'flatsome' ) ); ?>
			<p class="description description-wide ux-menu-item-options__icon-type">
				<label for="edit-menu-item-icon-type-<?php echo esc_attr( $item_id ); ?>">
					<?php esc_html_e( 'Icon type', 'flatsome' ); ?><br>
					<select id="edit-menu-item-icon-type-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="menu-item-icon-type[<?php echo esc_attr( $item_id ); ?>]">
						<option value="media" <?php selected( $icon_type, 'media', true ); ?>><?php esc_html_e( 'Media library', 'flatsome' ); ?></option>
						<option value="html" <?php selected( $icon_type, 'html', true ); ?>><?php esc_html_e( 'Custom content', 'flatsome' ); ?></option>
					</select>
				</label>
			</p>
			<div class="ux-menu-item-options__media hide-if-no-js">
				<p class="description description-thin ux-menu-item-options__media-control">
					<label for="edit-menu-item-icon-id-<?php echo esc_attr( $item_id ); ?>">
						<?php $this->media_view_html( $item_id, $icon_id, 'icon-id' ); ?>
					</label>
				</p>
				<p class="description description-thin ux-menu-item-options__icon-size">
					<label for="edit-menu-item-icon-width-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Width', 'flatsome' ); ?> (px)<br>
						<input type="number" id="edit-menu-item-icon-width-<?php echo esc_attr( $item_id ); ?>" class="widefat" min="0" name="menu-item-icon-width[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $icon_width ); ?>">
					</label>
					<label for="edit-menu-item-icon-height-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Height', 'flatsome' ); ?> (px)<br>
						<input type="number" id="edit-menu-item-icon-height-<?php echo esc_attr( $item_id ); ?>" class="widefat" min="0" name="menu-item-icon-height[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $icon_height ); ?>">
					</label>
					<?php $this->field_description( __( 'Icons default (empty) to 20x20.', 'flatsome' ) ); ?>
				</p>
			</div>
			<p class="description description-wide ux-menu-item-options__icon-html">
				<label for="edit-menu-item-icon-html-<?php echo esc_attr( $item_id ); ?>">
					<?php esc_html_e( 'Markup', 'flatsome' ); ?><br>
					<textarea id="edit-menu-item-icon-html-<?php echo esc_attr( $item_id ); ?>" class="widefat" rows="3" cols="20" name="menu-item-icon-html[<?php echo esc_attr( $item_id ); ?>]"><?php echo esc_attr( $icon_html ); ?></textarea>
				</label>
				<?php $this->field_description( __( 'Add any HTML, SVG or shortcode here.', 'flatsome' ) ); ?>
			</p>


		</div>
		<?php $this->menu_divider(); ?>
		<br>
		<?php
		echo ob_get_clean(); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Updates menu custom fields.
	 *
	 * @param int   $menu_id         ID of the updated menu.
	 * @param int   $menu_item_db_id ID of the updated menu item.
	 * @param array $args            An array of arguments used to update a menu item.
	 */
	public function update_menu_fields( $menu_id, $menu_item_db_id, $args ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		foreach ( $this->fields as $field ) {
			$key = 'menu-item-' . $field;

			if ( isset( $_POST[ $key ] ) && ! empty( $_POST[ $key ] ) && is_array( $_POST[ $key ] ) && isset( $_POST[ $key ][ $menu_item_db_id ] ) ) { //phpcs:ignore WordPress.Security
				$value = wp_unslash( $_POST[ $key ][ $menu_item_db_id ] ); //phpcs:ignore WordPress.Security
				update_post_meta( $menu_item_db_id, '_menu_item_' . $field, $value );
			}
		}
	}

	/**
	 * Divider template
	 */
	private function menu_divider() {
		echo '<p class="description description-wide" style="border-top: 1px solid #eee;margin: 1.5em 0;"></p>';
	}

	/**
	 * Section title.
	 *
	 * @param string $title The title.
	 */
	private function section_title( $title ) {
		echo '<h4 class="ux-menu-item-options__section-title description-wide">' . $title . '</h4>';
	}

	/**
	 * Menu option field description.
	 *
	 * @param string $description The description.
	 */
	private function field_description( $description ) {
		echo '<small class="description" style="font-style: italic; color: #999">' . $description . '</small>';
	}

	/**
	 * Media upload/remove view
	 *
	 * @param int    $item_id          Menu item ID.
	 * @param int    $image_id         Image ID.
	 * @param string $field_identifier Field identifier string.
	 */
	private function media_view_html( $item_id, $image_id, $field_identifier ) {
		$output = '';
		$image  = wp_get_attachment_image_src( $image_id, 'full' );

		$output .= sprintf( '<img class="placeholder %s" alt="" src="%s" />',
			! $image ? 'hidden' : '',
			$image ? esc_url( $image[0] ) : ''
		);
		$output .= sprintf( '<button type="button" class="upload-button button" data-item-id="%s">%s</button>',
			esc_attr( $item_id ),
			__( 'Select image', 'flatsome' )
		);
		$output .= sprintf( '<button type="button" class="remove-button button %s" data-item-id="%s">%s</button>',
			! $image ? 'hidden' : '',
			esc_attr( $item_id ),
			__( 'Remove', 'flatsome' )
		);
		$output .= sprintf( '<input type="hidden" id="edit-menu-item-%1$s-%2$s" name="menu-item-%1$s[%2$s]" value="%3$s">',
			$field_identifier,
			esc_attr( $item_id ),
			$image ? esc_attr( $image_id ) : ''
		);

		echo $output;
	}

	/**
	 * Register assets.
	 *
	 * @param string $hook The current hook.
	 */
	public function register_assets( $hook ) {
		if ( 'nav-menus.php' === $hook ) {
			$theme   = wp_get_theme( get_template() );
			$version = $theme->get( 'Version' );

			wp_enqueue_media();

			flatsome_enqueue_asset( 'flatsome-admin-menu', 'admin/admin-menu', array( 'jquery', 'nav-menu' ) );
			wp_enqueue_style( 'flatsome-admin-menu', get_template_directory_uri() . '/assets/css/admin/admin-menu.css', null, $version );
		}
	}
}

new Menu();
