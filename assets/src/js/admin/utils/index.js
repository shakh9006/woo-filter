const $ = require('jquery')

export function getRequest(url, data, callback, onError) {
	return $.ajax({
		url,
		data,
		type: "get",
		dataType: "json",
		success: callback,
		error: onError,
	})
}

export function postRequest(url, data, callback, onError) {
	return $.ajax({
		url,
		data,
		type: "post",
		dataType: "json",
		success: callback,
		error: onError,
	})
}

export function toggleValidator(val) {
	if (['', '0', 'false'].indexOf(val) !== -1)
		val = false
	return !!val
}

export function renderToast(title, type, {duration, position} = {duration: 2000, position: 'top-right'}) {
	if ( typeof vt !== "undefined" && typeof vt[type] === "function" )
		vt[type](title, { position, duration, closable: false })
}