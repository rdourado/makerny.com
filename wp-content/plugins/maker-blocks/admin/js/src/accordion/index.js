import { registerBlockType } from "@wordpress/blocks";
import attributes from "./attributes";
import edit from "./edit";
import save from "./save";

registerBlockType("maker/accordion", {
	attributes,
	title: "Accordion",
	icon: "sort",
	category: "widgets",
	edit,
	save
});
