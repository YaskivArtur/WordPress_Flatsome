<div class="am-plugins-holder wpcbtn-clear">
	<?php foreach ( $this->plugins_holder->all_am_plugins as $key => $wpcb_am_plugin ) : ?>
		<?php
		$is_url_external = false;

		$data = $this->plugins_holder->get_about_plugins_data( $wpcb_am_plugin );

		if ( isset( $wpcb_am_plugin['pro'] ) && \array_key_exists( $wpcb_am_plugin['pro']['path'], \get_plugins() ) ) {
			$is_url_external = true;
			$wpcb_am_plugin  = $wpcb_am_plugin['pro'];

			$data = array_merge( $data, $this->plugins_holder->get_about_plugins_data( $wpcb_am_plugin, true ) );
		} elseif ( isset( $wpcb_am_plugin['pro'] )
			&& ! \array_key_exists( $wpcb_am_plugin['pro']['path'], \get_plugins() )
			&& \array_key_exists( $wpcb_am_plugin['path'], \get_plugins() )
		) {
			$is_url_external       = true;
			$wpcb_am_plugin['url'] = $wpcb_am_plugin['pro']['url'];

			$data                 = array_merge( $data, $this->plugins_holder->get_about_plugins_data( $wpcb_am_plugin, true ) );
			$data['action_class'] = str_replace(
				'disabled',
				'wpcallbtn-button-upgrade',
				(
				str_replace( 'button-secondary', 'button-primary', $data['action_class'] )
				)
			);
			$data['status_class'] = 'status-active-can-up';
			$data['action_text']  = esc_attr__( 'Upgrade to Pro', 'wp-call-button' );
		}
		?>

		<div class="plugin-item">
			<div class="details">
				<img src="<?php echo \esc_url( $wpcb_am_plugin['icon'] ); ?>" alt="wp-call-button-icon">
				<h5 class="plugin-name">
					<?php echo esc_html( $wpcb_am_plugin['name'] ); ?>
				</h5>
				<p class="plugin-desc">
					<?php echo esc_html( $wpcb_am_plugin['desc'] ); ?>
				</p>
			</div>
			<div class="actions wpcbtn-clear">
				<div class="status">
					<strong>
						<?php
						\printf(
							/* translators: %s - status HTML text. */
							\esc_html__( 'Status: %s', 'wp-call-button' ),
							'<span class="status-label ' . esc_attr( $data['status_class'] ) . '">' . esc_html( $data['status_text'] ) . '</span>'
						);
						?>
					</strong>
				</div>
				<div class="action-button">
					<?php
					$go_to_class  = '';
					$target_blank = false;
					if ( $is_url_external && $data['status_class'] === 'status-download' ) {
						$go_to_class = 'go_to';
					}
					if ( $is_url_external && $data['status_class'] === 'status-active-can-up' ) {
						$target_blank = true;
					}

					?>
					<a
						<?php echo $target_blank ? ' target="_blank" ' : ''; ?>
						<?php echo ( strpos( $data['action_class'], 'disabled' ) !== false ) ? '' : ' href="' . esc_url( $wpcb_am_plugin['url'] ) . '" '; ?>
						class="<?php echo \esc_attr( $data['action_class'] ); ?> <?php echo esc_attr( $go_to_class ); ?>"
						data-plugin="<?php echo esc_attr( $data['plugin_src'] ); ?>">
						<?php echo esc_html( $data['action_text'] ); ?>
					</a>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
