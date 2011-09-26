<?php
defined('_JEXEC') or die('Can only be loaded from within Joomla');

$doc = &JFactory::getDocument();
$doc->addStyleSheet( JURI::root().'components/com_refassign/views/refassign/tmpl/default.css' );
//$doc->addStyleSheet( JPATH_COMPONENT.DS.'views'.DS.'refassign'.DS.'tmpl'.DS.'default.css' );

?>
<? if($this->cansee) : ?>
<script type="text/javascript">
function createRequest() {
	var req = null;
	try {
		req = new XMLHttpRequest();
	} catch (ms) {
  	try {
	    req = new ActiveXObject("Msxml2.XMLHTTP");
  	} catch (nonms) {
    	try {
      	req = new ActiveXObject("Microsoft.XMLHTTP");
    	} catch (failed) {
      	req = null;
    	}
  	}  
	}
	
  if (req == null)
    alert("Error creating request object!");
  
	//req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
  return req;
}

//handles responseXML with handleRequest
function request(getParam, handleRequest) {
	var req = createRequest();
	req.onreadystatechange = function(){
		if(req.readyState == 4 && req.status == 200) {
			//TODO remove debug code
			if(false) {
			  alert(req.responseText);
			}
			handleRequest(req.responseXML);	
		}
	}

  req.open("GET","index.php?option=com_refassign&format=raw"+getParam,true);
  req.setRequestHeader("Content-Type", "text/xml");
  req.overrideMimeType("text/xml");
  req.send(null);	
}

function listRefsForGameRequest(gameid, responseFun) {
	return function(reqResponse) { listRefsForGame(gameid, responseFun, reqResponse); };
}

function listRefsForGame(gameid, responseFun, reqResponse) {
	var gl = reqResponse.getElementsByTagName('game').item(0);
	var res = gl.getElementsByTagName('status').item(0).childNodes[0].nodeValue;
			
	if(res == "OK") {
		//var id = gl.getAttribute('id');
		var refs = gl.getElementsByTagName('ref');
		var result = new Array();
		for(var i = 0; i < refs.length; ++i) {
			var ref = refs.item(i);
			var nameval = ref.getAttribute('name');
			var statusval = ref.getAttribute('assignmentstatus');
			var isOwnStatus = (ref.getAttribute('you') == "true");
					
			result.push({'name' : nameval, 'status' : statusval, 'own' : isOwnStatus});
		}
		responseFun(result);
	} else {
	  alert("OMG.... NOOOOOB.... DUTDUT");
	}
}

function updateRefs(gameid, refs) {
	var ret = "";
	var ownStatus = "-1";
	for(var i=0; i<refs.length; ++i) {
		var ref = refs[i];
		ret += "<li class=\"refstatus"+ref.status+"\">" + ref.name + "</li>\n";
		if (ref.own) {
			if (ownStatus != "-1") {
				alert("You are schizophrenic! Panic!");
			}
			ownStatus = ref.status;
		}
	}
	if (ret != "") {
		ret = "<ul>" + ret + "</ul>";
	}
	var el = document.getElementById("list_"+gameid);
	el.innerHTML = ret;	

	document.getElementById("btns_"+gameid).childNodes[0].className = "ownstatus"+ownStatus;

}

var ids = [<?php

function getGameId($game) {
	return '"'.substr($game['Spielnummer'], 1).'"';
}

$ids = array_map("getGameId", $this->gamedata);
echo implode(",", $ids);
?>
];

function c_updateRefs(gameid) {
	return function(refs) { updateRefs(gameid, refs); };
}

window.onload = function() {
	for(var i = 0; i < ids.length; i++) {
		request("&task=listgames&gameid="+ids[i], listRefsForGameRequest(ids[i], c_updateRefs(ids[i])));
	}
};

function statusCycleResult(reqResponse) {
	var status = reqResponse.getElementsByTagName('status').item(0).childNodes[0].nodeValue;
	var gameid = reqResponse.getElementsByTagName('game').item(0).getAttribute("id");
	request("&task=listgames&gameid="+gameid, listRefsForGameRequest(gameid, c_updateRefs(gameid)));
}

function changeOwnState(gameid) {
	request("&task=cycleownstatus&gameid="+gameid, statusCycleResult );
}


function hasClass(ele,cls) {
  return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}

function addClass(ele,cls) {
  if (!this.hasClass(ele,cls)) ele.className += " "+cls;
}

function removeClass(ele,cls) {
  if (hasClass(ele,cls)) {
    var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
    ele.className=ele.className.replace(reg,' ');
  }
  // TODO remove double whitespaces
}

// return the active table_style element
function table_style_get_active() {
  // get the table_style element container
  var list = document.getElementById("table_style").childNodes;
  for(var i=0; i<list.length; ++i) {
    // don't care for the non html elements, especially line breaks
    if (list[i].nodeType == 3) {
      continue;
    }
    // check if this element is active
    if (hasClass(list[i], "table_style_active")) {
      return list[i];
    }
  }
}

// set only given element to table_style_active
function table_style_set_active(element) {
  // get the table_style element container
  var list = document.getElementById("table_style").childNodes;
  for(var i=0; i<list.length; ++i) {
    // don't care for the non html elements, especially line breaks
    if (list[i].nodeType == 3) {
      continue;
    }
    // remove the active class
    removeClass(list[i], "table_style_active");
  }
  // add the active class to the given element
  addClass(element, "table_style_active");
}

function table_style(style) {
  var element = document.getElementById("table_style_"+style);
/* Note: This code block is not needed, "active" will be set correctly with current setting. 
  if (element == table_style_get_active()) {
    alert("Error: Already selected.");
    return false;
  } // "else" following*/
  table_style_set_active(element);
  return true;
}


</script>
<div id="com_refassign">
<ul id="table_style">
<li id="table_style_compact" onClick="table_style('compact')" class="table_style_active">kompakt</li>
<li id="table_style_complete" onClick="table_style('complete')">komplett</li>
</ul>
<table style="font-size:8px" id="gametable">
	<tr>
  	<th>Spielnr.</th>
  	<th>Datum/Zeit</th>
  	<th>Ort/Halle</th>
  	<th>Liga</th>
		<th>Team A</th>
		<th>Team B</th>
		<th>Ref A</th>
		<th>Ref B</th>
		<th>Warteliste</th>
		<th>Zuordnung</th>  	
  </tr>
  <? foreach($this->gamedata as $game) : ?>
		<tr>
			<td><?=$game['Spielnummer']?></td>
			<td><?=$game['Datum']?> - <?=$game['Zeit']?></td>
			<td><?=$game['Halle']?></td>
			<td><?=$game['Liga']?></td>
			<td><?=$game['Team A']?></td>
			<td><?=$game['Team B']?></td>
			<td><?=$game['SR 1']?></td>
			<td><?=$game['SR 2']?></td>
			<?php $id = substr($game['Spielnummer'], 1); ?>
			<td id="list_<?=$id?>">laedt..</td>
			<td id="btns_<?=$id?>"><div class="ownstatusloading" onClick="changeOwnState(<?=$id?>)"></div></td>
		</tr>  
  <? endforeach; ?>
</table>
</div>
<? else : ?>
<h2 class="error"><?=$this->msg?></h2>
<? endif; ?>
