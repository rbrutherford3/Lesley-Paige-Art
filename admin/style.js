// Javascript function library for style.php
// These javascript functions all update color and font previews as
// the settings change (called by onchange event).  Colors are
// converted from HSL color space to hex  RGB using a javascript function

// Updates all displays
function updateall() {
	updategradient();
	updatecolors();
	updateprimaryfont();
	updatesecondaryfont();
}

// Shows or hides secondary lightness inputs
function updategradient() {
	var range = document.getElementById("secondarylightness");
	var label = document.getElementById("secondarylightnesslabel");
	var preview = document.getElementById("secondarycolorpreview");
	if (document.getElementById("gradient").checked) {
		range.disabled = false;
		range.style.display = "inline-block";
		label.style.display = "inline-block";
		preview.style.display = "block";
	}
	else {
		range.disabled = true;
		range.style.display = "none";
		label.style.display = "none";
		preview.style.display = "none";
	}
}

// Update all color previews (done together for efficiency)
function updatecolors() {
	// Get colors in hex RGB form
	var primarycolor = primarycolorRGB();
	var secondarycolor = secondarycolorRGB();
	var gradient = document.getElementById("gradient").checked;
	var backgroundcolor = backgroundcolorRGB();
	// Update individual color previews with new color
	document.getElementById("primarycolorpreview").style.backgroundColor = "#" + primarycolor;
	document.getElementById("secondarycolorpreview").style.backgroundColor = "#" + secondarycolor;
	document.getElementById("backgroundcolorpreview").style.backgroundColor = "#" + backgroundcolor;
	// Update preview of all colors together
	var preview = document.getElementById("preview");
	if (gradient) {
		preview.style.backgroundColor = "";
		preview.style.background = "radial-gradient(#" + secondarycolor + ", #" + primarycolor + ")";
	}
	else {
		preview.style.background = "";
		preview.style.backgroundColor = "#" + primarycolor;
	}
	preview.style.borderColor = "#" + backgroundcolor;
}

// Update primary font preview
function updateprimaryfont() {
	// Get index of primary font from radio buttons
	var fontbuttons = document.getElementsByName("primaryfont");
	for (i = 0; i < fontbuttons.length; i++) {
		if (fontbuttons[i].checked)
			var fontid = fontbuttons[i].value;
	}
	// Get name and style of font using index
	var fontname = document.getElementById("fontname." + fontid).value;
	var fontstyle = document.getElementById("fontstyle." + fontid).value;
	// Update font in preview
	var fontpreview = document.getElementById("primaryfontpreview");
	fontpreview.style.fontFamily = fontstyle;
	// Set size (different for 'Lesley' font which appears small)
	if (fontname == "Lesley")
		fontpreview.style.fontSize = "2.5em";
	else
		fontpreview.style.fontSize = "2em";
}

//  Update secondary font preview
function updatesecondaryfont() {
	// Get index of secondary font from radio buttons
	var fontbuttons = document.getElementsByName("secondaryfont");
	for (i = 0; i < fontbuttons.length; i++) {
		if (fontbuttons[i].checked)
			var fontid = fontbuttons[i].value;
	}
	// Get name and style of font using index
	var fontname = document.getElementById("fontname." + fontid).value;
	var fontstyle = document.getElementById("fontstyle." + fontid).value;
	// Uodate font jin preview
	var fontpreview = document.getElementById("secondaryfontpreview");
	fontpreview.style.fontFamily = fontstyle;
	// Set size (different for 'Lesley' font which appears small)
	if (fonntname == "Lesley")
		fontpreview.style.fontSize = "1.5em";
	else
		fontpreview.style.fontSize = "1em";
}

// Converts primary color HSL to hex RGB
var primarycolorRGB = function() {
	var hue = document.getElementById("hue").value;
	var saturation = document.getElementById("saturation").value;
	var lightness = document.getElementById("primarylightness").value;
	return rgbToHex(hslToRgb(hue/360, saturation/100, lightness/100));
};

// Converts secondary color HSL to hex RGB
var secondarycolorRGB = function() {
	var hue = document.getElementById("hue").value;
	var saturation = document.getElementById("saturation").value;
	var lightness = document.getElementById("secondarylightness").value;
	return rgbToHex(hslToRgb(hue/360, saturation/100, lightness/100));
};

// Converts background color HSL to hex RGB
var backgroundcolorRGB = function() {
	var lightness = document.getElementById("backgroundlightness").value;
	return rgbToHex(hslToRgb(0, 0, lightness/100));
};

// Convert decimal rgb to hex
var rgbToHex = function (rgb) {
	hex = decToHex(rgb[0]);
	hex += decToHex(rgb[1]);
	hex += decToHex(rgb[2]);
	return hex;
};

// Taken from https://campushippo.com/lessons/how-to-convert-rgb-colors-to-hexadecimal-with-javascript-78219fdb
// Convert a decimal number to a hexadecimal number
var decToHex = function (dec) {
	var hex = Number(dec).toString(16);
	if (hex.length < 2)
		hex = "0" + hex;
	return hex;
};

// Taken from https://stackoverflow.com/a/36722579/3130769
/** * Converts an HSL color value to RGB. Conversion formula *
 adapted from http://en.wikipedia.org/wiki/HSL_color_space. *
 Assumes h, s, and l are contained in the set [0, 1] and *
 returns r, g, and b in the set [0, 255]. * * @param {number} h
 The hue * @param {number} s The saturation * @param {number} l
 The lightness * @return {Array} The RGB representation
 */
var hslToRgb = function (h, s, l) {
	var r, g, b;
	if (s == 0) {
		r = g = b = l; // achromatic
        }
	else {
		var hue2rgb = function hue2rgb (p, q, t) {
			if (t < 0)
				t += 1;
			if (t > 1)
				t -= 1;
			if (t < 1/6)
				return p + (q - p) * 6 * t;
			if (t < 1/2)
				return q;
			if(t < 2/3)
				return p + (q - p) * (2/3 - t) * 6;
			return p;
		}
		var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
		var p  = 2 * l - q;
		r = hue2rgb(p, q, h + 1/3);
		g = hue2rgb(p, q, h);
		b = hue2rgb(p, q, h - 1/3);
	}
	return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
};

window.onload = function() {
	updateall();
}
