<?php
require_once(__DIR__ . '/../config/config.php');

$id = "";

if(isset($_GET['id'])){
    $id = $_GET['id'];
}elseif(isset($argv[1])){
    $id = $argv[1];
}

// $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

$filename = "";

if(is_numeric($id)){
    // if(!$result = $db->query('SELECT name, data FROM pdfs where id = ' . $db->real_escape_string($id) . ';')){
    //     die('There was an error running the query [' . $db->error . ']<br/>');
    // }else{
    //     $pdf = $result->fetch_assoc();
    //     if(!file_exists('./' . $pdf['name'])){
    //         if(file_put_contents($pdf['name'] . '.zip', base64_decode($pdf['data']))){
    //             if(file_exists($pdf['name'] . '.zip')){
    //                 $zip = new ZipArchive();
    //                 if ($zip->open($pdf['name'] . '.zip') === TRUE) {
    //                     $zip->extractTo('./' . $pdf['name']); 
    //                     $zip->close();
    //                 }
    //                 unlink($pdf['name'] . '.zip');
    //             }else{
    //                 print 'Zip file was not created';
    //             }
    //         }else{
    //             print 'Zip file was not created';
    //         }
    //     }
    // }

    $data = array(
        "host" => DB_SERVER,
        "user" => DB_USER,
        "password" => DB_PASS,
        "dbname" => DB_NAME,
        "id" => escapeshellcmd($id)
    );

    // print escapeshellcmd('extract.py \'' . json_encode($data) . '\'');
    // $command = escapeshellcmd('extract.py \'' . json_encode($data) . '\'');
    $command = 'python extractPdf.py \'' . json_encode($data) . '\'';
    $filename = exec($command);

}else{
    print "Error: ID " . $id . " was not numeric <br/>\n";
}

// $files = scandir(__DIR__ . '/' . $pdf['name']);
// if(count($files) > 2)
//     print $pdf['name'] . '/' . $files[2];

if(file_exists($filename)){
    print $filename;
}else{
    print 'No file: ' . $filename;
}