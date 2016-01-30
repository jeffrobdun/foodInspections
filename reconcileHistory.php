<?php
require('../config/config.php');
$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

$selectDistinct = 'SELECT DISTINCT inspectionDate, name, address, city FROM establishmentHistory;';

$selectResult = $db->query($selectDistinct);

$resultsArray = array();
if($selectResult->num_rows > 0){
	while($databaseResult = $selectResult->fetch_assoc()){
		$resultsArray[] = $databaseResult;
		$selectIds = 'SELECT id FROM establishmentHistory WHERE name = \'' . $db->real_escape_string($databaseResult['name']) . '\'
									AND address = \'' . $db->real_escape_string($databaseResult['address']) . '\' 
									AND city = \'' . $db->real_escape_string($databaseResult['city']) . '\'
									AND inspectionDate = \'' . $db->real_escape_string($databaseResult['inspectionDate']) . '\'';
		$selectIdResult = $db->query($selectIds);						
		for($i=1; $i < $selectIdResult->num_rows; $i++){
			$dbResult = $selectIdResult->fetch_assoc();
			if($i != 1){
				$deleteId .= ' OR id = ' . $dbResult['id'];
			}else{
				$deleteId = 'DELETE FROM establishmentHistory WHERE id = ' . $dbResult['id'];
			}
		}
		$deleteId .= ';';
		$deleteResult = $db->query($deleteId);
	}
}


$db->close();
// if(!$selectResult)
//   $log = 'Error inserting into th DB ' .  $db->error . "\n";