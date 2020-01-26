import classnames from "classnames";
import { __ } from "@wordpress/i18n";
import { RichText } from "@wordpress/block-editor";

function accordionBlock(props) {
	const { attributes } = props;
	const { active, title, description } = attributes;
	const className = classnames({
		"mkr-accordion": true,
		"mkr-accordion--active": active
	});

	return (
		<dl className={className}>
			<RichText.Content
				tagName="dt"
				value={title}
				role="button"
				aria-expanded="false"
			/>
			<RichText.Content tagName="dd" value={description} aria-hidden="true" />
		</dl>
	);
}

export default accordionBlock;
