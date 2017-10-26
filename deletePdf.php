<?php
require_once(__DIR__ . '/../config/config.php');

$id = "";

$delay = 0;

if(isset($_GET['delay'])){
    $delay = $_GET['delay'];
}elseif (isset($argv[2])) {
    $delay = $argv[2];
}

if(isset($_GET['id'])){
    $id = $_GET['id'];
}elseif(isset($argv[1])){
    $id = $argv[1];
}

// $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if(is_numeric($delay)){
    sleep($delay);
}

if(is_numeric($id)){
    // if(!$result = $db->query('SELECT name FROM pdfs where id = ' . $db->real_escape_string($id) . ';')){
    //     die('There was an error running the query [' . $db->error . ']<br/>');
    // }else{
    //     $pdf = $result->fetch_assoc();
    //     if(file_exists($pdf['name'])){
    //         chmod($pdf['name'], 777);
    //         $files = scandir(__DIR__ . '/' . $pdf['name']);
    //         for($i=0; $i<count($files); $i++){
    //             if(strlen($files[$i]) > 2){
    //                 unlink($pdf['name'] . '/' . $files[$i]);
    //             }
    //         }

    //         if(count(scandir(__DIR__ . '/' . $pdf['name'])) == 2)
    //             rmdir('./' . $pdf['name']);
    //         else
    //             print 'File not empty';
    //     }
    // }

    $data = array(
        "host" => DB_SERVER,
        "user" => DB_USER,
        "password" => DB_PASS,
        "dbname" => DB_NAME,
        "id" => escapeshellcmd($id)
    );

    $command = 'python deletePdf.py \'' . json_encode($data) . '\'';
    $filename = exec($command);

}