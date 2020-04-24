// Rotate image x degrees and store value in hidden field
function rotate(angle) {
	var img = document.getElementById('image');
	var val = document.getElementById('angle');
	newangle = (parseInt(val.value) + angle) % 360;
	img.style.transform = 'rotate(' + newangle + 'deg)';
	val.value = newangle;
}
