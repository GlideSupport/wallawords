import { __ } from '@wordpress/i18n';
import { MediaUpload } from '@wordpress/block-editor';
import { Button, ToggleControl } from '@wordpress/components';
export default function Media( { props, help } ) {
	const {
		attributes,
		setAttributes,
	} = props;
	const { bgImage } = attributes;
	return (
		<MediaUpload
			onSelect={ ( media ) => {
				setAttributes( {
					bgImage: {
						title: media.title,
						filename: media.filename,
						url: media.url,
						isOverlay: false,
					},
				} );
			} }
			allowedTypes={ [ 'image' ] }
			multiple={ false }
			render={ ( { open } ) => (

				( bgImage.url === '' ) ? <>
					<Button onClick={ open } className="is-primary">
						Upload Image
					</Button>
					<p className="components-base-control__help">{ help }</p>
				</> : <div className="bgImage-ctn">
					<Button
						className="image-btn"
						onClick={ open }
					>
						<img src={ bgImage.url } alt="" />
					</Button>
					<div className="dc-media-buttons">
						<Button onClick={ open } className="is-secondary">
							Replace Image
						</Button>
						<Button onClick={ () => {
							setAttributes( {
								bgImage: {
									title: '',
									filename: '',
									url: '',
									isOverlay: false,
								},
							} );
						} } className="is-link is-destructive">
							Remove overlay image
						</Button>
					</div>

					<ToggleControl
						label="Overlay Class"
						help={ __( 'Check if you want to apply overlay class' ) }
						checked={ bgImage.isOverlay }
						onChange={ () => {
							const value = ! bgImage.isOverlay;
							setAttributes( {
								bgImage: {
									title: bgImage.title,
									filename: bgImage.filename,
									url: bgImage.url,
									isOverlay: value,
								},
							} );
						} }
					/>
				</div>

			) }
		/>
	);
}
