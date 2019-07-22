function newline(side) {
	
	var img = document.getElementById("image");

	var w = img.width;
	var h = img.height;

	var tw = document.getElementById("trueWidth").value;
	var th = document.getElementById("trueHeight").value;	
	
	var canvas = document.getElementById("canvas");
	
	canvas.style.position = "absolute";
	
	canvas.style.top = img.offsetTop + "px";
	canvas.style.left = img.offsetLeft + "px";
	
	var lInput = document.getElementById("left");
	var rInput = document.getElementById("right");
	var tInput = document.getElementById("top");
	var bInput = document.getElementById("bottom");
	
	var lVal = lInput.value;
	var rVal = rInput.value;
	var tVal = tInput.value;
	var bVal = bInput.value;
	
	stepsize = getstepsize()
	
	if ((side=="l") && (lVal > tw-rVal)) {
		alert('Left margin value too high, resetting');
		lInput.value = Math.floor((tw-rVal)/stepsize)*stepsize;
		lVal = lInput.value;
	}
	if ((side=="r") && (rVal > tw-lVal)) {
		alert('Right margin value too high, resetting');
		rInput.value = Math.floor((tw-lVal)/stepsize)*stepsize;
		rVal = rInput.value;
	}
	if ((side=="t") && (tVal > th-bVal)) {
		alert('Top margin value too high, resetting');
		tInput.value = Math.floor((th-bVal)/stepsize)*stepsize;
		tVal = tInput.value;
	}
	if ((side=="b") && (bVal > th-tVal)) {
		alert('Bottom margin value too high, resetting');
		bInput.value = Math.floor((th-tVal)/stepsize)*stepsize;
		bVal = bInput.value;
	}	
	
	l = lVal*w/tw;
	r = rVal*w/tw;
	t = tVal*h/th;
	b = bVal*h/th;
	
	var ctx = canvas.getContext("2d");
	ctx.clearRect(0, 0, canvas.width, canvas.height);

	ctx.beginPath();
	ctx.moveTo(l, 0);
	ctx.lineTo(l, canvas.height);
	ctx.stroke();
	
	ctx.beginPath();
	ctx.moveTo(canvas.width-r, 0);
	ctx.lineTo(canvas.width-r, canvas.height);
	ctx.stroke();
	
	ctx.beginPath();
	ctx.moveTo(0, t);
	ctx.lineTo(canvas.width, t);
	ctx.stroke();
	
	ctx.beginPath();
	ctx.moveTo(0, canvas.height-b);
	ctx.lineTo(canvas.width, canvas.height-b);
	ctx.stroke();
	
	lInput.max = tw-rVal;
	rInput.max = tw-lVal;
	tInput.max = th-bVal;
	bInput.max = th-tVal;
}

function newstepsize() {
	var stepsize = getstepsize();
	document.getElementById("left").step = stepsize;
	document.getElementById("right").step = stepsize;
	document.getElementById("top").step = stepsize;
	document.getElementById("bottom").step = stepsize;
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
	return stepsize;
}

document.addEventListener('keyup', (e) => {
  var key = e.charCode || e.keyCode || 0;     
  if (key == 13) {
    e.preventDefault();
  }
})