<?php
require_once(__DIR__ . '/../config/config.php');

$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
$result = $db->query('SELECT DISTINCT id FROM pdfs;');
while($pdf = $result->fetch_assoc()){
    $output = array();
    exec('php extractPdf.php ' .  escapeshellarg($pdf['id']), $output);
    $filepath = $output[0];
    $im = new imagick($filepath);
    $im->setImageFormat('jpg');
}

