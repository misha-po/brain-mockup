<?php
session_start();

// include('ListRequiredColumns.php');

unset($_SESSION["redis_session"]);

$_SESSION["prev_page"] = 0;
$_SESSION["next_page"] = 1;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0064)file:///C:/Users/misha/Documents/Work3/FaceLab/www/default4.html -->
<!--
<meta http-equiv="refresh" content="1; RefteshPage();" />
-->

<html xmlns="http://www.w3.org/1999/xhtml">
<title>W3.CSS Template</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="static/css/main.css" rel="stylesheet" type="text/css" />
<link href="static/css/action-button.css" rel="stylesheet" type="text/css" />
<link href="static/css/menu-bar.css" rel="stylesheet" type="text/css" />
<!--
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
-->

<script src="static/js/read-csv.js"></script>
<script src="static/js/canvasjs.min.js"></script>

<head>
	<div id="header">
		<img src='static/img/point.png' style='float: left; margin-left:20px; height:140px;'/>
		<img src='static/img/matrixBi.png' style='float: right; height:140px;'/>
		<div style='margin-left: 250px; padding-top: 40px;padding-bottom: 50px;'>PoalimBrain</div>
	</div>
</head>

<!--
<body onload="FillTable('project_list');" >
-->
<body onload="FillTable('project_list');setInterval(function() {FillTable('project_list');}, 5000);">

	<div class="tab_pane">
		<button class="tab-button tab-button-selected" type="button" onclick="SelectTab(this, 'pckg-editor');">Packages</button>
		<button class="tab-button" type="button" onclick="SelectTab(this, 'event-viewer');">Algorithms</button>
		<button class="tab-button" type="button" onclick="SelectTab(this, 'event-viewer');">Event viewer</button>
		<button class="tab-button" type="button" onclick="SelectTab(this, 'airflow');">Airflow</button>
		<button class="tab-button" type="button" onclick="SelectTab(this, 'atlas');">Atlas</button>
		<button class="tab-button" type="button" onclick="ReloadGraph('project_list');SelectTab(this, 'depends');">Depends</button>
	</div>

	
	<div id='tab-vew'>
		<div id='pckg-editor' class='data-pane'>
			<h2 style="padding-top: 10px;" >Package list</h2>
			<div id="left-pane" class="vertical-pane" >
				<table id='project_list' style="width:100%;">
				</table>
			</div>
			<div id="right-pane" class="vertical-pane">
				<ul class='menu-bar1'>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" onclick="ShowEditDialog();">Edit</button></li>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" >Show PDF</button></li>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" >Dependencies</button></li>
					<li><button class="action-button project-button" type="button">Delete</button></li>
					<li>&nbsp</li>
					<li><button class="action-button project-button" type="button"  data-toggle="modal" data-target="#edit-popup" onclick="ShowSelectFeatureDialog();">New</button></li>
				</ul>
				<!--
				<table>
					<tr><td>
						<button class="action-button project-button" type="button"  >Delete</button>
					</td></tr>
					<tr><td>
						<button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" onclick="ShowEditDialog();">Edit</button>
					</td></tr>
					<tr><td>
						<button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" >Show PDF</button>
					</td></tr>
					<tr><td>
						<button class="action-button project-button" type="button"  data-toggle="modal" data-target="#select-feature-popup" >Dependencies</button>
					</td></tr>
					<tr><td>
					</td></tr>
					<tr><td>
						<button class="action-button project-button" type="button"  data-toggle="modal" data-target="#edit-popup" onclick="ShowSelectFeatureDialog();">New</button>
					</td></tr>
				</table>
				-->
			</div>
		</div>
		<div id='event-viewer' style='height:500px;display:none;' >
			<iframe src="http://127.0.0.1:8983/banana" style="padding-top: 1px;width: 95%; height: 95%;border:0;"></iframe>
		</div>
		<div id='airflow' style='height:500px;display:none;' class='data-pane'>
			<iframe src="http://192.168.56.101:8080/admin" style="padding-top: 1px;width: 95%; height: 95%;border:0;"></iframe>
		</div>
		<div id='atlas' style='height:500px;display:none;' class='data-pane'>
			<iframe src="http://127.0.0.2:21001" style="padding-top: 1px;width: 95%; height: 95%;border:0;"></iframe>
		</div>
		<div id='depends' style='height:600px;display:none;' class='data-pane'>
			<!--
			<button class="action-button" type="button" onclick="ReloadGraph();">Reload</button>
			-->
			<iframe id="graph_area" src="data/graph1.svg" style="padding-top: 1px;width: 95%; height: 90%;border:0;"></iframe>
			<!-- graphviz generated map 
			<img src="data/graph1.png" usemap="#G" alt="graphviz graph" />
			<map id="G" name="G">
				<area shape="poly" href="google.com" title="boot&#45;master" alt="" coords="297,29 292,22 279,15 258,10 233,7 204,5 175,7 150,10 129,15 116,22 111,29 116,37 129,43 150,49 175,52 204,53 233,52 258,49 279,43 292,37"/>
			
			</map>
			-->
		</div>
		
	</div>
	<!-- -------------------------------------------------------------------------------------- -->
	<!-- --------- MODAL POPUPS --------------------------------------------------------------- -->
	<!-- -------------------------------------------------------------------------------------- -->
	<div class="modal fade" id="alert-popup" role="dialog" onmouseout='ShowButtons(this, false, "Cancel_select-feature-popup", "Save_select-feature-popup");'>
		<span style='float:right;' class="closebtn" onclick="this.parentElement.style.display='none';">click to close &times;</span>
		<div style='display:inline-block;width:100%;' onclick="this.parentElement.style.display='none';">Select row that you want to edit.</div> 
	</div>	
	
	<!-- ----------------- -->
	<!-- edit package ---- -->
	<!-- ----------------- -->
	<!-- <div class="modal fade" id="edit-popup" role="dialog" onmouseout='ShowButtons(this, false, "Cancel_edit-popup", "Save_edit-popup");'> -->
	<div class="modal fade" id="edit-popup" role="dialog" style="top:100px;">
	  <div class="modal-content">
		<div style="border:solid black 1px;">
			<span id="modal-title" style='float:left;font-size: 28px;'>Edit propertes</span>
			<span class="close" onclick="CloseEditDialog('edit-popup');">&times;</span>
		</div>
		<!-- <div style="border:solid black 1px; height:500px;"   onmouseover='ShowButtons(this, true, "Cancel_edit-popup", "Save_edit-popup");'> -->
		<div style="border:solid black 1px; height:600px;">
			<object id="object-package_edit" type="text/html" style="width:100%;border: 0; height:100%;" data="edit-package.html">
				<param name="object-package_id" value="unknown"/>
			</object>
		</div>
	  </div>
	</div>

	<!-- ----------------- -->
	<!-- feature selection -->
	<!-- ----------------- -->
	<div class="modal fade" id="select-feature-popup" role="dialog" onmouseout='ShowButtons(this, false, "Cancel_select-feature-popup", "Save_select-feature-popup");'>
	  <div class="modal-content">
		<div style="border:solid black 1px;">
			<span id="modal-title" style='float:left;font-size: 28px;'>Select package</span>
			<span class="close" onclick='CloseEditDialog("select-feature-popup");'>&times;</span>
		</div>
		<div style="border:solid black 1px;" 
				onmouseover='ShowButtons(this, true, "Cancel_select-feature-popup", "Save_select-feature-popup");'>
			<ul id='package_list'>
			</ul>
			<!--
			<button id="Cancel_edit-popup" class="action-button" type="button" onclick="CloseEditDialog('edit-popup');" style='visibility:hidden;'>Cancel</button>
			-->
			<button id="Save_select-feature-popup" class="action-button" type="button" style='visibility:hidden;'>Save</button>
		</div>
	  </div>
  </div>

