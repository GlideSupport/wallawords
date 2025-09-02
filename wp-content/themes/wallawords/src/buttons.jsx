
import { unregisterBlockStyle, registerBlockStyle } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { Panel, PanelBody, SelectControl } from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
registerBlockStyle( 'core/button', {
	label: 'Fill Boxed',
	name: 'fill-boxed',
} );
registerBlockStyle( 'core/button', {
	label: 'Outline Boxed',
	name: 'outline-boxed',
} );
function addCustomAttributes( settings, name ) {
	if ( 'core/button' === name ) {
		if ( settings.attributes ) {
			settings.attributes.size = {
				type: 'string',
				default: '',
			};
		}
	}
	return settings;
}

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'core/spacer',
	addCustomAttributes
);

const withCoreButton = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		const { attributes, setAttributes } = props;
		unregisterBlockStyle( 'core/button', 'outline' );
		unregisterBlockStyle( 'core/button', 'fill' );

		return (
			<>
				<BlockEdit { ...props } />
				{ /* <InspectorControls group="styles">
					<Panel>
						<PanelBody>

							<SelectControl
								label="Button Size"
								value={ attributes.size }
								options={ [
									{ label: 'Default', value: '' },
									{ label: 'Small', value: 'is-style-small' },
									{ label: 'Medium', value: 'is-style-medium' },
								] }
								onChange={ ( value ) => setAttributes( {
									size: value,
									className: attributes.className + ' ' + value,
								} ) }

							/>
						</PanelBody>
					</Panel>
				</InspectorControls> */ }
			</>
		);
	};
}, 'withCoreButton' );

wp.hooks.addFilter(
	'editor.BlockEdit',
	'core/spacer',
	withCoreButton
);
