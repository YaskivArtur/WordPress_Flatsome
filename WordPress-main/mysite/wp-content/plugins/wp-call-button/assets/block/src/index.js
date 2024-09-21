/* global wpcallbtn_block_vars */
import './index.scss';
import edit from './edit';

import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

registerBlockType( 'wp-call-button/wp-call-button-block', {
	title: __( 'WP Call Button', 'wp-call-button' ),
	description: __(
		'Adds a clickable phone link (quick call button), so people can easily call your business phone.',
		'wp-call-button'
	),
	icon: 'phone',
	category: 'common',
	keywords: [
		__( 'click to call button', 'wp-call-button' ),
		__( 'call now button', 'wp-call-button' ),
		__( 'phone', 'wp-call-button' ),
	],
	attributes: {
		btn_text: {
			type: 'string',
			default: wpcallbtn_block_vars.data_call_btn_text,
		},
		btn_color: {
			type: 'string',
			default: '#269041',
		},
		btn_txt_color: {
			type: 'string',
			default: '#fff',
		},
		hide_phone_icon: {
			type: 'boolean',
			default: false,
		},
		class_for_call_btn: {
			type: 'string',
			default:
				'wp-call-button-block-button wp-call-button-block-button-normal',
		},
		btn_font_size: {
			type: 'number',
			default: 16,
		},
		btn_center_align: {
			type: 'boolean',
			default: false,
		},
	},

	edit,
	save: () => null,
} );
