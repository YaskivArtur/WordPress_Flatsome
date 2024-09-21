import { PhoneIcon } from './phone-icon';

import classnames from 'classnames';

import {
	FontSizePicker,
	ToggleControl,
	PanelBody,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/*
 * @TODO: Import from block-editor instead.
 *
 * This will require bumping the minimum supported version of WP to
 * WordPress 5.3 (released late 2019) once done.
 */
const {
	InspectorControls,
	PanelColorSettings,
	RichText,
} = window.wpcallbtnshim;

const edit = ( { attributes, setAttributes } ) => {
	const btnText = attributes.btn_text;
	const btnColor = attributes.btn_color;
	const btnTextColor = attributes.btn_txt_color;
	const hidePhoneIcon = attributes.hide_phone_icon || false;
	const btnCenterAlign = attributes.btn_center_align || false;
	const btnFontSize = attributes.btn_font_size || 16;

	const btnColorsPalette = [
		{ name: __( 'Green', 'wp-call-button' ), color: '#269041' },
		{ name: __( 'Blue', 'wp-call-button' ), color: '#12A5F4' },
		{ name: __( 'Red', 'wp-call-button' ), color: 'red' },
		{ name: __( 'Yellow', 'wp-call-button' ), color: 'yellow' },
		{ name: __( 'Silver', 'wp-call-button' ), color: 'silver' },
		{ name: __( 'Gray', 'wp-call-button' ), color: 'gray' },
		{ name: __( 'Black', 'wp-call-button' ), color: 'black' },
	];
	const txtColorsInPalette = [
		{ name: __( 'White', 'wp-call-button' ), color: '#fff' },
		{ name: __( 'Black', 'wp-call-button' ), color: '#000' },
	];
	const fontSizes = [
		{ name: __( 'Small', 'wp-call-button' ), slug: 'small', size: 16 },
		{ name: __( 'Big', 'wp-call-button' ), slug: 'big', size: 24 },
	];

	const onChangeContent = ( newContent ) => {
		setAttributes( { btn_text: newContent } );
	};

	const onBtnTextColorChange = ( changes ) => {
		setAttributes( { btn_txt_color: changes } );
	};

	const onBtnColorChange = ( changes ) => {
		setAttributes( { btn_color: changes } );
	};

	const onCheckBoxControlChange = ( change ) => {
		setAttributes( { hide_phone_icon: change } );
	};

	const onBtnCenterCheckBoxControlChange = ( change ) => {
		setAttributes( { btn_center_align: change } );
		setAttributes( {
			class_for_call_btn:
				( hidePhoneIcon
					? 'wp-call-button-block-button-no-phone'
					: 'wp-call-button-block-button' ) +
				( change
					? ' wp-call-button-block-button-center'
					: ' wp-call-button-block-button-normal' ),
		} );
	};

	const onFontSizeChange = ( newfontSize ) => {
		setAttributes( { btn_font_size: newfontSize } );
	};

	const blockClassNames = () => {
		return classnames( {
			'wp-call-button-block-button': true,
			'wp-call-button-block-button-no-phone': hidePhoneIcon,
			'wp-call-button-block-button-center': btnCenterAlign,
			'wp-call-button-block-button-normal': ! btnCenterAlign,
			[ attributes.className ]: true,
		} );
	};

	return (
		<>
			<InspectorControls key="controls">
				<PanelBody>
					<FontSizePicker
						fontSizes={ fontSizes }
						onChange={ onFontSizeChange }
						value={ btnFontSize }
						disableCustomFontSizes={ true }
					/>
					<ToggleControl
						checked={ hidePhoneIcon }
						onChange={ onCheckBoxControlChange }
						label={ __( 'Hide phone icon?', 'wp-call-button' ) }
					/>
					<ToggleControl
						checked={ btnCenterAlign }
						onChange={ onBtnCenterCheckBoxControlChange }
						label={ __(
							'Center align call button?',
							'wp-call-button'
						) }
					/>
				</PanelBody>
				<PanelColorSettings
					title={ __( 'Color settings:', 'wp-call-button' ) } // New string.
					initialOpen={ true }
					colorSettings={ [
						{
							value: btnColor,
							onChange: onBtnColorChange,
							label: __( 'Background color:', 'wp-call-button' ),
							disableCustomColors: false,
							colors: btnColorsPalette,
						},
						{
							value: btnTextColor,
							onChange: onBtnTextColorChange,
							label: __( 'Text color:', 'wp-call-button' ),
							disableCustomColors: false,
							colors: txtColorsInPalette,
						},
					] }
				/>
			</InspectorControls>
			<p className={ blockClassNames() }>
				<span
					className="wp-call-button-in-btn"
					style={ {
						color: btnTextColor,
						background: btnColor,
						fontSize: btnFontSize
							? `${ btnFontSize }px`
							: undefined,
					} }
				>
					{ ! hidePhoneIcon && (
						<>
							<PhoneIcon />{ ' ' }
						</>
					) }
					<RichText
						key="richtext"
						tagName="span"
						onChange={ onChangeContent }
						value={ btnText }
						formattingControls={ [] }
						multiline={ false }
					/>
				</span>
			</p>
		</>
	);
};

export default edit;
