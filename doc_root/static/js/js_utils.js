function RewriteSelect(select, list, selected_value=-1) {
	var table1 = document.getElementById(select);
	var children = table1.getElementsByTagName('option');
	for (var i = children.length-1; i> 0; i--) {
		table1.removeChild(children[i]);
	}
	for (var i = 0; i < list.length; i++) {
		var opt = document.createElement('option');
		opt.value = list[i].id;
		if (list[i].name != undefined)
			opt.innerHTML = list[i].name;
		else
			opt.innerHTML = list[i].Name;
		if(opt.value == selected_value)
			opt.selected = true;
		table1.appendChild(opt);
	}
	var opt = document.createElement('option');
	opt.innerHTML = '--none--';
	opt.value = -1;
	if (selected_value == -1) {
		opt.selected = true;		
	}
	table1.appendChild(opt);
}

function RewriteUL(ul_name, list, empty_value='--none--') {
	var ul = document.getElementById(ul_name);
	while( ul.firstChild )
		ul.removeChild(ul.firstChild );
	if (list.length == 0) {
		var li = document.createElement("li");
		li.appendChild(document.createTextNode(empty_value));
		ul.appendChild(li);
	}
	else {
		for (var i =0; i < list.length; i++) {
			var li = document.createElement("li");
			li.appendChild(document.createTextNode(list[i].name));
			li.setAttribute('onclick', 'ulSelectLine(this, "'+ul_name+'")');
			li.value = list[i].id;
			ul.appendChild(li);
		}
	}
}

var popup_name = window.parent.document.getElementsByName('tab-name')[0].value;
function editCancelSavePackage(popup_name) {
	//console.log(popup_name+'-view-popup_close');
	window.parent.document.getElementById(popup_name+'-view-popup_close').onclick();
}

function highLightRow(row) {
	var  tbl = row.parentElement;
	// console.log(row.parentElement.nodeName);
	if (row.classList.contains("highlighted-strong")) {
		for (var i = 1; i < tbl.childNodes.length; i++) {
			tbl.childNodes[i].classList.remove("highlighted-strong");
		}
	}
	else {
		for (var i = 1; i < tbl.childNodes.length; i++) {
			tbl.childNodes[i].classList.remove("highlighted-strong");
		}
		row.classList.add("highlighted-strong");
	}
}

function getSelectedRow(table_name) {
	var  rows = document.getElementById(table_name).getElementsByTagName('tr');
	for (var i = 1; i < rows.length; i++) {
		if (rows[i].classList.contains("highlighted-strong")) {
			return rows[i].cells[0].innerText;
		}
	}
}

function fillSelect(select_id, obj_type) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var 	json_doc = JSON.parse(this.responseText);
			if (json_doc.length == 0) {
				return;
			}
			RewriteSelect(select_id, json_doc.rows, selected_value=-1)
			//console.log(e.options[e.selectedIndex].text);
		}
	};
	xhttp.open("GET", 'static/ajax/GetKnownObjects.php?type='+obj_type, true);
	xhttp.send();		
}

function selectValue(select_id, val) {
	var table1 = document.getElementById(select_id);
	var children = table1.getElementsByTagName('option');
	for (i=0; i < children.length; i++) {
		if (table1.options[i].value == val) {
			table1.selectedIndex = i;
			return;
		}
	}
}

function ulSelectLine(element, list_name) {
	if (element.classList.contains('selected_line')) {
		element.classList.remove('selected_line');
		return;
	}

    var the_list = document.getElementById(list_name);
    var list_elems = the_list.getElementsByTagName('li');
    for (var i = 0; i < list_elems.length; i++) {
        list_elems[i].classList.remove('selected_line');
    }
    element.classList.add('selected_line');
}

function ulDeleteSelectedLine(list_name) {
    var the_list = document.getElementById(list_name);
    var list_elems = the_list.getElementsByTagName('li');
    for (var i = 1; i < list_elems.length; i++) {
        if (list_elems[i].classList.contains('selected_line')) {
			li=list_elems[i];
			li.parentNode.removeChild(li);
            return;
        }
    }
}

function ulAddLine(ul_name, name, value, empty_label='--none--') {
	var ul = document.getElementById(ul_name);
	if (ul.getElementsByTagName("li").length == 1) {
		var li = ul.getElementsByTagName("li")[0]
		if(li.innerHTML == empty_label) {
			li.parentNode.removeChild(li);
		}
	}
	var li = document.createElement("li");
	li.value = value;
	li.appendChild(document.createTextNode(name));
	li.setAttribute('onclick', 'ulSelectLine(this, "'+ul_name+'")');
	ul.appendChild(li);
}

function ulGetList(list_name) {
    var the_list = document.getElementById(list_name);
    var list_elems = the_list.getElementsByTagName('li');
	var the_list = new Array();
    for (var i = 0; i < list_elems.length; i++) {
        the_list.push(list_elems[i].value);
    }
	return the_list;
}

function RewriteRadioButtons(container_name, name, list, selected_value=-1, onchange_func) {
	var container = document.getElementById(container_name);
	var children = container.getElementsByTagName(name);
	
	for (var i = children.length-1; i> 0; i--) {
		children[i].parent.removeChild(children[i]);
	}
	
	for (var i = 0; i < list.length; i++) {
		try {
			var radioHtml = '<input type="radio" name="' + name + '"';
			if ( list[i].id ==  selected_value) {
				radioHtml += ' checked';
			}
			radioHtml += ' value="'+list[i].id+'"';
			radioHtml += ' onchange="'+onchange_func+'"';
			radioHtml += '>';
			radioHtml += list[i].display_name;
			radioHtml += '</input>';
			container.insertAdjacentHTML('beforeend', radioHtml);
			container.insertAdjacentHTML('beforeend', '<br>');
		} catch( err ) {
			console.log(err);
			radioInput = document.createElement('input');
			radioInput.setAttribute('type', 'radio');
			radioInput.setAttribute('name', name);
			radioInput.value=list[i].id;
			if ( list[i].id ==  selected_value) {
				radioInput.setAttribute('checked', 'true');
			}
			radioInput.innerHTML = list[i].display_name;
			container.appendChild(radioInput);
			container.insertAdjacentHTML('beforeend', '<br>');
		}
	}
}
