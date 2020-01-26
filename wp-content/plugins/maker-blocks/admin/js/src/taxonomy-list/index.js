import { registerBlockType } from "@wordpress/blocks";
import attributes from "./attributes";
import edit from "./edit";

registerBlockType("maker/taxonomy-list", {
	attributes,
	title: "Taxonomy List",
	icon: "list-view",
	category: "widgets",
	edit,
	save: () => null
});
