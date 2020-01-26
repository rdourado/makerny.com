import { __ } from "@wordpress/i18n";
import { withSelect } from "@wordpress/data";
import { InspectorControls, RichText } from "@wordpress/block-editor";
import { PanelBody, SelectControl, RangeControl } from "@wordpress/components";

const defaultTax = "client";

function taxonomyListBlock(props) {
	const { attributes, className, setAttributes } = props;
	const { title, order, orderBy, postsToShow } = attributes;
	const posts = props.posts || [];

	return (
		<div className={className}>
			{
				<InspectorControls>
					<PanelBody title={__("Sorting and Filtering", "maker")}>
						<SelectControl
							label={__("Order by", "maker")}
							value={`${orderBy}/${order}`}
							options={[
								{ value: "date/desc", label: "Newest to Oldest" },
								{ value: "date/asc", label: "Oldest to Newest" },
								{ value: "title/asc", label: "A → Z" },
								{ value: "title/desc", label: "Z → A" }
							]}
							onChange={value =>
								setAttributes({
									order: value.split("/")[1],
									orderBy: value.split("/")[0]
								})
							}
						/>
						<RangeControl
							label={__("Number of items", "maker")}
							value={postsToShow}
							onChange={postsToShow => setAttributes({ postsToShow })}
							min={1}
							max={10}
						/>
					</PanelBody>
				</InspectorControls>
			}
			<ul>
				{posts.map(post => (
					<li
						key={post.id}
						dangerouslySetInnerHTML={{
							__html: `${post.title.rendered} &nbsp;—&nbsp; <strong>${post.terms.client[0]}</strong>`
						}}
					/>
				))}
			</ul>
		</div>
	);
}

export default withSelect((select, { attributes }) => {
	const { postsToShow: per_page, orderBy: orderby, order } = attributes;
	return {
		posts: select("core").getEntityRecords("postType", "case", {
			per_page,
			orderby,
			order
		})
	};
})(taxonomyListBlock);
