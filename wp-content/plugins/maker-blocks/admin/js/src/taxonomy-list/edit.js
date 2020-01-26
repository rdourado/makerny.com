import { __ } from "@wordpress/i18n";
import { withSelect } from "@wordpress/data";
import { InspectorControls } from "@wordpress/block-editor";
import { SelectControl, PanelBody } from "@wordpress/components";

function taxonomyListBlock(props) {
	const { attributes, className, setAttributes } = props;

	const terms = props.terms || [];
	const taxonomies = props.taxonomies || [];
	const taxonomy = attributes.taxonomy;

	const options = taxonomies.map(tax => ({ label: tax.name, value: tax.slug }));
	const taxObject = taxonomies.find(tax => tax.slug === taxonomy) || {};

	return (
		<div className={`${className} block-library-list`}>
			{
				<InspectorControls>
					<PanelBody title={__("Block Settings", "maker")}>
						<SelectControl
							label="Taxonomy"
							value={taxonomy}
							options={options}
							onChange={taxonomy => setAttributes({ taxonomy })}
						/>
					</PanelBody>
				</InspectorControls>
			}
			<>
				<h3 className="is-style-heading-5">{taxObject.name}</h3>
				<ul>
					{terms.map(term => (
						<li key={term.id}>{term.name}</li>
					))}
				</ul>
			</>
		</div>
	);
}

export default withSelect((select, { attributes }) => {
	const taxonomy = attributes.taxonomy;
	return {
		taxonomies: select("core").getEntityRecords("root", "taxonomy"),
		terms: taxonomy && select("core").getEntityRecords("taxonomy", taxonomy)
	};
})(taxonomyListBlock);
