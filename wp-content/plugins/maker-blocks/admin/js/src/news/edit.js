import { __ } from "@wordpress/i18n";
import { withSelect } from "@wordpress/data";
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, QueryControls } from "@wordpress/components";
import { dateI18n, __experimentalGetSettings } from "@wordpress/date";

function newsBlock(props) {
	const { attributes, className, setAttributes } = props;
	const { order, orderBy, numberOfItems } = attributes;
	const posts = props.posts || [];
	const dateFormat = __experimentalGetSettings().formats.date;

	console.log(posts);

	return (
		<div className={`${className} block-library-list`}>
			{
				<InspectorControls>
					<PanelBody title={__("Sorting and Filtering", "maker")}>
						<QueryControls
							{...{ orderBy, order, numberOfItems }}
							onOrderByChange={orderBy => setAttributes({ orderBy })}
							onOrderChange={order => setAttributes({ order })}
							onNumberOfItemsChange={numberOfItems =>
								setAttributes({ numberOfItems })
							}
						/>
					</PanelBody>
				</InspectorControls>
			}
			<ul>
				{posts.map(({ meta, ...post }) => {
					const title = meta.press_title || post.category_names[0];
					const date = meta.press_date || dateI18n(dateFormat, post.date_gmt);

					return (
						<li key={post.id}>
							<div>
								{title} – {date}
							</div>
							<div>{post.title.raw}</div>
						</li>
					);
				})}
			</ul>
		</div>
	);
}

export default withSelect((select, { attributes }) => {
	const { numberOfItems: per_page, orderBy: orderby, order } = attributes;
	const query = { per_page, orderby, order };
	return {
		posts: select("core").getEntityRecords("postType", "post", query)
	};
})(newsBlock);
