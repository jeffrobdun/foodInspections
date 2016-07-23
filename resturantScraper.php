<?php
// $url = "http://www1.gnb.ca/0601/fseinspectresults.asp";


// http://www1.gnb.ca/0601/fseinspectresults.asp?curralpha=0

//print phpinfo() . '<br/>';
require_once('../config/config.php');
require_once('../extensions/simple_html_dom.php');
require_once('../extensions/base128.php');

ini_set('memory_limit', '-1');

$letters = range('A','Z');
$letters[] = '0';
$tables = array();
$resturantList = array();
$divs = array();
$baseUrl = 'http://www1.gnb.ca/0601/';

$count = 0;

print 'Started on ' . date('d/m/y') . "\n";
$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if(!$result = $db->query('DELETE FROM establishments;')){
			    print 'There was an error running the delete [' . $db->error . ']' . "\n";
}


	foreach($letters as $letter){
		
		$url = 'http://www1.gnb.ca/0601/fseinspectresults.asp?curralpha=' . $letter;
		
		$html = file_get_html($url);
		$table = $html->find('table', 1);
		$innerTable = $table->find('table[width]', 0);
		if(isset($innerTable)){
			$innerTable = $innerTable->find('table[width]', 4);
			
			foreach($innerTable->find('tr') as $row){
				if(count($row->find('td')) >= 6){
					$resturantName = str_replace('&nbsp;', '', $row->find('td',0)->find("font", 0)->innertext);
					$address = str_replace('&nbsp;', '', $row->find('td',1)->find("font", 0)->innertext);
					$addressArray = explode('<br/>', $address);
					$address = $addressArray[0];
					$city = $addressArray[1];
					$inspectionDate = str_replace('&nbsp;', '', $row->find('td',2)->find("font", 0)->innertext);
					$colourImage = str_replace('&nbsp;', '', $row->find('td',3)->find('img', 0)->src);
					$reinspectionDate = str_replace('&nbsp;', '', $row->find('td',4)->find("font", 0)->innertext);
					$pdfPath = str_replace('&nbsp;', '', $row->find('td',5)->find("a", 0)->href); 
					
					$insertResult = '';

					if(!empty($resturantName)){
						
						
						$insertStatement = 'INSERT INTO establishments(name, address, city, inspectionDate, colourImage, reinspectionDate, pdfPath) 
											VALUES("' . 
											$db->real_escape_string(html_entity_decode($resturantName)) . '", "' . 
											$db->real_escape_string($address) . '", "' . 
											$db->real_escape_string($city) . '", "' . $db->real_escape_string($inspectionDate) . '", "' . 
											$db->real_escape_string($colourImage) . '", "' . $db->real_escape_string($reinspectionDate) . '", "' .	
											$db->real_escape_string($pdfPath) . '");';

						$insertResult = $db->query($insertStatement);

						if(!$insertResult){
							// print 'Insert statement: ' . $insertStatement . "<br/>\n";
							print 'Error inserting into the DB ' .  $db->error . "<br/>\n";
						}

						$historyInsertStatement = 'INSERT INTO establishmentHistory(name, address, city, inspectionDate, colourImage, reinspectionDate, pdfPath) 
										VALUES("' . 
										$db->real_escape_string($resturantName) . '", "' . 
										$db->real_escape_string($address) . '", "' . 
										$db->real_escape_string($city) . '", "' . $db->real_escape_string($inspectionDate) . '", "' . 
										$db->real_escape_string($colourImage) . '", "' . $db->real_escape_string($reinspectionDate) . '", "' .	
										$db->real_escape_string($pdfPath) . '");';
										
						$historyInsertResult = $db->query($historyInsertStatement);
							
						if(!$historyInsertResult){
							print 'Error inserting into the DB ' .  $db->error . "<br/>\n";
						}
				}
			} 
		}
		
		print '<br/>Working on letter ' . $letter . "<br/> \n";

		$resturantList = array();
		
		print '<br/>Finishing establishment list for letter ' . $letter . "\n";

	}
	}


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

print 'Done';


?>
