<?php

require_once('class/BCGFontFile.php');
require_once('class/BCGColor.php');
require_once('class/BCGDrawing.php');
require_once('class/BCGcode39.barcode.php');

$colorFront = new BCGColor(0, 0, 0);
$colorBack = new BCGColor(255, 255, 255);

$font = new BCGFontFile('./font/Arial.ttf', 20);

require_once('../support/config.php');
$stmt=$con->prepare("SELECT code,name FROM employees");
$stmt->execute();

while($employee=$stmt->fetch(PDO::FETCH_ASSOC)){
$code = new BCGcode39(); // Or another class name from the manual
$code->setScale(2); // Resolution
$code->setThickness(30); // Thickness
$code->setForegroundColor($colorFont); // Color of bars
$code->setBackgroundColor($colorBack); // Color of spaces
$code->setFont($font); // Font (or 0)
$code->parse($employee['code']);
$code->clearLabels();
$drawing = new BCGDrawing('', $colorBack);
$drawing->setFilename('./barcodes/'.$employee['name'].'.png');
$drawing->setBarcode($code);
$drawing->draw();

$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);	
}
die;
$emp_codes=array('11506025','19810002','11404011','11105007','10907001','19411001','11302006');
$count=0;

foreach($emp_codes as $number){
$code = new BCGcode39(); // Or another class name from the manual
$code->setScale(2); // Resolution
$code->setThickness(30); // Thickness
$code->setForegroundColor($colorFont); // Color of bars
$code->setBackgroundColor($colorBack); // Color of spaces
$code->setFont($font); // Font (or 0)
$code->parse($number);
$drawing = new BCGDrawing('', $colorBack);
$drawing->setFilename($count.'.png');
$drawing->setBarcode($code);
$drawing->draw();

$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
$count++;
}
header('Content-Type: image/png');