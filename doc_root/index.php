<?php
session_start();

// include('ListRequiredColumns.php');

unset($_SESSION["redis_session"]);

$_SESSION["prev_page"] = 0;
$_SESSION["next_page"] = 1;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<title>W3.CSS Template</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="static/css/main.css" rel="stylesheet" type="text/css" />
<link href="static/css/action-button.css" rel="stylesheet" type="text/css" />
<link href="static/css/menu-bar.css" rel="stylesheet" type="text/css" />

<script src="static/js/read-csv.js"></script>
<script src="static/js/canvasjs.min.js"></script>

<head>
	<div id="header">
		<img src='static/img/point.png' style='float: left; margin-left:20px; height:140px;'/>
		<img src='static/img/matrixBi.png' style='float: right; height:140px;'/>
		<div style='margin-left: 250px; padding-top: 40px;padding-bottom: 50px;'>PoalimBrain</div>
	</div>
</head>

<body onload="SelectTab(0);">

	<div id="main_vew_tabs" class="tab_pane">
		<button class="tab-button" type="button" 
				onclick="SelectTab(0);">
				Tags
		</button>
		<button class="tab-button" type="button" 
				onclick="SelectTab(1); UpdateFeatureList()">
				Features
		</button>
		<button class="tab-button" type="button" 
				onclick="SelectTab(2); ">
				Algorithms
		</button>
		<button class="tab-button tab-button-selected" type="button" 
				onclick="SelectTab(3);">
				Packages
		</button>
		<button class="tab-button" type="button" onclick="SelectTab(4);">Airflow</button>
		<button class="tab-button" type="button" onclick="SelectTab(5);">Atlas</button>
		<button class="tab-button" type="button" onclick="ReloadGraph('pckg_list');SelectTab(6);">Depends</button>
	</div>
	
	<div id='tab-vew'>
		<div id='tag-view' class='data-pane'>
			<h2 style="padding-top: 10px;" >Tag list</h2>
			<div class="vertical-pane left-pane" >
				<table class='object_list' id='tag_list' style="width:100%;">
				</table>
			</div>
			<div id="right-pane" class="vertical-pane">
				<ul class='menu-bar1'>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" 
									onclick="ShowEditDialog('tag-edit-popup', 'tag_list', 'object-tag_id', false);">Edit</button></li>
					<li><button class="action-button project-button" type="button">Delete</button></li>
					<li> &nbsp </li>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#tag-edit-popup" 
								onclick="ShowEditDialog('tag-edit-popup', 'tag_list', 'object-tag_id', true);">New</button></li>
				</ul>
			</div>
		</div>
		<div id='pckg-view' class='data-pane'>
			<h2 style="padding-top: 10px;" >Package list</h2>
			<div class="vertical-pane left-pane" >
				<table class='object_list' id='pckg_list' style="width:100%;">
				</table>
			</div>
			<div id="right-pane" class="vertical-pane">
				<ul class='menu-bar1'>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" onclick="ShowEditDialog('pkg-edit-popup', 'pckg_list', 'object-package_id', false);">Edit</button></li>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" >Show PDF</button></li>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup"  onclick="ReloadGraph('pckg_list');SelectTab(6);" >Dependencies</button></li>
					<li><button class="action-button project-button" type="button">Delete</button></li>
					<li> &nbsp </li>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#pkg-edit-popup" onclick="ShowEditDialog('pkg-edit-popup', 'pckg_list', 'object-package_id', true);">New</button></li>
				</ul>
			</div>
		</div>
		<div id='algo-view' class='data-pane' >
			<h2 style="padding-top: 10px;" >Algorithm list</h2>
			<div class="vertical-pane left-pane" >
				<table class='object_list' id='algo_list' style="width:100%;">
				</table>
			</div>
			<div id="right-pane" class="vertical-pane">
				<ul class='menu-bar1'>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" onclick="ShowEditDialog('algo-view-popup', 'algo_list', 'object-algo_id', false);">Edit</button></li>
					<li><button class="action-button project-button" type="button">Delete</button></li>
					<li> &nbsp </li>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#algo-view-popup" onclick="ShowEditDialog('algo-view-popup', 'algo_list', 'object-algo_id', true);">New</button></li>
				</ul>
			</div>
		</div>
		<div id='feature-view' class='data-pane' >
			<h2 style="padding-top: 10px;" >Feature list</h2>
			<div class="vertical-pane left-pane" style='display:inline-block;'>
				<label style="font-size: large;display:inline-block;">Dataframe:</label>
				<select class='object_list' id='df_list' style="font-size: large;display:inline-block;width:80%;float:right;" 
									onchange='UpdateFeatureList();'></select>
				<table class='object_list' id='feature_list' style="width:100%;">
				</table>
			</div>
			<div id="right-pane" class="vertical-pane">
				<ul class='menu-bar1'>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" 
									onclick="UpdateFeatureList();ShowEditDialog('feature-view-popup', 'feature_list', 'object-feature_id', false);">Edit</button></li>
					<li><button class="action-button project-button" type="button">Delete</button></li>
					<li> &nbsp </li>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#feature-view-popup" 
									onclick="UpdateFeatureList();ShowEditDialog('feature-view-popup', 'feature_list', 'object-feature_id', true);">New</button></li>
				</ul>
			</div>
		</div>
		<div id='event-view' style='height:500px;display:none;' >
			<iframe src="http://127.0.0.1:8983/banana" style="padding-top: 1px;width: 95%; height: 95%;border:0;"></iframe>
		</div>
		<div id='airflow-view' style='height:500px;display:none;' class='data-pane'>
			<iframe src="http://192.168.56.101:8080/admin" style="padding-top: 1px;width: 95%; height: 95%;border:0;"></iframe>
		</div>
		<div id='atlas-view' style='height:500px;display:none;' class='data-pane'>
			<iframe src="http://127.0.0.2:21001" style="padding-top: 1px;width: 95%; height: 95%;border:0;"></iframe>
		</div>
		<div id='depends-view' style='height:600px;display:none;' class='data-pane'>
			<iframe id="graph_area" src="data/graph1.svg" style="padding-top: 1px;width: 95%; height: 90%;border:0;"></iframe>
		</div>
	</div>
	<!-- -------------------------------------------------------------------------------------- -->
	<!-- --------- MODAL POPUPS --------------------------------------------------------------- -->
	<!-- -------------------------------------------------------------------------------------- -->
	<!--
	<div class="modal fade" id="alert-popup" role="dialog" onmouseout='ShowButtons(this, false, "Cancel_select-feature-popup", "Save_select-feature-popup");'>
		<span style='float:right;' class="closebtn" onclick="this.parentElement.style.display='none';">click to close &times;</span>
		<div style='display:inline-block;width:100%;' onclick="this.parentElement.style.display='none';">Select row that you want to edit.</div> 
	</div>
	-->	
	<div class="modal fade" id="alert-popup" role="dialog">
		<div class="modal-content">
			<div style="border:solid black 1px;">
				<span id="modal-title" style='float:left;font-size: 28px;'>Error</span>
				<span id="tag-view-popup_close" class="close" onclick="CloseEditDialog('alert-popup','','');">&times;</span>
			</div>
			<div style='display:inline-block;width:100%;' onclick="this.parentElement.style.display='none';">Select row that you want to edit.</div> 
		</div>
	</div>	

	<input type="hidden" name='tab-name' value="">
	
	<!-- ----------------- -->
	<!-- edit tag -------- -->
	<!-- ----------------- -->
	<div class="modal fade" id="tag-edit-popup" role="dialog" style="top:100px;">
	  <div class="modal-content">
		<div style="border:solid black 1px;">
			<span id="modal-title" style='float:left;font-size: 28px;'>Edit tag propertes</span>
			<span id="tag-view-popup_close" class="close" onclick="CloseEditDialog('tag-edit-popup','tag_list','tag');">&times;</span>
		</div>
		<div style="border:solid black 1px; height:140px;">
			<object id="object-tag_edit" type="text/html" style="width:100%;border: 0; height:100%;" data="edit-tag.html">
				<param name="object-tag_id" value="unknown"/>
			</object>
		</div>
	  </div>
	</div>
	<!-- ----------------- -->
	<!-- edit package ---- -->
	<!-- ----------------- -->
	<div class="modal fade" id="pkg-edit-popup" role="dialog" style="top:100px;">
	  <div class="modal-content">
		<div style="border:solid black 1px;">
			<span id="modal-title" style='float:left;font-size: 28px;'>Edit propertes</span>
			<span id="modal-title_close" class="close" onclick="CloseEditDialog('pkg-edit-popup','pckg_list', 'package');">&times;</span>
		</div>
		<div style="border:solid black 1px; height:700px;">
			<object id="object-package_edit" type="text/html" style="width:100%;border: 0; height:100%;" data="edit-package.html">
				<param name="object-package_id" value="unknown"/>
			</object>
		</div>
	  </div>
	</div>
	<!-- ----------------- -->
	<!-- edit algorithm -- -->
	<!-- ----------------- -->
	<!-- <div class="modal fade" id="edit-popup" role="dialog" onmouseout='ShowButtons(this, false, "Cancel_edit-popup", "Save_edit-popup");'> -->
	<div class="modal fade" id="algo-view-popup" role="dialog" style="top:100px;">
	  <div class="modal-content">
		<div style="border:solid black 1px;">
			<span id="modal-title" style='float:left;font-size: 28px;'>Edit propertes</span>
			<span id="algo-view-popup_close" class="close" onclick="CloseEditDialog('algo-view-popup','algo_list', 'algorithm');">&times;</span>
		</div>
		<div style="border:solid black 1px; height:300px;">
			<object id="object-algo_edit" type="text/html" style="width:100%;border: 0; height:100%;" data="edit-algorithm.html">
				<param name="object-algo_id" value="unknown"/>
			</object>
		</div>
	  </div>
	</div>

	<!-- ----------------- -->
	<!-- edit feature ------->
	<!-- ----------------- -->
	<div class="modal fade" id="feature-view-popup" role="dialog"  style="top:100px;">
	  <div class="modal-content">
		<div style="border:solid black 1px;">
			<span id="modal-title" style='float:left;font-size: 28px;'>Edit propertes</span>
			<span id="feature-view-popup_close" class="close" onclick='CloseEditDialog("feature-view-popup","feature_list", "feature");'>&times;</span>
		</div>
		<div style="border:solid black 1px; height:300px;">
			<object id="object-feature_edit" type="text/html" style="width:100%;border: 0; height:100%;" data="edit-feature.html">
				<param name="object-feature_id" value="-1"/>
				<param name="object-df_id" value="-1"/>
			</object>
		</div>
	  </div>
  </div>

