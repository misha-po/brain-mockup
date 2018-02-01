function RewriteSelect(select, list) {
	var table1 = document.getElementById(select)
	var children = table1.getElementsByTagName('option');
	for (var i = children.length-1; i> 0; i--) {
		table1.removeChild(children[i]);
	}
	for (var i = 0; i < list.length; i++) {
		var opt = document.createElement('option');
		opt.value = list[i].id;
		opt.innerHTML = list[i].Name;
		table1.appendChild(opt);
	}
}

