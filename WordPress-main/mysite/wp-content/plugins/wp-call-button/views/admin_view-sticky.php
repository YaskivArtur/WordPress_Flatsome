<form method="post" action="options-general.php?page=<?php echo esc_attr( $this->plugin_slug ); ?>" novalidate="novalidate">
	<input type="hidden" name="option_page" value="general">
	<input type="hidden" name="action" value="update">
	<?php wp_nonce_field( $this->plugin_slug . '-settings-nonce', '_wp_call_button_settings_nonce' ); ?>
	<div class="wpcbtn-row wpcbtn-row-heading wpcbtn-clear">
		<div class="wpcbtn-field">
			<h4><?php echo esc_html( $this->menu_sub_title ); ?></h4>
			<p><?php esc_html_e( 'Sticky call buttons scroll with the user, so you can get maximum attention to your Call Now button.', 'wp-call-button' ); ?></p>
		</div>
	</div>
	<div class="wpcbtn-row wpcbtn-clear">
		<div class="wpcbtn-label"><label for="wpcallbtn_button_enabled"><?php esc_html_e( 'Call Now Button Status', 'wp-call-button' ); ?></label></div>
		<div class="wpcbtn-field">
			<input class="wpcb-switch-checkbox" name="wpcallbtn_button_enabled" type="checkbox" id="wpcallbtn_button_enabled" value="yes" <?php checked( 'yes', $settings['wpcallbtn_button_enabled'] ); ?> />
			<label for="wpcallbtn_button_enabled" class="wpcb-switch-toggle button-status"></label>
			<br />
			<p class="description"><?php esc_html_e( 'Display the Call Now button on the website.', 'wp-call-button' ); ?></p>
		</div>
	</div>
	<div class="wpcbtn-row wpcbtn-clear">
		<div class="wpcbtn-label text"><label for="wpcallbtn_phone_num"><?php esc_html_e( 'Phone Number', 'wp-call-button' ); ?><span class="red">*</span></label></div>
		<div class="wpcbtn-field">
			<input type="tel" placeholder="" name="wpcallbtn_phone_num" type="text" id="wpcallbtn_phone_num" aria-describedby="tagline-wpcallbtn_phone_num" value="<?php echo esc_attr( $settings['wpcallbtn_phone_num'] ); ?>" class="regular-text" />
			<span id="valid-msg" class="hide phone-valid">✓ <?php esc_html_e( 'Valid', 'wp-call-button' ); ?></span>
			<span id="error-msg" class="hide phone-valid"></span>
			<p class="description" id="tagline-wpcallbtn_phone_num"><?php esc_html_e( 'Enter your business phone number with area code', 'wp-call-button' ); ?></p>
			<p class="description" id="tagline-wpcallbtn_phone_num">
				<?php
				printf(
					/* translators: 1: Link to recommended company */
					esc_html__( 'Don’t have a business phone number? We recommend using %1$s, a leading small business phone service provider.', 'wp-call-button' ),
					'<a href="https://nextiva.7eer.net/JveBq" target="_blank">Nextiva</a>'
				);
				?>
			</p>
			<p class="description" id="tagline-wpcallbtn_phone_num">
				<?php
				printf(
					/* translators: 1: Brand name; 2: Opening link tag; 3: closing link tag. */
					esc_html__( 'See why WPBeginner uses and recommends %1$s as the \'%2$sbest business phone service for small businesses%3$s\'', 'wp-call-button' ),
					'Nextiva',
					'<a href="https://www.wpbeginner.com/showcase/best-business-phone-services/?utm_source=wpcbplugin&utm_medium=pluginstickypage&utm_campaign=stickywpcb" target="_blank">',
					'</a>'
				);
				?>
			</p>
		</div>
	</div>
	<div class="wpcbtn-row wpcbtn-clear">
		<div class="wpcbtn-label text"><label for="wpcallbtn_button_text"><?php esc_html_e( 'Call Button Text', 'wp-call-button' ); ?></label></div>
		<div class="wpcbtn-field">
			<input name="wpcallbtn_button_text" type="text" id="wpcallbtn_button_text" aria-describedby="tagline-wpcallbtn_button_text" value="<?php echo esc_attr( $settings['wpcallbtn_button_text'] ); ?>" class="regular-text" />
			<p class="description" id="tagline-wpcallbtn_button_text"><?php esc_html_e( 'Enter a button text. Works only if the <b>Call Button Position</b> is set to <code>Full Width</code> below', 'wp-call-button' ); ?></p>
		</div>
	</div>
	<div class="wpcbtn-row wpcbtn-positions wpcbtn-clear">
		<div class="wpcbtn-label"><label for="wpcallbtn_button_position"><?php esc_html_e( 'Call Button Position', 'wp-call-button' ); ?></label></div>
		<div class="wpcbtn-field">
			<div class="wpcallbtn-positions">
				<label class="opt-d opt" title="<?php esc_attr_e( 'Bottom Full Width (with text)', 'wp-call-button' ); ?>" for="wpcallbtn_button_position-4">
					<input type="radio" id="wpcallbtn_button_position-4" name="wpcallbtn_button_position" value="bottom-full" <?php $this->radio_checked( 'bottom-full', $settings['wpcallbtn_button_position'] ); ?>>
					<?php esc_html_e( 'Full Width', 'wp-call-button' ); ?>
				</label>
				<label class=" opt-b opt" title="<?php esc_attr_e( 'Bottom Left', 'wp-call-button' ); ?>" for="wpcallbtn_button_position-2">
					<input type="radio" id="wpcallbtn_button_position-2" name="wpcallbtn_button_position" value="bottom-left" <?php $this->radio_checked( 'bottom-left', $settings['wpcallbtn_button_position'] ); ?>>
					<?php esc_html_e( 'Bottom Left', 'wp-call-button' ); ?>
				</label>
				<label class="opt-c opt" title="<?php esc_attr_e( 'Bottom Center', 'wp-call-button' ); ?>" for="wpcallbtn_button_position-3">
					<input type="radio" id="wpcallbtn_button_position-3" name="wpcallbtn_button_position" value="bottom-center" <?php $this->radio_checked( 'bottom-center', $settings['wpcallbtn_button_position'] ); ?>>
					<?php esc_html_e( 'Bottom Center', 'wp-call-button' ); ?>
				</label>
				<label class="opt-a opt" title="<?php esc_attr_e( 'Bottom Right', 'wp-call-button' ); ?>" for="wpcallbtn_button_position-1">
					<input type="radio" id="wpcallbtn_button_position-1" name="wpcallbtn_button_position" value="bottom-right" <?php $this->radio_checked( 'bottom-right', $settings['wpcallbtn_button_position'] ); ?>>
					<?php esc_html_e( 'Bottom Right', 'wp-call-button' ); ?>
				</label>
				<div style="clear: both;"></div>
			</div>
			<p class="description" id="tagline-wpcallbtn_button_position"><?php esc_html_e( 'Determines how the sticky call button appears on your website.', 'wp-call-button' ); ?></p>
		</div>
	</div>
	<div class="wpcbtn-row wpcbtn-clear">
		<div class="wpcbtn-label text"><label for="wpcallbtn_button_color"><?php esc_html_e( 'Call Button Color', 'wp-call-button' ); ?></label></div>
		<div class="wpcbtn-field">
			<input name="wpcallbtn_button_color" type="text" id="wpcallbtn_button_color" aria-describedby="tagline-wpcallbtn_button_color" value="<?php echo esc_attr( $settings['wpcallbtn_button_color'] ); ?>" class="regular-text">
			<p class="description" id="tagline-wpcallbtn_button_color"><?php esc_html_e( 'Choose a color for your Call Button.', 'wp-call-button' ); ?></p>
		</div>
	</div>
	<div class="wpcbtn-row wpcbtn-clear">
		<div class="wpcbtn-label"><label for="wpcallbtn_button_filter_ids"><?php esc_html_e( 'Call Button Visibility', 'wp-call-button' ); ?></label></div>
		<div class="wpcbtn-field wpcbtn-field-radio-container">
			<div class="wpcallbtn-filter-type">
				<div class="radio-item wpcbtn-clear">
					<input type="radio" id="wpcallbtn_button_filter_type-3" name="wpcallbtn_button_filter_type" value="none" <?php $this->radio_checked( 'none', $settings['wpcallbtn_button_filter_type'] ); ?>>
					<label title="<?php esc_attr_e( 'Bottom Center', 'wp-call-button' ); ?>" for="wpcallbtn_button_filter_type-3"><?php esc_html_e( 'Show everywhere (Default)', 'wp-call-button' ); ?></label>
				</div>
				<div class="radio-item wpcbtn-clear">
					<input type="radio" id="wpcallbtn_button_filter_type-1" name="wpcallbtn_button_filter_type" value="show" <?php $this->radio_checked( 'show', $settings['wpcallbtn_button_filter_type'] ); ?>>
					<label title="<?php esc_attr_e( 'Only Show on above post IDs', 'wp-call-button' ); ?>" for="wpcallbtn_button_filter_type-1"><?php esc_html_e( 'Show only on certain posts / pages and Hide everywhere else', 'wp-call-button' ); ?></label>
				</div>
				<div class="items-show-only">
					<select multiple="multiple" style="width: 300px;" name="wpcallbtn_button_filter_ids_show[]" type="text" id="wpcallbtn_button_filter_ids_show" aria-describedby="tagline-wpcallbtn_button_filter_ids_show">
					<?php if ( isset( $settings['wpcallbtn_button_filter_ids_show'] ) && is_array( $settings['wpcallbtn_button_filter_ids_show'] ) ) : ?>
						<?php
						foreach ( $settings['wpcallbtn_button_filter_ids_show'] as $option ) :
							$option_arr = explode( '____', $option );
							?>
					<option value="<?php echo esc_attr( $option ); ?>" selected><?php echo esc_html( $option_arr[1] ) . '<em>(' . esc_html( $option_arr[2] ) . ')</em>'; ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
					</select>
					<p class="description" id="tagline-wpcallbtn_button_filter_ids_show"><?php esc_html_e( 'Choose posts, pages and / or custom post type posts', 'wp-call-button' ); ?></p>
				</div>
				<div class="radio-item wpcbtn-clear">
					<input type="radio" id="wpcallbtn_button_filter_type-2" name="wpcallbtn_button_filter_type" value="hide" <?php $this->radio_checked( 'hide', $settings['wpcallbtn_button_filter_type'] ); ?>>
					<label title="<?php esc_attr_e( 'Hide on above post IDs', 'wp-call-button' ); ?>" for="wpcallbtn_button_filter_type-2"><?php esc_html_e( 'Hide on certain posts / pages but show everywhere else', 'wp-call-button' ); ?></label>
				</div>
				<div class="items-hide-only">
					<select multiple="multiple" style="width: 300px;" name="wpcallbtn_button_filter_ids_hide[]" type="text" id="wpcallbtn_button_filter_ids_hide" aria-describedby="tagline-wpcallbtn_button_filter_ids_hide">
					<?php if ( isset( $settings['wpcallbtn_button_filter_ids_hide'] ) && is_array( $settings['wpcallbtn_button_filter_ids_hide'] ) ) : ?>
						<?php
						foreach ( $settings['wpcallbtn_button_filter_ids_hide'] as $option ) :
							$option_arr = explode( '____', $option );
							?>
							<option value="<?php echo esc_attr( $option ); ?>" selected><?php echo esc_html( $option_arr[1] ) . '<em>(' . esc_html( $option_arr[2] ) . ')</em>'; ?></option>
							<?php
						endforeach;
					endif;
					?>
					</select>
					<p class="description" id="tagline-wpcallbtn_button_filter_ids_hide"><?php esc_html_e( 'Choose posts, pages and / or custom post type posts', 'wp-call-button' ); ?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="wpcbtn-row wpcbtn-clear">
		<div class="wpcbtn-label"><label for="wpcallbtn_button_mobile_only"><?php esc_html_e( 'Show Call Button Only on Mobile devices?', 'wp-call-button' ); ?></label></div>
		<div class="wpcbtn-field">
			<input class="wpcb-switch-checkbox" name="wpcallbtn_button_mobile_only" type="checkbox" id="wpcallbtn_button_mobile_only" value="yes" <?php checked( 'yes', $settings['wpcallbtn_button_mobile_only'] ); ?> />
			<label for="wpcallbtn_button_mobile_only" class="wpcb-switch-toggle"></label>
		</div>
	</div>
	<p style="padding: 15px 0 0 0;"><input type="submit" name="submit" id="submit" class="button button-primary wpcallbtn-button-green" value="<?php esc_attr_e( 'Save Changes', 'wp-call-button' ); ?>" /></p>
</form>
