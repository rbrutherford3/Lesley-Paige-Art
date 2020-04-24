// Draws lines on the canvas object to show crop margins (after margins have been set)
function newline() {

	// Gather eements and values
	var img = document.getElementById("image");

	var w = img.width;
	var h = img.height;

	var tw = parseInt(document.getElementById("trueWidthRotated").value);
	var th = parseInt(document.getElementById("trueHeightRotated").value);

	var canvas = document.getElementById("canvas");
	var lInput = document.getElementById("left");
	var rInput = document.getElementById("right");
	var tInput = document.getElementById("top");
	var bInput = document.getElementById("bottom");

	var lVal = parseInt(lInput.value);
	var rVal = parseInt(rInput.value);
	var tVal = parseInt(tInput.value);
	var bVal = parseInt(bInput.value);

	// Calculate crop margin display positons through a ratio of
	// the formatted dimensions to the original dimensions
	l = Math.floor(lVal*w/tw);
	r = Math.floor(rVal*w/tw);
	t = Math.floor(tVal*h/th);
	b = Math.floor(bVal*h/th);

	// Clear previously drawn lines
	var ctx = canvas.getContext("2d");
	ctx.clearRect(0, 0, canvas.width, canvas.height);

	// Draw left margin
	ctx.beginPath();
	ctx.moveTo(l, 0);
	ctx.lineTo(l, canvas.height);
	ctx.strokeStyle = "#39ff14";
	ctx.lineWidth = 2;
	ctx.stroke();

	// Draw right margin
	ctx.beginPath();
	ctx.moveTo(canvas.width-r, 0);
	ctx.lineTo(canvas.width-r, canvas.height);
	ctx.strokeStyle = "#39ff14";
	ctx.lineWidth = 2;
	ctx.stroke();
	
	// Draw top margin
	ctx.beginPath();
	ctx.moveTo(0, t);
	ctx.lineTo(canvas.width, t);
	ctx.strokeStyle = "#39ff14";
	ctx.lineWidth = 2;
	ctx.stroke();

	// Draw bottom margin
	ctx.beginPath();
	ctx.moveTo(0, canvas.height-b);
	ctx.lineTo(canvas.width, canvas.height-b);
	ctx.strokeStyle = "#39ff14";
	ctx.lineWidth = 2;
	ctx.stroke();
}

// Determine step size selected simply by looping through each one and checking
function getstepsize() {
	var stepsizebuttons = document.getElementsByName("stepsize");
	for (var i = 0, length = stepsizebuttons.length; i < length; i++) {
		if (stepsizebuttons[i].checked) {
			// do whatever you want with the checked radio
			var stepsize = stepsizebuttons[i].value;
			// only one radio can be logically checked, don't check the rest
			break;
		}
	}
	return parseInt(stepsize);
}

// Function to change the hidden crop margin fields (original image, not formatted)
function changecrop(btn) {
	
	// Get elements
	
	side = btn.id.substr(0,1);		// First letter of button ID determines side
	direction = btn.id.substr(1,3);	// Last three letters determine direction
	stepsize = getstepsize();	// Get step size from user input
	var valInput;	// Crop margin vaue
	var oppInput;	// Crop margin value of opposite side (for limits)
	var dimInput;	// Length of dimension for margin
	switch(side) {	// Get appropriate margins and dimension based on button pressed
		case "t":
			valInput = document.getElementById("top");
			oppInput = document.getElementById("bottom");
			dimInput = document.getElementById("trueHeightRotated");
			break;
		case "b":
			valInput = document.getElementById("bottom");
			oppInput = document.getElementById("top");
			dimInput = document.getElementById("trueHeightRotated");
			break;
		case "l":
			valInput = document.getElementById("left");
			oppInput = document.getElementById("right");
			dimInput = document.getElementById("trueWidthRotated");
			break;
		case "r":
			valInput = document.getElementById("right");
			oppInput = document.getElementById("left");
			dimInput = document.getElementById("trueWidthRotated");
			break;
	}
	
	// Get values of margins and dimension
	var val = parseInt(valInput.value);
	var opp = parseInt(oppInput.value);
	var dim = parseInt(dimInput.value);
	var newval;
	
	// Increase or decrease margin as specified by button press
	switch(direction) {
		case "inc":
			newval = val + stepsize;
			break;
		case "dec":
			newval = val - stepsize;
			break;
	}

	// No negative margins
	if (newval < 0) {
		newval = 0;
	}
	// No overlapping margins
	else if ((newval + opp) > dim) {
		alert("Can't increase crop margin further");
	}
	// Store value and draw new lines on canvas
	else {
		valInput.value = String(newval);
		newline();
	}
}

// Draw lines as soon as page loads (for existing pieces with previously set margins)
window.onload = function() {
	newline();
}