</div>
<script>
var		name_idx = -1;
var		timestamp = { value: 0};
var		timestamp2 = { value: 0};
var		tab_names = [
	'tag',
	'feature',
	'algo',
	'pckg',
	'airflow',
	'atlas',
	'depends'
];

function FillTable(tbl_name, timestamp, type, extra_data)
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var		tbl = document.getElementById(tbl_name);
			if (this.responseText == '') {
				return;
			}
			var 	json_doc = JSON.parse(this.responseText);

			if (timestamp.value == json_doc.timestamp)
				return;
			timestamp.value = json_doc.timestamp;
			
			for (var j = tbl.rows.length-1; j >= 0; j--) {
				tbl.deleteRow(j);
			}
			
			//console.log(this.responseText);
			var headers = json_doc.headers;
			var n_columns = headers.length;
			var tr = document.createElement('tr');
			for (var i = 0; i < n_columns; i++) {
				var th = document.createElement('th');
				th.innerText = headers[i];
				th.onclick = function(evt) {
					sortTable(tbl_name,evt.path[0].cellIndex);
				};
				th.onmouseover = function(evt) {evt.path[0].classList.add("highlighted-header");};
				th.onmouseout = function(evt) {evt.path[0].classList.remove("highlighted-header");};
				if (headers[i] == 'id')
					th.style.display = 'none';
				if (headers[i] == 'Name')
					name_idx = i;
				tr.appendChild(th)
			}
			tbl.appendChild(tr);
			
			var rows = json_doc.rows;
			for (var j = 0; j < rows.length; j++) {
				var json_row = rows[j];
				var tr = document.createElement('tr');
				for(var i in json_row) {
					var td = document.createElement('td');
					td.innerText = json_row[i];
					if (i == 'id')
						td.style.display = 'none';
					tr.appendChild(td)
				}
				tr.onclick = function() { 
					highLightRow(this);
				};
				tbl.appendChild(tr)
			}
		}
	};
	var url='static/ajax/GetKnownObjects.php?type='+type+((extra_data=='')?'':('&'+extra_data));
	console.log(url);
	xhttp.open("GET", url, true);
	xhttp.send();		

}

