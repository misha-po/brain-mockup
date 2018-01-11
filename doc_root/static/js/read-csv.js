var lines = [];
var titles;
var next_page = 0;
var prev_page = new Array();
var this_page = 0;

function ShowPage(page_id) {
	var childNodes = document.getElementById("main-frame").getElementsByClassName("main_page");
	for (var i=0; i < childNodes.length; i++) {
		if (childNodes[i].id != 'page-'+page_id) {
			childNodes[i].style.display = 'none';
		}
		else {
			childNodes[i].style.display = 'block';
		}
	}
	var func_name = 'show_page_' + page_id;
	if (typeof(window[func_name]) == 'function') {
		// console.log('func_name='+func_name);
		window[func_name]();
	}
	this_page = page_id;
	console.log("this_page="+this_page+"  prev="+prev_page);
}

function BackToPage(page_id) {
	while(true) {
		if (prev_page.pop() == page_id)
			break;
	}	
	ShowPage(page_id);
}

function ShowPage1(page_id, prev=page_id-1, next=page_id+1) {
	if (page_id != -1) {
		var childNodes = document.getElementById("main-frame").getElementsByClassName("main_page");
		for (var i=0; i < childNodes.length; i++) {
			if (childNodes[i].id != 'page-'+page_id) {
				childNodes[i].style.display = 'none';
			}
			else {
				childNodes[i].style.display = 'block';
			}
		}
		var func_name = 'show_page_' + page_id;
		if (typeof(window[func_name]) == 'function') {
			window[func_name]();
		}
		this_page = page_id
	}
	if (next != -1) {
		next_page = next;
		document.getElementById("next_button").style.visibility = "visible";
	}
	else
		document.getElementById("next_button").style.visibility = "hidden";
	if (prev != -1) {
		document.getElementById("prev_button").style.visibility = "visible";
	}
	else
		document.getElementById("prev_button").style.visibility = "hidden";
}

function ExecuteButtonAction(button_type, page_id) {
	// console.log('ExecuteButtonAction: '+page_id);
	var func_name = button_type+'_action_' + page_id;
	if (typeof(window[func_name]) == 'function') {
		// console.log('func_name='+func_name);
		window[func_name]();
		// return false;
	}
	return true;
}

function ShowNextPage(page_id) {
	if (page_id > 0) {
		prev_page.push(this_page);
		ShowPage(page_id);
	}
	else {
		ExecuteButtonAction('next', this_page);
	}
}

function ShowPrevPage() {
	page_id = prev_page.pop();

	if (ExecuteButtonAction('prev', this_page))
		ShowPage(page_id);
}

function ProcessLines(allTextLines) {
	titles = allTextLines[0].split(',');
	nFields = titles.length;
	for (var i=0; i < allTextLines.length; i++) {
		var fields = allTextLines[i].split(',');
		if (fields.length != nFields)
			continue;
		lines.push(fields);
	}
}

function GetCandidateTitles(dataType) {
	var titles_subset = [];
	for (var i = 0; i < titles.length; i++) {
		if (ColumnHasDatatype(i, dataType) == true) {
			titles_subset.push(titles[i]);
		}
	}
	return titles_subset;
}

function ColumnHasDatatype(idx, dataType) {
	col_type = '';
	for (var i=1; i < lines.length; i++) {
		var fields = lines[i];
		var columns = []
		new_type = GuessType(fields[idx]);
		if (col_type == '')
			col_type = new_type
		else if (col_type != new_type)
			if (col_type == 'uint' && new_type == 'float')
				col_type = 'float'
			else if (col_type == 'float' && new_type == 'uint')
				col_type = 'float'
			
	}
	//console.log("Here we go: "+lines.length);
	return (dataType==col_type);
}

function GuessType(text) {
	if (text == '')
		return ''
	if (text.match(/[^0-9]/) == null)
		return "uint";
	
	if ((text.charAt(0) == "-") && (text.match(/[^0-9]/) == null))
		return "sint";
	
	parts = text.split(".")
	if ((parts.length == 2) && ((parts[0].match(/[^0-9]/) == null) && (parts[1].match(/[^0-9]/) == null))) 
		return "float";

	parts1 = text.split(" ")
	if (parts1.length == 1) {
		parts2 = parts1[0].split("-")
		if (parts2.length == 3 && (parts2[0].match(/[^0-9]/) == null && parts2[1].match(/[^A-Z]/) == null && parts2[2].match(/[^0-9]/) == null)) 
			return "date";
	}
	else if (parts1.length == 2) {
		parts2 = parts1[0].split("/")
		if (parts2.length == 3 && (parts2[0].match(/[^0-9]/) == null && parts2[1].match(/[^0-9]/) == null && parts2[2].match(/[^0-9]/) == null)) {
			parts3 = parts1[1].split(":")
			if (parts3.length == 2 && (parts3[0].match(/[^0-9]/) == null && parts3[1].match(/[^0-9]/) == null))
				return "datetime";
		}
	}
	return "string";
}

function GetColumnDataType(colIdx) {
	for (var i=1; i < lines.length; i++) {
		
	}
	return true;
}
		
function GetDataSample(title_text) {
	for (var i = 0; i < titles.length; i++) {
		if (titles[i] == title_text)
			break;
	}
	
	column_data = []
	for (var j = 1; j < 11; j++) {
		fields = lines[j];
		column_data.push(fields[i]);
	}
	return(column_data);
}

function SetProjectStatus(project_name, proj_status) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			
		}
	};
	xhttp.open("GET", 'static/ajax/project.php?update_project_data&status='+encodeURIComponent(proj_status, true)+'&project_name='+encodeURIComponent(project_name, true));
	xhttp.send();		
}
