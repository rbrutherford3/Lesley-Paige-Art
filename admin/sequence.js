// Function for changing the order of a published piece, either one up or one down
function swaporder(elem,up){

	// grab divs
	var div = elem.parentNode.parentNode.parentNode;
	if (up) {
		var div2 = div.previousElementSibling;
	}
	else {
		var div2 = div.nextElementSibling;
	}

	if (!div2) {
		alert("No piece to swap with!");
	}
	else {
		// save div ids
		var divid = div.id;
		var div2id = div2.id;

		// scroll up or down by distance between divs
		//var rect = div.getBoundingClientRect();
		//var rect2 = div2.getBoundingClientRect();
		//var diff = rect.top - rect2.top;
		//window.scrollBy(0, -diff);

		// swap divs
		if (up) {
			div.parentNode.insertBefore(div,div2);
		}
		else {
			div.parentNode.insertBefore(div2,div);
		}

		// swap sequence values
		var sequenceinput = document.getElementById("sequence" + String(divid));
		var sequenceinput2 = document.getElementById("sequence" + String(div2id));
		var sequence = sequenceinput.value;
		var sequence2 = sequenceinput2.value;
		sequenceinput.value = sequence2;
		sequenceinput2.value = sequence;

		// flag the pieces as changed
		var changedinput = document.getElementById("changed" + String(divid));
		var changedinput2 = document.getElementById("changed" + String(div2id));
		changedinput.value = true;
		changedinput2.value = true;

		setbuttonsvisilibity(div);
		setbuttonsvisilibity(div2);

		changesmade();
	}
}

// Either publish or unpublish a piece
function swappublished(elem, publish){

	// grab elements
	var div = elem.parentNode.parentNode.parentNode;
	var divid = div.id;
	var sequenceinput = document.getElementById("sequence" + String(divid));
	var changedinput = document.getElementById("changed" + String(divid));
	var divpublished = document.getElementById("published");
	var divunpublished = document.getElementById("unpublished");

	// grab published/unpublished counts
	var numpublishedinput = document.getElementById("numpublished");
	var numunpublishedinput = document.getElementById("numunpublished");
	var numpublished = Number(numpublishedinput.value);
	var numunpublished = Number(numunpublishedinput.value);

	// update counts and item's sequence value
	if (publish) {
		divgroup = divpublished;
		numpublished++;
		numunpublished--;
		sequenceinput.value = numpublished;
	}
	// update counts and item's sequence value
	else {
		divgroup = divunpublished;
		numpublished--;
		numunpublished++;
		sequence = Number(sequenceinput.value);
		sequenceinput.value = "";

		// update sequences (+1) for all remaining published pieces, and mark as changed
		divnext = div.nextElementSibling;
		while(divnext) {
			idnext = divnext.id;
			document.getElementById("sequence" + String(idnext)).value = sequence;
			document.getElementById("changed" + String(idnext)).value = true;
			sequence++;
			divnext = divnext.nextElementSibling;
		}
	}

	// save counts and flag as changed
	numpublishedinput.value = numpublished;
	numunpublishedinput.value = numunpublished;
	changedinput.value = true;

	// move div, and grab the last published entries for updating button appearance
	divlastpublishedbefore = divpublished.lastElementChild;
	divgroup.appendChild(div);
	divlastpublishedafter = divpublished.lastElementChild;

	// update button appearances
	setbuttonsvisilibity(divlastpublishedbefore);
	setbuttonsvisilibity(divlastpublishedafter);
	setbuttonsvisilibity(div);

	changesmade();
}

// Update the appareance of the buttons in a piece's container, indiscriminate of the triggering action
function setbuttonsvisilibity(div) {

	// Grab buttons and more
	var numpublished = Number(document.getElementById("numpublished").value);
	var id = Number(div.id);
	var sequence = Number(document.getElementById("sequence" + String(id)).value);
	var upbutton = document.getElementById("up" + String(id));
	var downbutton = document.getElementById("down" + String(id));
	var publishbutton = document.getElementById("publish" + String(id));
	var unpublishbutton = document.getElementById("unpublish" + String(id));

	// Show "up" button unless first published piece or unpublished
	if ((sequence == 1) || (!sequence)) {
		upbutton.style.display = "none";
	}
	else {
		upbutton.style.display = "block";
	}

	// Show "down" unless last published piece or unpublished
	if ((sequence == numpublished) || (!sequence)) {
		downbutton.style.display = "none";
	}
	else {
		downbutton.style.display = "block";
	}

	// Show "published" button if upublished, "unpublish" button if published
	if (!sequence) {
		publishbutton.style.display = "block";
		unpublishbutton.style.display = "none";
	}
	else {
		publishbutton.style.display = "none";
		unpublishbutton.style.display = "block";
	}
}

function changesmade() {
	document.getElementById("anychanged").value = "true";
	document.getElementById("submit").style.display = "block";
	document.getElementById("recaptcha").style.display = "block";
}