<?php
/**
 * Directory warning.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

if ( $template !== 'flatsome' ) : ?>
<div class="notice notice-warning notice-alt inline" style="display:block!important;margin-bottom:15px!important">
	<p>
		<?php /* translators: 1. Template */ ?>
		<?php echo sprintf( __( 'An unusual theme directory name was detected: <em>%s</em>. The Flatsome parent theme should be installed in a directory named <em>flatsome</em> to ensure updates are handled correctly.', 'flatsome' ), $template ); ?>
	</p>
</div>
<?php endif; ?>
