let x = document.querySelector('#filter_x_coord');
let y = document.querySelector('#filter_y_coord');
let size = document.querySelector('#filter_size');
let filter = document.querySelector('#select_filter');

window.addEventListener("keydown", function (e) {
	// up
	if (e.keyCode == "38") {
	    y.value--;
	    filter.style.top = y.value+"%";
	}
	//down
	if (e.keyCode == "40") {
		y.value++;
		filter.style.top = y.value+"%";
	}
	// left
	if (e.keyCode == "37") {
	    x.value--;
	    filter.style.left = x.value+"%";
	}
	// right
	if (e.keyCode == "39") {
	    x.value++;
	    filter.style.left = x.value+"%";
	}
	// zoom up
	if (e.keyCode == "107") {
	    size.value++;
	    filter.style.width = size.value+"%";
	}
	// zoom down
	if (e.keyCode == "109") {
	    size.value--;
	    filter.style.width = size.value+"%";
	}
}, false);