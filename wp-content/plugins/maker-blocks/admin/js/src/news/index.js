import { registerBlockType } from "@wordpress/blocks";
import attributes from "./attributes";
import edit from "./edit";

registerBlockType("maker/news", {
	attributes,
	title: "News",
	icon: "testimonial",
	category: "widgets",
	edit,
	save: () => null
});
