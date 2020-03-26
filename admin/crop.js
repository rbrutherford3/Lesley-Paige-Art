function newline() {

	var img = document.getElementById("image");

	var w = img.width;
	var h = img.height;

	var tw = parseInt(document.getElementById("trueWidth").value);
	var th = parseInt(document.getElementById("trueHeight").value);

	var canvas = document.getElementById("canvas");
	var lInput = document.getElementById("left");
	var rInput = document.getElementById("right");
	var tInput = document.getElementById("top");
	var bInput = document.getElementById("bottom");

	var lVal = parseInt(lInput.value);
	var rVal = parseInt(rInput.value);
	var tVal = parseInt(tInput.value);
	var bVal = parseInt(bInput.value);

	l = Math.floor(lVal*w/tw);
	r = Math.floor(rVal*w/tw);
	t = Math.floor(tVal*h/th);
	b = Math.floor(bVal*h/th);

	var ctx = canvas.getContext("2d");
	ctx.clearRect(0, 0, canvas.width, canvas.height);

	ctx.beginPath();
	ctx.moveTo(l, 0);
	ctx.lineTo(l, canvas.height);
	ctx.strokeStyle = "#39ff14";
	ctx.lineWidth = 2;
	ctx.stroke();

	ctx.beginPath();
	ctx.moveTo(canvas.width-r, 0);
	ctx.lineTo(canvas.width-r, canvas.height);
	ctx.strokeStyle = "#39ff14";
	ctx.lineWidth = 2;
	ctx.stroke();

	ctx.beginPath();
	ctx.moveTo(0, t);
	ctx.lineTo(canvas.width, t);
	ctx.strokeStyle = "#39ff14";
	ctx.lineWidth = 2;
	ctx.stroke();

	ctx.beginPath();
	ctx.moveTo(0, canvas.height-b);
	ctx.lineTo(canvas.width, canvas.height-b);
	ctx.strokeStyle = "#39ff14";
	ctx.lineWidth = 2;
	ctx.stroke();
}

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

function changecrop(btn) {
	side = btn.id.substr(0,1);
	direction = btn.id.substr(1,3);
	stepsize = getstepsize();
	var valInput;
	var oppInput;
	var dimInput;
	switch(side) {
		case "t":
			valInput = document.getElementById("top");
			oppInput = document.getElementById("bottom");
			dimInput = document.getElementById("trueHeight");
			break;
		case "b":
			valInput = document.getElementById("bottom");
			oppInput = document.getElementById("top");
			dimInput = document.getElementById("trueHeight");
			break;
		case "l":
			valInput = document.getElementById("left");
			oppInput = document.getElementById("right");
			dimInput = document.getElementById("trueWidth");
			break;
			
		case "r":
			valInput = document.getElementById("right");
			oppInput = document.getElementById("left");
			dimInput = document.getElementById("trueWidth");
			break;
	}
	
	var val = parseInt(valInput.value);
	var opp = parseInt(oppInput.value);
	var dim = parseInt(dimInput.value);
	var newval;
	
	switch(direction) {
		case "inc":
			newval = val + stepsize;
			break;
		case "dec":
			newval = val - stepsize;
			break;
	}

	if (newval < 0) {
		newval = 0;
	}
	else if ((newval + opp) > dim) {
		alert("Can't increase crop margin further");
	}
	else {
		valInput.value = String(newval);
		newline();
	}
}