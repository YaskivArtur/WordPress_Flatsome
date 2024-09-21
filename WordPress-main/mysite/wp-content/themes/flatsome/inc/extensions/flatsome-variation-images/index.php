<?php // @codingStandardsIgnoreLine


namespace Flatsome\Extensions;

defined( 'ABSPATH' ) || exit;


global $extensions_url;
require $extensions_url . '/flatsome-variation-images/includes/class-variation-images.php';

variation_images();
