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

// $ch = curl_init();

$count = 0;

// $letters = array('0');

// printf('<pre>%s</pre>', print_r($letters, true));

print 'Started on ' . date('d/m/y') . "\n";
$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if(!$result = $db->query('DELETE FROM establishments;')){
			    print 'There was an error running the delete [' . $db->error . ']' . "\n";
}

if(!$result = $db->query('DELETE FROM pdfs;')){
			    print 'There was an error running the delete [' . $db->error . ']' . "\n";
}

// try{
	foreach($letters as $letter){
		
		$url = 'http://www1.gnb.ca/0601/fseinspectresults.asp?curralpha=' . $letter;
		
		$html = file_get_html($url);
		$table = $html->find('table', 1);
		$innerTable = $table->find('table[width]', 0);
		$innerTable = $innerTable->find('table[width]', 4);
			// 		$table1 = $table->find('table[width]', 0);
			// print file_get_contents($url) . '<br/>';
		
		foreach($innerTable->find('tr') as $row){
// 			print $row->outertext . '<br/>';
// 			$tableCells = 
			$resturantName = str_replace('&nbsp;', '', $row->find('td',0)->find("font", 0)->innertext);
// 			print '<br/>resturantName: ' . $resturantName;
			$address = str_replace('&nbsp;', '', $row->find('td',1)->find("font", 0)->innertext);
			$addressArray = explode('<br/>', $address);
// 			printf('<pre>%s</pre>',print_r($address, true));
			$address = $addressArray[0];
			$city = $addressArray[1];
			$inspectionDate = str_replace('&nbsp;', '', $row->find('td',2)->find("font", 0)->innertext);
			$colourImage = str_replace('&nbsp;', '', $row->find('td',3)->find('img', 0)->src);
			$reinspectionDate = str_replace('&nbsp;', '', $row->find('td',4)->find("font", 0)->innertext);
			$pdfPath = str_replace('&nbsp;', '', $row->find('td',5)->find("a", 0)->href); 
			$pdf = file_get_contents($baseUrl . $pdfPath);			
			
			$insertResult = '';
// 			foreach($tableCells as $cell){
// 				print $cell->innertext . '<br/>';
// 			}
			
			if(!empty($resturantName)){
				
				if(!empty($pdf)){
					$lastInsertId = 0;
					$pdfName = explode('/', $pdfPath)[1];
					$pdfName = explode('.', $pdfName)[0];
					$insertStatement = 'INSERT INTO pdfs(name, file) 
										VALUES("' . 
										$db->real_escape_string($pdfName) . '", "' . 
										$db->real_escape_string(base128::encode($pdf)) . '");';

					$insertResult = $db->query($insertStatement);
					$lastInsertId = $db->insert_id;
					if(!$insertResult){
						print 'Unable to insert PDF into DB';
					}
				}
				
				$insertStatement = 'INSERT INTO establishments(name, address, city, inspectionDate, colourImage, reinspectionDate, pdfId) 
									VALUES("' . 
									$db->real_escape_string(html_entity_decode($resturantName)) . '", "' . 
									$db->real_escape_string($address) . '", "' . 
									$db->real_escape_string($city) . '", "' . $db->real_escape_string($inspectionDate) . '", "' . 
									$db->real_escape_string($colourImage) . '", "' . $db->real_escape_string($reinspectionDate) . '", ' .	
									$db->real_escape_string($lastInsertId) . ');';

				$insertResult = $db->query($insertStatement);
			}

			if(!$insertResult){
				print 'Error inserting into th DB ' .  $db->error . "<br/>\n";
			}else{
				print 'Inserted 1 row successfully<br/>' . "\n";
			}
		}
		
		print '<br/>Working on letter ' . $letter . "<br/> \n";

		$resturantList = array();
		
		print '<br/>Starting on establishment list for letter ' . $letter . "\n";
		// For each row in the list of th resturants

		// if($count % 10 == 0)
		// 	print 'Inserted ' . $count . ' rows. Still working... <br/>';
		// if($db->error != "")
		// 	print $db->error;
// 		sleep(3);
	
	}
// }catch(Exception $e){
// 	print $e->getMessage();	
// }

// for($i=0;$i<count($completeResults);$i++){
// 	json_encode($completeResults[0]);
// }
$db->close();

// file_put_contents('scraper.log', $log, FILE_APPEND);

curl_close($ch);

print 'Done';


?>
