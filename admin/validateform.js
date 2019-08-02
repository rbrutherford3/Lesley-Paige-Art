function validateform() {
	var etsy = validateURL(document.getElementById("etsy").value)
	var fineartamerica = validateURL(document.getElementById("fineartamerica").value);
	if (!etsy) {
		alert('Please review etsy.com URL');
		return false;
	}
	if (!fineartamerica) {
		alert('Please review fineartamerica.com URL');
		return false;
	}
	return true;
}

function validateURL(URL) {
	var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
		'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
		'((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
		'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
		'(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
		'(\\#[-a-z\\d_]*)?$','i'); // fragment locator
	if (URL === "") {
		return true;
	}
	else {
		return !!pattern.test(URL);
	}
}