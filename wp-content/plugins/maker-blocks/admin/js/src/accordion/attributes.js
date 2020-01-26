export default {
	active: {
		type: "boolean",
		default: false
	},
	title: {
		type: "string",
		source: "html",
		selector: "dt"
	},
	description: {
		type: "string",
		source: "html",
		selector: "dd"
	}
};
