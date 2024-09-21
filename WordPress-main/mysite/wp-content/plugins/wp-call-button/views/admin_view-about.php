<div id="poststuff-wp-call-button-code">
	<div id="post-body" class="metabox-holder">
		<div id="post-body-content">
			<div class="postbox">
				<div class="inside">
					<h3><?php esc_html_e( 'Hello and welcome to WP Call Button, the easiest way to add a call now button on your WordPress site.', 'wp-call-button' ); ?></h3>
					<p><?php esc_html_e( 'Our goal is to help small businesses get more customers, so they can grow and compete with the big guys.', 'wp-call-button' ); ?></p>
					<p>
					<?php
					printf(
						wp_kses(
							/* translators: %1$s - WPBeginner URL, %2$s - OptinMonster URL, %3$s - MonsterInsights URL. */
							__(
								'WP Call Button is brought to you by the team behind <a href="%1$s" target="_blank" rel="noopener noreferrer">WPBeginner</a> in collaboration with our friends at <a href="%2$s" target="_blank" rel="noopener noreferrer">Nextiva</a>, business phone service. Our team is also behind other popular plugins like <a href="%3$s" target="_blank" rel="noopener noreferrer">WPForms</a>,  <a href="%4$s" target="_blank" rel="noopener noreferrer">MonsterInsights</a>, <a href="%5$s" target="_blank" rel="noopener noreferrer">OptinMonster</a>, <a href="%6$s" target="_blank" rel="noopener noreferrer">WP Mail SMTP</a>, <a href="%7$s" target="_blank" rel="noopener noreferrer">SeedProd</a>, <a href="%8$s" target="_blank" rel="noopener noreferrer">RafflePress</a>, <a href="%9$s" target="_blank" rel="noopener noreferrer">TrustPulse</a> and more.',
								'wp-call-button'
							),
							[
								'a' => [
									'href'   => [],
									'rel'    => [],
									'target' => [],
								],
							]
						),
						'https://www.wpbeginner.com/?utm_source=wpcbplugin&utm_medium=pluginaboutpage&utm_campaign=aboutwpcb',
						'https://nextiva.7eer.net/JveBq',
						'https://wpforms.com/?utm_source=wpcbplugin&utm_medium=pluginaboutpage&utm_campaign=aboutwpcb',
						'https://www.monsterinsights.com/?utm_source=wpcbplugin&utm_medium=pluginaboutpage&utm_campaign=aboutwpcb',
						'https://optinmonster.com/?utm_source=wpcbplugin&utm_medium=pluginaboutpage&utm_campaign=aboutwpcb',
						'https://wpmailsmtp.com/?utm_source=wpcbplugin&utm_medium=pluginaboutpage&utm_campaign=aboutwpcb',
						'https://seedprod.com/?utm_source=wpcbplugin&utm_medium=pluginaboutpage&utm_campaign=aboutwpcb',
						'https://rafflepress.com/?utm_source=wpcbplugin&utm_medium=pluginaboutpage&utm_campaign=aboutwpcb',
						'https://trustpulse.com/?utm_source=wpcbplugin&utm_medium=pluginaboutpage&utm_campaign=aboutwpcb'
					);
					?>

					</p>
					<p><?php esc_html_e( 'Yup, we know a thing or two about building awesome products that customers love. Currently our plugins are running on over 9 million websites.', 'wp-call-button' ); ?><p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php require_once 'admin_view-plugins-holder.php'; ?>