function FillTable2(tbl_name, timestamp, type)
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var		tbl = document.getElementById(tbl_name);
			if (this.responseText == '') {
				return;
			}
			var 	json_doc = JSON.parse(this.responseText);

			var children = tbl.getElementsByTagName('option');
			for (var i = children.length-1; i> 0; i--) {
				tbl.removeChild(children[i]);
			}
			for (var i = 0; i < json_doc.rows.length; i++) {
//				console.log(json_doc.rows[i]);
				var opt = document.createElement('option');
				opt.value = json_doc.rows[i].id;
				opt.innerHTML = json_doc.rows[i].Name;
				tbl.appendChild(opt);
			}
			extra_data='df='+json_doc.rows[0].id;
			FillTable("feature_list", 0, "feature", extra_data);
		}
	};
	xhttp.open("GET", 'static/ajax/GetKnownObjects.php?type='+type, true);
	xhttp.send();		

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


function CloseEditDialog(dlg_id,tbl_name, type) {
	if (tbl_name != '') {
		if (type == 'feature')
			FillTable2('df_list', 0, 'dataframe','');
		else
			FillTable(tbl_name, 0, type,'');
	}
	var modal = document.getElementById(dlg_id);
	modal.style.display = "none";
}

function ShowEditDialog(popup_name, table_name, param_name, new_object) {
	var  tbl = document.getElementById(table_name);
	var  row;
	var  object_id;
	var  modal_title = document.getElementById('modal-title');
//	console.log('is_new='+new_object);
	if (!new_object) {
		for (var i = 1; i < tbl.childNodes.length; i++) {
			row = tbl.childNodes[i]
			if (row.classList.contains("highlighted-strong")) {
				break;
			}
		}
		if (i == tbl.childNodes.length) {
			document.getElementById('alert-popup').style.display = "block";
			return;
		}
		object_id = row.getElementsByTagName('td')[0].innerHTML;
		var td = row.getElementsByTagName("td")[name_idx];
		modal_title.innerText = 'Edit propertes of '+td.innerText;
	}
	else {
		object_id = -1;
		modal_title.innerText = 'Edit new package'
	}

	var modal = document.getElementById(popup_name);
	modal.style.display = "block";

	document.getElementsByName(param_name)[0].value = object_id;
}

