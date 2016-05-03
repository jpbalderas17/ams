<?php

require_once('../support/config.php');
if(!isLoggedIn()){
		toLogin();
		die();
	}
if(empty($_GET['id'])){
	redirect("assets.php");
	die;
}
else{
	$id=$_GET['id'];
}
require_once('class/BCGFontFile.php');
require_once('class/BCGColor.php');
require_once('class/BCGDrawing.php');
require_once('class/BCGcode128.barcode.php');







$asset=$con->myQuery("SELECT asset_tag,asset_name,model FROM qry_assets WHERE id=?",array($id))->fetch(PDO::FETCH_ASSOC);
if(empty($asset)){
	Modal("Asset does not Exist.");
	redirect("index.php");
	die();
}
try{
$colorFront = new BCGColor(0, 0, 0);
$colorBack = new BCGColor(255, 255, 255);

$font = new BCGFontFile('./font/Arial.ttf', 11);
$code = new BCGcode128(); // Or another class name from the manual
$code->setScale(2); // Resolution
$code->setThickness(30); // Thickness
$code->setForegroundColor($colorFront); // Color of bars
$code->setBackgroundColor($colorBack); // Color of spaces
$code->setFont($font); // Font (or 0)
$code->parse($asset['asset_tag']);
$code->clearLabels();

$labe=new BCGLabel($asset['asset_name']." (".$asset['model'].")",$font,BCGLabel::POSITION_TOP);
$code->addLabel($labe);
// $code->parse('4124');

}catch(Exception $exception){
//	var_dump($exception);
}
$drawing = new BCGDrawing('', $colorBack);
//$drawing->setFilename('./test.png');
$drawing->setBarcode($code);
$drawing->draw();
//header('Content-Disposition: attachment; filename="'.$asset['asset_tag'].'.png"');
header('Content-Type: image/png');
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

die();