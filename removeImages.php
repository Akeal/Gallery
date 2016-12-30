<?php
include("connection.php");

$ID = $_POST['ID'];
$Album = $_POST['Album'];

$ImageRemove = $pdo->prepare("DELETE FROM Images WHERE ID = ?");

//Remove single image from file storage and database
if($Album == 0){
	$GetExt = $pdo->prepare("SELECT Extension FROM Images WHERE ID = ?");
	$GetExt->execute(array($ID));
	$Ext = $GetExt->fetch(PDO::FETCH_NUM);
	unlink("GalImages/" . $ID . "." . $Ext[0]);
	$ImageRemove->execute(array($ID));
	echo "Image removed.";
}
//Remove all images in album from file storage and database
else{
	$GetFiles = $pdo->prepare("SELECT ID, Extension FROM Images WHERE Album = ?");
	$GetFiles->execute(array($Album));
	$Files = $GetFiles->fetchAll(PDO::FETCH_NUM);
	foreach($Files as $File){
		unlink("GalImages/" . $File[0] . "." . $File[1]);
		$ImageRemove->execute(array($File[0]));
	}
	echo "Album removed.";
}
?>
