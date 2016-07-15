<?php
require_once('../config/config.php');
header('Access-Control-Allow-Origin:*');
$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if(isset($_GET['token']) && !empty($_GET['token'])){
	$tokenQuery = 'SELECT COUNT(id) as count FROM tokens WHERE token = \'' . $db->real_escape_string($_GET['token']) . '\';';
	if(!$result = $db->query($tokenQuery)){
		die('There was an error running the query [' . $db->error . ']<br/>');
	}
	
	$databaseResult = $result->fetch_assoc();
	if($databaseResult['count'] > 0){

		$resturantName = ((isset($_POST['name'])) ? $_POST['name'] : '');
		$address = ((isset($_POST['address'])) ? $_POST['address'] : '');
		$city = ((isset($_POST['city'])) ? $_POST['city'] : '');
		$inspectionDate = ((isset($_POST['inspectionDate'])) ? $_POST['inspectionDate'] : '');
		$letter = ((isset($_POST['letter'])) ? $_POST['letter'] : '');

		$statuses = array(
				'green' => 'images/public_green_sm.gif',
				'darkYellow' => 'images/public_yellow_high_sm.gif',
				'lightYellow' => 'images/public_yellow_low_sm.gif',
				'red' => 'images/public_red_high_sm.gif',
				'stripedRed' => 'images/public_red_low_sm.gif'
			);

		$selectedStatuses = array_values(array_intersect(array_keys($statuses), array_keys($_POST)));
		if(isset($_POST['letter'])){

			if($_POST['letter'] == "0-9"){
				$selectResturantByLeter = 'SELECT * FROM establishments WHERE name REGEXP \'^[0-9]\';';
			}else{
				$selectResturantByLeter = 'SELECT * FROM establishments WHERE name LIKE \'' . $db->real_escape_string($letter) .'%\';';
			}

			if(!$result = $db->query($selectResturantByLeter)){
					die('There was an error running the query [' . $db->error . ']<br/>');
			}

			$resultsArray = array();

			while($databaseResult = $result->fetch_assoc())
				$resultsArray[] = $databaseResult;

			$jsonString = json_encode($resultsArray);

			$db->close();
		}else{
			$selectResturant = 'SELECT * FROM establishments'; //'WHERE name LIKE \'%' . $db->real_escape_string($resturantName) .'%\' AND address LIKE \'%' . $db->real_escape_string($address) . '%\' AND inspectionDate LIKE \'%' . $db->real_escape_string($inspectionDate) . '%\' AND city LIKE \'%' . $db->real_escape_string($city) . '%\'';

			if($resturantName != ''){
				$selectResturant .= ' WHERE name LIKE \'%' . $db->real_escape_string($resturantName) .'%\'';
			}

			if($address != ''){
				if(strpos($selectResturant, 'WHERE'))
					$selectResturant .= ' AND address LIKE \'%' . $db->real_escape_string($address) . '%\'';
				else
					$selectResturant .= ' WHERE address LIKE \'%' . $db->real_escape_string($address) . '%\'';
			}

			if($city != ''){
				if(strpos($selectResturant, 'WHERE'))
					$selectResturant .= ' AND city LIKE \'%' . $db->real_escape_string($city) . '%\'';
				else
					$selectResturant .= ' WHERE city LIKE \'%' . $db->real_escape_string($city) . '%\'';
			}

			if($inspectionDate != ''){
				if(strpos($selectResturant, 'WHERE'))
					$selectResturant .= ' AND inspectionDate LIKE \'%' . $db->real_escape_string($inspectionDate) . '%\'';
				else
					$selectResturant .= ' WHERE inspectionDate LIKE \'%' . $db->real_escape_string($inspectionDate) . '%\'';
			}

			if(count($selectedStatuses) > 0){
				if(strpos($selectResturant, 'WHERE')){
					$selectResturant .= ' AND';
				}else{
					$selectResturant .= ' WHERE';
				}

				$selectResturant .= ' colourImage IN (\'' . $db->real_escape_string($statuses[$selectedStatuses[0]]) . '\'';
				for($i=1;$i<count($selectedStatuses);$i++){
					$selectResturant .= ', \'' . $db->real_escape_string($statuses[$selectedStatuses[$i]]) . '\'';
				}
				$selectResturant .= ')';
			}

			$selectResturant .= ' ORDER BY name, address;';

			if(!$result = $db->query($selectResturant)){
					die('There was an error running the query [' . $db->error . ']<br/>');
			}

			$resultsArray = array();
			while($databaseResult = $result->fetch_assoc()){
				$resultsArray[] = $databaseResult;
			}

			$jsonString = json_encode($resultsArray);

			$db->close();
		}
		print $jsonString;
	}
}