</div>
<script>
var		name_idx = -1;
var		timestamp = '';
function FillTable(tbl_name)
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var		tbl = document.getElementById(tbl_name);
			if (this.responseText == '') {
					//tbl.style.display = "none";
					return;
			}
			var res = this.responseText.split("|||");
			if (timestamp == res[0])
				return;
			timestamp = res[0];
			var 	json_doc = JSON.parse(res[1]);
			if (json_doc.length == 0) {
				//tbl.style.display = "none";
				return;
			}
			
			var rows = json_doc.rows;
			for (var j = tbl.rows.length-1; j >= 0; j--) {
				tbl.deleteRow(j);
			}
			
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
			tbl.appendChild(tr)
			var rows = json_doc.rows;
			for (var j = 0; j < rows.length; j++) {
				var json_row = rows[j];
				var tr = document.createElement('tr');
				for(var i in json_row) {
					var td = document.createElement('td');
					td.innerText = json_row[i];
					if (i == 'pid')
						td.style.display = 'none';
					tr.appendChild(td)
				}
				tr.onclick = function() { 
					highLightRow(this);
				};
				// var td = document.createElement('td');
				// if (json_row[n_columns-1] == 1) {
					// tr.classList.add("result-available");
					
									// // <a class="action-button" href="data/grid_figure.pdf" download>Download</a>

					// var div = document.createElement('div');
					// var button = document.createElement('a');
					// button.href="data/grid_figure.pdf"
					// button.download="";
					// button.innerText = "PDF";
					// div.appendChild(button);
					
					// div.style.display = "block";
					// div.style.color = "white";
					// button.style.width = "100%";
					// button.style.height = "100%";
					// td.classList.add("cell-button");
					// td.appendChild(div);
				// }
				// else {
					// tr.classList.remove("result-available");
				// }
				// tr.appendChild(td)
				tbl.appendChild(tr)
			}
		}
	};
	xhttp.open("GET", 'static/ajax/GetKnownPackages.php', true);
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


function CloseEditDialog(dlg_id) {
	var modal = document.getElementById(dlg_id);
	modal.style.display = "none";
}

function ShowEditDialog() {
	var  tbl = document.getElementById('project_list');
	var  row;
	// console.log(row.parentElement.nodeName);
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
	var pckg_id = row.getElementsByTagName('td')[0].innerHTML;

	var modal_title = document.getElementById('modal-title');
	var td = row.getElementsByTagName("td")[name_idx];
	modal_title.innerText = 'Edit propertes of '+td.innerText;
	var modal = document.getElementById('edit-popup');
	modal.style.display = "block";

	document.getElementsByName('object-package_id')[0].value = 'abcde-12345';
}

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

function SelectTab(button, tab_id) {
	var buttons = button.parentElement.childNodes;
	for (var i = 0; i < buttons.length; i++) {
		if (buttons[i].type == 'button')
			buttons[i].classList.remove('tab-button-selected');
	}
	button.classList.add('tab-button-selected');
	var tabs = document.getElementById('tab-vew').childNodes;
	// console.log(tabs.length);
	for (var i = 0; i < tabs.length; i++) {
		if (tabs[i].nodeName.toUpperCase() == 'DIV') {
			tabs[i].style.display='none';
		}
	}
	document.getElementById(tab_id).style.display='block';
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
</script>

</body>
</html>
