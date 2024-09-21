<?php
/**
 * Shape divider options.
 *
 * @package Flatsome
 */

return array(
	'type'    => 'group',
	'heading' => 'Shape divider',
	'options' => array(
		'divider_top'          => array(
			'type'       => 'select',
			'heading'    => 'Divider top',
			'full_width' => true,
			'default'    => '',
			'options'    => require __DIR__ . '/../values/dividers.php',
		),
		'divider_top_height'   => array(
			'type'       => 'scrubfield',
			'heading'    => 'Height',
			'conditions' => 'divider_top != ""',
			'responsive' => true,
			'default'    => '150px',
			'min'        => '0',
		),
		'divider_top_width'    => array(
			'type'       => 'slider',
			'heading'    => 'Width',
			'conditions' => 'divider_top != ""',
			'responsive' => true,
			'default'    => '100',
			'min'        => '100',
			'max'        => '300',
			'unit'       => '%',
		),
		'divider_top_fill'     => array(
			'type'       => 'colorpicker',
			'heading'    => 'Fill',
			'conditions' => 'divider_top != ""',
			'default'    => '',
			'format'     => 'rgb',
			'alpha'      => true,
			'position'   => 'bottom right',
			'helpers'    => require __DIR__ . '/../helpers/colors.php',
		),
		'divider_top_flip'     => array(
			'type'       => 'checkbox',
			'heading'    => 'Flip Y',
			'conditions' => 'divider_top != ""',
			'default'    => 'false',
		),
		'divider_top_to_front' => array(
			'type'       => 'checkbox',
			'heading'    => 'To front',
			'conditions' => 'divider_top != ""',
			'default'    => 'false',
		),
		'divider'              => array(
			'type'       => 'select',
			'heading'    => 'Divider bottom',
			'full_width' => true,
			'default'    => '',
			'options'    => require __DIR__ . '/../values/dividers.php',
		),
		'divider_height'       => array(
			'type'       => 'scrubfield',
			'heading'    => 'Height',
			'conditions' => 'divider != ""',
			'responsive' => true,
			'default'    => '150px',
			'min'        => '0',
		),
		'divider_width'        => array(
			'type'       => 'slider',
			'heading'    => 'Width',
			'conditions' => 'divider != ""',
			'responsive' => true,
			'default'    => '100',
			'min'        => '100',
			'max'        => '300',
			'unit'       => '%',
		),
		'divider_fill'         => array(
			'type'       => 'colorpicker',
			'heading'    => 'Fill',
			'conditions' => 'divider != ""',
			'default'    => '',
			'format'     => 'rgb',
			'alpha'      => true,
			'position'   => 'bottom right',
			'helpers'    => require __DIR__ . '/../helpers/colors.php',
		),
		'divider_flip'         => array(
			'type'       => 'checkbox',
			'heading'    => 'Flip Y',
			'conditions' => 'divider != ""',
			'default'    => 'false',
		),
		'divider_to_front'     => array(
			'type'       => 'checkbox',
			'heading'    => 'To front',
			'conditions' => 'divider != ""',
			'default'    => 'false',
		),
	),
);
