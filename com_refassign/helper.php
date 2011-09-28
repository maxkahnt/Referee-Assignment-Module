<?php
defined('_JEXEC') or die('This module can only be loaded within Joomla.');

function post_request($url, $data = array()) {
	$query = http_build_query($data);
	
	$url_data = parse_url($url);
	
	if(isset($url_data['scheme']) && $url_data['scheme'] != 'http') {
		return array('status' => 'err', 'id' => 1, 'msg' => 'scheme not supported');
	}
	
	$host = $url_data['host'];
	$path = $url_data['path'];
	$port = 80;
	if(isset($url_data['port']))
		$port = $url_data['port'];
	
	$fp = fsockopen($host, $port, $errno, $errstr, 15); //TODO: check timeout value
	if($fp) {
		fputs($fp, "POST $path HTTP/1.1\r\n");
		fputs($fp, "Host: $host\r\n");
		fputs($fp, "Connection: Close\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: ".strlen($query)."\r\n\r\n");
		fputs($fp, $query);
		
		$result = '';
		while(!feof($fp))
			$result .= fgets($fp, 128);		
	} else {
		return array('status' => 'err', 'id' => 2, 'msg' => "$errstr($errno)");
	}
	
	fclose($fp);
	
	//TODO: more elegant way?
	$result = explode("\r\n\r\n", $result, 2);
	
	$header = (isset($result[0]))?$result[0]:'';
	$content = (isset($result[1]))?utf8_encode($result[1]):'';
	
	return array('status' => 'ok', 'header' => $header, 'content' => $content); 
}

function parse_csv_gamelist($str) {
	$rows = explode("\n", $str);
	$header = explode(";", $rows[6]);
	
	$rows = array_slice($rows, 8, count($rows) - 11);
	$result = array();
	foreach($rows as $row) {
		$fields = explode(";", $row);
		$game = array();
		for($i=0; $i<count($header)-1; $i++) {
			$hf = $header[$i];
			$ff = $fields[$i];
			$game[$hf] = $ff;
		}
		$result[] = $game;  
	}
	
	return $result;
}

function get_content() {
	$data = array('tx_bbvdb_pi1' => array('CODE' => 'spielplan',
																				'action' => 'spielplansuche_gocsv',
																				'teama' => 1,
																				'teamb' => 1,
																				'sr' => 'BBS',
																				'sra' => 1,
																				'srb' => 1,
																				'spielfrei' => 1,
																				'saison' => 2011,
																				'layout' => 1,
																				'newfashion' => 1,
																				'savecookie' => 0,
																				'abds' => 1,
																				'anzahlds' => 178,
																				'gesamtds' => 178),
					  		'spielplansuche_gocsv' => 'CSV');

	$result = post_request("http://www.binb.info/cms/spielbetriebv3.0.html", $data);
	
	if($result['status'] == 'ok') {
		return parse_csv_gamelist($result['content']);
	} else {
		return array();
	}
}
?>