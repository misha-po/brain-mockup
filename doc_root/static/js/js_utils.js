function RewriteSelect(select, list, selected_value=-1) {
	var table1 = document.getElementById(select);
	var children = table1.getElementsByTagName('option');
	for (var i = children.length-1; i> 0; i--) {
		table1.removeChild(children[i]);
	}
	for (var i = 0; i < list.length; i++) {
		var opt = document.createElement('option');
		opt.value = list[i].id;
		opt.innerHTML = list[i].name;
		if(opt.value == selected_value)
			opt.selected = true;
		table1.appendChild(opt);
	}
}

var popup_name = window.parent.document.getElementsByName('tab-name')[0].value;
function editCancelSavePackage(popup_name) {
	console.log(popup_name+'-view-popup_close');
	window.parent.document.getElementById(popup_name+'-view-popup_close').onclick();
}


