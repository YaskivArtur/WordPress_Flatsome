<?php
/**
 * Welcome screen getting started template
 */

use function Flatsome\Admin\status;
?>

<div id="tab-activate" class="col cols panel flatsome-panel">
	<div class="cols">
		<div class="inner-panel col-span-3">
			<h3><?php esc_html_e( 'Theme registration', 'flatsome' ); ?></h3>
			<?php echo flatsome_envato()->admin->render_directory_warning(); ?>
			<?php echo flatsome_envato()->admin->render_registration_form(); ?>
		</div>
		<div class="inner-panel">
			<h3><?php esc_html_e( 'Status', 'flatsome' ); ?></h3>
			<?php status()->render_section_overview(); ?>
		</div>
	</div>
</div>