/*
function ShowSelectFeatureDialog() {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var 	json_doc = JSON.parse(this.responseText);
			if (json_doc.length == 0) {
				//tbl.style.display = "none";
				return;
			}
			var root = json_doc.root;
			var tree_view = document.getElementById('package_list');
			// tree_view
			for (var i = 0; i < root.length; i++) {
				var name = root[i].name;
				var li = document.createElement('li');
				li.value = name;
				li.innerHTML = name;
				tree_view.appendChild(li);
				
			}
			
		}
	};
	xhttp.open("GET", 'static/ajax/GetPackageList.php', true);
	xhttp.send();		
	var modal = document.getElementById('select-feature-popup');
	modal.style.display = "block";
}
*/
function ShowButtons(obj, show, cancel_button_id, save_button_id) {
	if (show) {
		// document.getElementById(cancel_button_id).style.visibility = 'visible';
		document.getElementById(save_button_id).style.visibility = 'visible';
	}
	else {
		// document.getElementById(cancel_button_id).style.visibility = 'hidden';
		document.getElementById(save_button_id).style.visibility = 'hidden';
	}
}

function SelectTab(tab_id) {
	var type = tab_names[tab_id];
	var tab_name = type+'-view';
	console.log(tab_name, type);
	var buttons = document.getElementById('main_vew_tabs').getElementsByTagName('button');
	var button = buttons[tab_id];
	for (var i = 0; i < buttons.length; i++) {
		if (buttons[i].type == 'button')
			buttons[i].classList.remove('tab-button-selected');
	}
	button.classList.add('tab-button-selected');
	var tabs = document.getElementById('tab-vew').childNodes;
	for (var i = 0; i < tabs.length; i++) {
		if (tabs[i].nodeName.toUpperCase() == 'DIV') {
			tabs[i].style.display='none';
		}
	}
	document.getElementById(tab_name).style.display='block';
	document.getElementsByName('tab-name')[0].value=tab_name;
	if (type == 'feature')
		FillTable2('df_list', 0, 'dataframe','');
	else
		FillTable(type+'_list', 0, type,'');
}

