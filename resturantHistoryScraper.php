<?php
// $url = "http://www1.gnb.ca/0601/fseinspectresults.asp";


// http://www1.gnb.ca/0601/fseinspectresults.asp?curralpha=0

//print phpinfo() . '<br/>';

require('../config/config.php');

$letters = range('A','Z');
$letters[] = '0';
$tables = array();
$resturantList = array();
$divs = array();

$ch = curl_init();

$count = 0;

// $letters = array('0');

// printf('<pre>%s</pre>', print_r($letters, true));

$log = 'Started on ' . date('d/m/y') . "\n";
$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// if(!$result = $db->query('DELETE FROM resturants;')){
// 			    $log .= 'There was an error running the delete [' . $db->error . ']' . "\n";
// 			}

try{
	foreach($letters as $letter){
		
		$log .= 'Working on letter ' . $letter . "\n";
	
		$url = 'http://www1.gnb.ca/0601/fseinspectresults.asp?curralpha=' . $letter;
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10000);
		
		$completeResults = array();
		$result = curl_exec($ch);
		
		$tables = explode('<table ', $result);
		
		$resturantList = explode('<tr', $tables[11]);
		
		$log .= 'Starting on establishment list for letter ' . $letter . "\n";
		// For each row in the list of th resturants
		foreach($resturantList as $row){
			
			$divs = explode('<div', $row);
			
			// Throw this out, no useful data
			unset($divs[0]);
			
			// Extract the resturant name and turn it into useable data
			$resturantName = explode('>', $divs[1]);
			$resturantName = $resturantName[2];
			$resturantName = str_replace('&NBSP;', '', strtoupper($resturantName));
			$resturantName = str_replace('</FONT', '', $resturantName);
			$resturantName = str_replace('"', '', $resturantName);
			$resturantName = str_replace('&AMP;', '&', $resturantName);
			$resturantName = html_entity_decode($resturantName);
			$resturantName = htmlspecialchars_decode($resturantName);
			$resturantName = utf8_encode($resturantName);
			
			$address = strtoupper($divs[2]);
			$address = explode('>', $address);
			$city = $address[3];
			$address = $address[2];
			$address = str_replace('<BR/', '', $address);
			$address = html_entity_decode($address);
			$address = htmlspecialchars_decode($address);
			$address = utf8_encode($address);
			
			$city = str_replace('&NBSP;', '', $city);
			$city = str_replace('</FONT', '', $city);
			$city = html_entity_decode($city);
			$city = utf8_encode($city);
			
			$inspectionDate = explode('>', $divs[3]);
			$colourImage = strtolower($inspectionDate[6]);
			$inspectionDate = strtoupper($inspectionDate[2]);
			$inspectionDate = explode('&NBSP;', $inspectionDate);
			$inspectionDate = $inspectionDate[0];
			
			$colourImage = explode('<img src=\'', $colourImage);
			$colourImage = explode('\'', $colourImage[1]);
			$colourImage = $colourImage[0];
			
			$reinspectionDate = explode('>', $divs[4]);
			$reinspectionDate = strtoupper($reinspectionDate[2]);
			$reinspectionDate = explode('&NBSP;', $reinspectionDate);
			$reinspectionDate = $reinspectionDate[0];
			
			$pdfPath = explode('href=', $divs[5]);
			$pdfPath = explode('"', $pdfPath[1]);
			$pdfPath = $pdfPath[1];
			$pdfPath = (($pdfPath == null) ? "" : $pdfPath);
			
			// $completeResults = array(
			// 					'resturantName' => $resturantName,
			// 					'address' => $address,
			// 					'city' => $city,
			// 					'inspectionDate' => $inspectionDate,
			// 					'colourImage' => $colourImage,
			// 					'reinspectionDate' => $reinspectionDate,
			// 					'pdfPath' => $pdfPath
			// 					);
								
			// $jsonResults = json_encode($completeResults);
			// print $db->real_escape_string($jsonResults) . '<br/>';
			
			// if($db->connect_errno > 0){
			//     die('Unable to connect to database [' . $db->connect_error . ']');
			// }
			if(!empty($resturantName)){
				$insertStatement = 'INSERT INTO establishmentHistory(name, address, city, inspectionDate, colourImage, reinspectionDate, pdfPath) 
									VALUES("' . 
									$db->real_escape_string($resturantName) . '", "' . 
									$db->real_escape_string($address) . '", "' . 
									$db->real_escape_string($city) . '", "' . $db->real_escape_string($inspectionDate) . '", "' . 
									$db->real_escape_string($colourImage) . '", "' . $db->real_escape_string($reinspectionDate) . '", "' .	
									$db->real_escape_string($pdfPath) . '");';
									
				$insertResult = $db->query($insertStatement);
						
				if(!$insertResult)
					$log .= 'Error inserting into th DB ' .  $db->error . "\n";
				
				$insertStatement = 'INSERT INTO establishmentHistoryCopy(name, address, city, inspectionDate, colourImage, reinspectionDate, pdfPath) 
									VALUES("' . 
									$db->real_escape_string($resturantName) . '", "' . 
									$db->real_escape_string($address) . '", "' . 
									$db->real_escape_string($city) . '", "' . $db->real_escape_string($inspectionDate) . '", "' . 
									$db->real_escape_string($colourImage) . '", "' . $db->real_escape_string($reinspectionDate) . '", "' .	
									$db->real_escape_string($pdfPath) . '");';
									
				$insertResult = $db->query($insertStatement);
						
				if(!$insertResult)
					$log .= 'Error inserting into th DB ' .  $db->error . "\n";
			}
			
			// $insertStatement = 'INSERT INTO resturantJsonTable(resturantObject) 
			// 					VALUES(\'' . $db->real_escape_string($jsonResults) . '\');';
								
			// $selectResturant = 'SELECT COUNT(id) as count FROM resturantJsonTable WHERE resturantObject LIKE \'%' . $db->real_escape_string($resturantName) . '%' . $db->real_escape_string($address) . '%\';';
			
			// $selectResturant = 'SELECT COUNT(id) as count FROM resturants WHERE name LIKE \'%' . $db->real_escape_string($resturantName) .'%\' AND address LIKE \'%' . $db->real_escape_string($address) . '%\' AND inspectionDate LIKE \'%' . $db->real_escape_string($inspectionDate) . '%\';';

			// if(!$result = $db->query($selectResturant)){
			//     $log .= 'There was an error running the query [' . $db->error . ']' . "\n";
			// }
			
			// $databaseResult = $result->fetch_assoc();
			// if($databaseResult['count'] == 0 && !empty($resturantName)){
				
				
			// }
			
			// printf('<pre>%s</pre>', print_r($databaseResult, true));
			
			// if($result->num_rows > 0){
			// 	while($databaseResult = $result->fetch_assoc()){
			// 		if($inspectionDate != $databaseResult['inspectionDate']){
			// 			// print 'Inserting into DB 1 <br/>';
			// 			// print $insertStatement . '<br/>';
			// 			if(!$db->query($insertStatement))
			// 				print 'error inserting into DB - ' . $db->error . '<br/>';
						
			// 			$count++;
			// 			exit;
			// 		}
			// 	}
			// }elseif($resturantName != "" && $address != ""){
			// 	// print 'Inserting into DB 2 <br/>';
			// 	// print $insertStatement . '<br/>';
			// 	if(!$db->query($insertStatement))
			// 		print 'error inserting into DB - ' . $db->error . '<br/>';
			
			// 	$count++;
			// }
			
		}
		// if($count % 10 == 0)
		// 	print 'Inserted ' . $count . ' rows. Still working... <br/>';
		// if($db->error != "")
		// 	print $db->error;
		sleep(6);
	
	}
}catch(Exception $e){
	print $e->getMessage();	
}

// for($i=0;$i<count($completeResults);$i++){
// 	json_encode($completeResults[0]);
// }
$db->close();

// file_put_contents('scraper.log', $log, FILE_APPEND);

curl_close($ch);

print 'Done';



// printf('<pre>%s</pre>', print_r($result->fetch_assoc(), true));

// printf('<pre>%s</pre>', print_r($result, true));



?>
