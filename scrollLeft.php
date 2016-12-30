<?php
include("connection.php");

$findImage = $pdo->prepare("SELECT ID, Extension FROM Images WHERE Album = ? ORDER BY ID DESC");
$findImage->execute(array($_GET['album']));
$Album = $findImage->fetchAll(PDO::FETCH_NUM);
$nextID = $_GET['id'];
$extension;
$wrap = true;
foreach($Album as $Image){
	if($Image[0] < $_GET['id']){
		$nextID = $Image[0];
		$extension = $Image[1];
		$wrap = false;
		break;
	}
}
if($wrap == true){
        $findImage->execute(array($_GET['album']));
		$Image = $findImage->fetch(PDO::FETCH_NUM);
	$nextID = $Image[0];
	$extension = $Image[1];
}
$nextImg = $nextID . "." . $extension;
echo $nextImg;
?>
