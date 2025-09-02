/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { InspectorControls, InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { Panel, PanelBody, PanelRow, RadioControl } from '@wordpress/components';
import Media from './components/Media.jsx';
import Popup from './components/Popup.jsx';
import DesignOption from './components/DesignOption.jsx';
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @param  props
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */

export default function Edit( props ) {
	const {
		attributes,
		setAttributes,
	} = props;
	const { bgDesignType, bgWidth, ctnShape, className } = attributes;

	const myCustomClassName = className ? className : undefined;
	const myCustomWidthClass = bgWidth ? 'content-width-border editor-' + bgWidth : undefined;
	const myCustomDesignClass = bgDesignType ? 'editor-' + bgDesignType : undefined;
	const myCustomShape = ctnShape ? 'editor-' + ctnShape : undefined;
	const classes = [ myCustomWidthClass, myCustomDesignClass, myCustomClassName, myCustomShape ];
	const blockProps = useBlockProps( {
		className: classes.join( ' ' ),
	} );
	return (

		<div { ...blockProps }>

			<InspectorControls>
				<Panel>
					<PanelBody title={ __( 'Container Width' ) } initialOpen={ true }>

						<PanelRow>
							<RadioControl
								help={ __( 'Please choose container width.' ) }
								selected={ bgWidth }
								options={ [

									{ label: 'Width 1180px (Default)', value: 'ctn' },
									{ label: 'Width 980px', value: 'ctn-980' },
									{ label: 'Width 760px', value: 'ctn-760' },
								]
								}
								onChange={ ( value ) => setAttributes( {
									bgWidth: value,
								} ) }
							/>
						</PanelRow>
					</PanelBody>
					<PanelBody
						title={ __( 'Container Design' ) }
						className="dc-design-component"
						initialOpen={ true }
					>
						<DesignOption
							props={ props }
							value={ bgDesignType }
							DesignKey="bgDesignType"
							help="Click to select value"
							options={ [
								{ label: 'Container Red', value: 'ctn-red', display: '#ff0000' },
								{ label: 'Container Black', value: 'ctn-black', display: 'ctn-black.png' },
								{ label: 'Container Sea Green', value: 'ctn-sea-green', display: 'ctn-sea-green.png' },
							] }
						/>
					</PanelBody>
					<PanelBody
						title={ __( 'Container Overlay' ) }
						className="dc-media-component"
						initialOpen={ true }
					>
						<PanelRow>
							<Media
								props={ props }
								help="Please upload the overlay image."
							/>

						</PanelRow>
					</PanelBody>

					<PanelBody
						title={ __( 'Popup Options (Test)' ) }
						className="dc-popup-component"
						initialOpen={ true }
					>
						<PanelRow>
							<Popup
								props={ props }
								PopupKey="ctnShape"
								PopupValue={ ctnShape }
								ButtonText="Click for Options"
								help="Click to change or remove the value."
								options={ [
									{ label: 'Container Light Gray', value: 'ctn-lgray', display: 'ctn-lgray.png' },
									{ label: 'Container White', value: 'ctn-white', display: 'ctn-white.png' },
									{ label: 'Container Green', value: 'ctn-green', display: '#bfff00' },
									{ label: 'Container Purple', value: 'ctn-purple', display: 'ctn-purple.png' },
								] }
							/>

						</PanelRow>
					</PanelBody>

				</Panel>

			</InspectorControls>
			<section
				className={
					[ bgDesignType, bgWidth ].join( ' ' )
				}
			>
				<InnerBlocks />
			</section>
		</div>
	);
}
