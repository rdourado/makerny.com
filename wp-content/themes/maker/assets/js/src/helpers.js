import $ from 'jquery'

export function renameTag(tag, element) {
	const oldElement = $(element)
	const newElement = $(`<${tag}/>`)
	const attributes = oldElement.get(0).attributes
	Array.prototype.slice
		.call(attributes)
		.forEach(attr => newElement.attr(attr.name, attr.value))
	oldElement
		.wrapInner(newElement)
		.children(0)
		.unwrap()
	return newElement
}
