function rotate() {
	var img = document.getElementById('image');
	var val = document.getElementById('angle');
	angle = (parseInt(val.value) + 90) % 360;
	img.style.transform = 'rotate(' + angle + 'deg)';
	val.value = angle;
}