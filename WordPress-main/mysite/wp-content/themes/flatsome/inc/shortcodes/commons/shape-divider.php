<?php // phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
if ( $atts['divider_top'] ) :
	$classes_divider_top = array(
		'ux-shape-divider',
		'ux-shape-divider--top',
		'ux-shape-divider--style-' . $atts['divider_top'],
	);

	if ( $atts['divider_top_flip'] === 'true' ) $classes_divider_top[]     = 'ux-shape-divider--flip';
	if ( $atts['divider_top_to_front'] === 'true' ) $classes_divider_top[] = 'ux-shape-divider--to-front';
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes_divider_top ) ); ?>">
		<?php echo file_get_contents( get_template_directory() . '/assets/img/dividers/' . $atts['divider_top'] . '.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions, WordPress.Security.EscapeOutput ?>
	</div>
<?php endif; ?>

<?php
if ( $atts['divider'] ) :
	$classes_divider = array(
		'ux-shape-divider',
		'ux-shape-divider--bottom',
		'ux-shape-divider--style-' . $atts['divider'],
	);

	if ( $atts['divider_flip'] === 'true' ) $classes_divider[]     = 'ux-shape-divider--flip';
	if ( $atts['divider_to_front'] === 'true' ) $classes_divider[] = 'ux-shape-divider--to-front';
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes_divider ) ); ?>">
		<?php echo file_get_contents( get_template_directory() . '/assets/img/dividers/' . $atts['divider'] . '.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions, WordPress.Security.EscapeOutput ?>
	</div>
<?php endif; // phpcs:enable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>