function ReloadGraph(tbl_name)
{
	var		tbl = document.getElementById(tbl_name);
	var 	rows = tbl.rows;
	for (var i=1; i<rows.length; i++) {
		if(rows[i].classList.contains("highlighted-strong")) {
			highligted = rows[i].cells[0].innerText;
			break;
		}
	}
	
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("graph_area").contentWindow.location.reload();
		}
	};
	xhttp.open("GET", 'static/ajax/GetGraph.php?id='+highligted, true);
	xhttp.send();	
}

function RefteshPage()
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("graph_area").contentWindow.location.reload();
		}
	};
	xhttp.open("GET", 'static/ajax/GetMessage.php?update', true);
	xhttp.send();	
}

function DisplayPDF(algo_name) {
	console.log(name);
}

/////////////////////////////////////////////////////////////
function sortTable(tbl_name,col_id) {
	var		tbl = document.getElementById(tbl_name);
	var 	rows = tbl.rows;

	var sort_down = true;
	if(rows[0].cells[col_id].classList.contains("sort-down")) 
		sort_down = false;
	else
		sort_down = true;
	
	for (var i = 0; i < rows[0].cells.length; i++)  {
		rows[0].cells[i].classList.remove("sort-down")
		rows[0].cells[i].classList.remove("sort-up")
	}
	if (sort_down) {
		rows[0].cells[col_id].classList.remove("sort-up")
		rows[0].cells[col_id].classList.add("sort-down")
	}
	else {
		rows[0].cells[col_id].classList.remove("sort-down")
		rows[0].cells[col_id].classList.add("sort-up")
	}

	var 	tableArr = tableToArray(rows);
	tableArr.sort(function(a, b){return compareRows(a,b,col_id, sort_down);});
	var		highligted;
	var		result;
	for (var i=1; i<rows.length; i++) {
		if(rows[i].classList.contains("highlighted-strong")) {
			highligted = rows[i].cells[0].innerText;
			break;
		}
	}
	for (var i=1; i<rows.length; i++) {
		if(rows[i].classList.contains("result-available")) {
			result = rows[i].cells[0].innerText;
			break;
		}
	}
	rebuildTable(tbl, tableArr, highligted, result);
}

function tableToArray(rows) {
	var result = []

	for (var i=1; i<rows.length; i++) {
		t = [];
		var cells = rows[i].cells;
		for (var j=0; j<cells.length; j++)
			t.push(cells[j].innerText);
		result.push(t);
	}
  return result; 
}
	
function compareRows(a,b,i, sort_down) {
	if (sort_down)
		return a[i].localeCompare(b[i]);
	else
		return b[i].localeCompare(a[i]);
}

function rebuildTable(tbl, tableArr, highligted, result) {
	var rows = tbl.rows;
	for (var i = 1; i < rows.length; i++) {
		var cells = rows[i].cells;
		for (var j=0; j<cells.length; j++)
			cells[j].innerText = tableArr[i-1][j];
	}	
	for (var i = 1; i < rows.length; i++) {
		rows[i].classList.remove("highlighted-strong");
		rows[i].classList.remove("result-available");
	}
	for (var i = 1; i < rows.length; i++) {
		if (rows[i].cells[0].innerText == highligted)
			rows[i].classList.add("highlighted-strong");
		if (rows[i].cells[0].innerText == result)
			rows[i].classList.add("result-available");
	}
}

function UpdateFeatureList() {
	var table = document.getElementById('df_list');
	FillTable("feature_list", 0, "feature","df="+table.options[table.selectedIndex].value);
	document.getElementsByName('object-df_id')[0].value = table.options[table.selectedIndex].value;
}
</script>

</body>
</html>
