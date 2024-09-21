<div class="notice notice-success is-dismissible <?php echo esc_attr( $this->plugin_slug ); ?>-notice-welcome">
	<p>
		<?php
		printf(
				/* translators: 1: Name of Plugin wrapped in bold tag. 2: Opening link tag. 3. Closing link tag. */
			esc_html__( 'Thanks for installing %1$s. %2$sClick here%3$s to configure the plugin.', 'wp-call-button' ),
			'<b>' . esc_html( $this->plugin_name ) . '</b>',
			'<a href="' . esc_url( $setting_page ) . '">',
			'</a>'
		);
		?>
	</p>
</div>
<script type="text/javascript">
	jQuery(document).ready( function($) {
		$(document).on( 'click', '.<?php echo esc_js( $this->plugin_slug ); ?>-notice-welcome button.notice-dismiss', function( event ) {
			event.preventDefault();
			$.post( '<?php echo esc_url( $ajax_url ); ?>', {
				action: '<?php echo esc_js( $this->plugin_slug ) . '_dismiss_dashboard_notices'; ?>',
				nonce: '<?php echo esc_js( wp_create_nonce( $this->plugin_slug . '-nonce' ) ); ?>'
			});
			$( '.<?php echo esc_js( $this->plugin_slug ); ?>-notice-welcome' ).remove();
		});
	});
</script>
