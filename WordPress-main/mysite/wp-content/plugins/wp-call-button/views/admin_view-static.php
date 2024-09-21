<div class="wpcbtn-clear static-page">
	<div class="wpcbtn-col-1">
		<div class="wpcbtn-row wpcbtn-row-heading wpcbtn-clear">
			<div class="wpcbtn-field">
				<h4><?php echo esc_html( $this->menu_sub_title ); ?></h4>
				<p><?php esc_html_e( 'Static call buttons can be embedded anywhere by using our easy to use shortcode. You can adjust the appearance of your button using the settings below.', 'wp-call-button' ); ?></p>
			</div>
		</div>
		<div class="wpcbtn-row wpcbtn-clear">
			<div class="wpcbtn-label text"><label for="wpcallbtn_button_text_static"><?php esc_html_e( 'Call Button Text', 'wp-call-button' ); ?></label></div>
			<div class="wpcbtn-field">
				<input type="text" id="wpcallbtn_button_text_static" aria-describedby="tagline-wpcallbtn_button_text_static" value="<?php echo esc_attr( $settings['wpcallbtn_button_text'] ); ?>" class="regular-text" />
				<p class="description" id="tagline-wpcallbtn_button_text_static"><?php esc_html_e( 'Enter a button text.', 'wp-call-button' ); ?></p>
			</div>
		</div>
		<div class="wpcbtn-row wpcbtn-clear">
			<div class="wpcbtn-label text"><label for="wpcallbtn_button_color_static"><?php esc_html_e( 'Call Button Color', 'wp-call-button' ); ?></label></div>
			<div class="wpcbtn-field">
				<input type="text" id="wpcallbtn_button_color_static" aria-describedby="tagline-wpcallbtn_button_color_static" value="<?php echo esc_attr( $settings['wpcallbtn_button_color'] ); ?>" class="regular-text">
				<p class="description" id="tagline-wpcallbtn_button_color_static"><?php esc_html_e( 'Choose a color for your Call Button.', 'wp-call-button' ); ?></p>
			</div>
		</div>
		<div class="wpcbtn-row wpcbtn-clear">
			<div class="wpcbtn-label"><label for="wpcallbtn_button_mobile_only"><?php esc_html_e( 'Hide the phone icon in your button?', 'wp-call-button' ); ?></label></div>
			<div class="wpcbtn-field">
				<input class="" type="checkbox" id="wpcallbtn_button_mobile_only" value="yes" <?php checked( 'yes', $settings['wpcallbtn_button_mobile_only'] ); ?> />
				<label for="wpcallbtn_button_mobile_only" class="switch"></label>
				<p class="description" id="tagline-wpcallbtn_button_mobile_only"><?php esc_html_e( 'Hide the phone icon from your call button.', 'wp-call-button' ); ?></p>
			</div>
		</div>
		<div class="wpcbtn-row wpcbtn-row-heading wpcbtn-clear">
			<div class="wpcbtn-field">
				<h4>Shortcode</h4>
				<p><?php esc_html_e( 'Use the shortcode below to add a Call button in your posts or pages.', 'wp-call-button' ); ?></p>
			</div>
		</div>
		<div class="wpcbtn-row wpcbtn-row-no-label wpcbtn-clear">
			<div class="wpcbtn-field">
				<input class="regular-text" data-clipboard-target="#wpcallbtn_button_shortcode" readonly="true" type="text" id="wpcallbtn_button_shortcode" value="[wp_call_button btn_text='Call Now' btn_color='#fff' hide_phone_icon='false']" />
				<input type="submit" data-clipboard-target="#wpcallbtn_button_shortcode" id="wpcallbtn-copy-btn" class="button button-primary wpcallbtn-button-green" value="<?php esc_attr_e( 'Copy', 'wp-call-button' ); ?>" style="padding: 6px 15px 7px;" />
				<p class="description"><?php esc_html_e( 'Click on the field above to copy the shortcode. Note: Phone Number will be pulled from the Sticky Call Button settings page.', 'wp-call-button' ); ?></p>
			</div>
		</div>
		<div class="wpcbtn-row wpcbtn-row-no-label wpcbtn-clear" style="padding-top: 25px;">
			<div class="wpcbtn-field">
				<p class="description" style="margin-top: 0;">
				<?php
				printf(
					/* translators: 1: Brand name 2: Brandname */
					esc_html__( 'If you’re using the new %1$s for %2$s, then you can use our call button block.', 'wp-call-button' ),
					'Gutenberg Block editor',
					'WordPress'
				);
				?>
				</p>
				<p class="description">
				<?php
				printf(
					/* translators: 1: Brand name linked to widgets page; 2: brand name. */
					esc_html__( 'We also have a %1$s if you wanted to display the call button in your theme\'s sidebar. For the block editor, then you can use our call button block.', 'wp-call-button' ),
					'<a href="' . admin_url( 'widgets.php' ) . '">WP Call Button Widget</a>'
				);
				?>
				</p>
			</div>
		</div>
	</div>
</div>
