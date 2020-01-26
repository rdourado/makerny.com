import { __ } from "@wordpress/i18n";
import { InspectorControls, RichText } from "@wordpress/block-editor";
import { PanelBody, ToggleControl } from "@wordpress/components";

function accordionBlock(props) {
	const { attributes, className, setAttributes } = props;
	const { active, title, description } = attributes;

	return (
		<div className={className}>
			{
				<InspectorControls>
					<PanelBody title={__("Block Settings", "maker")}>
						<ToggleControl
							label={__("Default State", "maker")}
							help={
								active
									? __("Showing description by default", "maker")
									: __("Hiding description by default", "maker")
							}
							checked={active}
							onChange={active => setAttributes({ active })}
						/>
					</PanelBody>
				</InspectorControls>
			}
			<dl>
				<RichText
					tagName="dt"
					value={title}
					placeholder="Name"
					onChange={title => setAttributes({ title })}
				/>
				<RichText
					tagName="dd"
					value={description}
					placeholder="Description"
					onChange={description => setAttributes({ description })}
				/>
			</dl>
		</div>
	);
}

export default accordionBlock;
