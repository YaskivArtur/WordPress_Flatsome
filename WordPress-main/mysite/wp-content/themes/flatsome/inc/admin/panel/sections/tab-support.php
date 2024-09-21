<?php
/**
 * Welcome screen getting started template
 */

?>
<div id="tab-support" class="coltwo-col panel flatsome-panel">
	<div class="cols">

	<div class="inner-panel" style="text-align: center;">
		<img style="width:100px; margin:30px 15px 0;" src="<?php echo get_template_directory_uri().'/inc/admin/panel/img/videos.png'; ?>"/>
		<h3>How-to Videos</h3>
		<p>Our How-to videos is perfect for learning about Flatsome and what is possible.</p>
        <a href="https://www.youtube.com/channel/UCeccZ4VQ8b5ZoMI-wU6qgFg" target="_blank" rel="noopener noreferrer" class="button button-primary">
        <?php _e( 'Open Videos', 'flatsome-admin' ); ?></a>
	</div>

	<div class="inner-panel" style="text-align: center;">
		<img style="width:100px; margin:30px 15px 0;" src="<?php echo get_template_directory_uri().'/inc/admin/panel/img/documentation.png'; ?>"/>
		<h3>Online Documentation</h3>
		<p>The first place you should look if you have any problems is our theme documentation.</p>
        <a href="http://uxthemes.helpscoutdocs.com" target="_blank" rel="noopener noreferrer" class="button button-primary">
        <?php _e( 'Open Documentation', 'flatsome-admin' ); ?></a>
	</div>

	<div class="inner-panel" style="text-align: center;">
	<img style="width:100px; margin:30px 15px 0;" src="<?php echo get_template_directory_uri().'/inc/admin/panel/img/emailsupport.png'; ?>"/>			<h3>Premium E-mail Support</h3>
		<p>All customers of Flatsome has access to premium e-mail support.</p>
		<?php if(!flatsome_is_theme_enabled())	{ ?>
			<a href="<?php echo admin_url().'admin.php?page=flatsome-panel';?>" class="button button-primary">Activate Theme to get support</a>
    	<?php } else { ?>
		<a href="https://themeforest.net/item/flatsome-multipurpose-responsive-woocommerce-theme/5484319/support" target="_blank" rel="noopener noreferrer" class="button button-primary">
			<?php _e( 'Send us a Support Ticket', 'flatsome-admin' ); ?>
		</a>
		<br><br><small><a href="https://themeforest.net/page/item_support_policy" target="_blank" rel="noopener noreferrer">What does support include?</a></small>
		<?php } ?>
	</div>

	</div>

	<div class="cols">

		<div class="inner-panel" style="text-align: center;">
			<h3>Flatsome Community</h3>
			<p>Join our community and get help from other Flatsome Users.</p>
		    <a href="//www.facebook.com/groups/flatsome/" target="_blank" rel="noopener noreferrer" class="button button-primary">
	        <?php _e( 'Join Community', 'flatsome-admin' ); ?></a>
		</div>

		<div class="inner-panel" style="text-align: center;">
			<h3>Flatsome Localization</h3>
			<p>Welcoming translations to make Flatsome available in your language.</p>
			<a href="//crowdin.com/project/l10n-flatsome" target="_blank" rel="noopener noreferrer" class="button button-primary">
				<?php _e( 'Join', 'flatsome-admin' ); ?></a>
		</div>

    <div class="inner-panel" style="text-align: center;">
      <h3>Feature Requests</h3>
      <p>Send Feature Request for Flatsome Theme and vote for the ones you like.</p>
      <a href="//uxthemes.canny.io/flatsome" target="_blank" rel="noopener noreferrer" class="button button-primary">
      <?php _e( 'Feature Requests', 'flatsome-admin' ); ?></a>
    </div>

	</div>

</div>
