<?php
/**
 * Cart replace element.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

if(flatsome_option('catalog_mode_header')) echo '<li class="html cart-replace">'.do_shortcode(flatsome_option('catalog_mode_header')).'</li>';
