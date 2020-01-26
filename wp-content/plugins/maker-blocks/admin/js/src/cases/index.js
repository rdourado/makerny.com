import { registerBlockType } from "@wordpress/blocks";
import attributes from "./attributes";
import edit from "./edit";

registerBlockType("maker/cases", {
	attributes,
	title: "Cases",
	icon: "index-card",
	category: "widgets",
	edit,
	save: () => null
});
