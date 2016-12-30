<?php
include("connection.php");

$ImagesArray = $_POST['images'];
$ExtensionArray = $_POST['extension'];
$ImageInsert = $pdo->prepare('INSERT INTO Images (Type, Extension, Album) VALUES (?, ?, ?)');
$UpdateFirst = $pdo->prepare('UPDATE Images SET Album = ? WHERE ID = ?');
$uploaded = true;

if(sizeof($ImagesArray) == 1){
	//Insert image info into database
        $ImageInsert->execute(array($_POST['type'], $ExtensionArray[0], 0));
	$ID = $pdo->lastInsertId();
	//Decode image
	//Get all data after "base64"
	$fileData = explode(',', $ImagesArray[0]);
	$uploadFile = base64_decode($fileData[1]);
	//Upload file
	$fileDir = "GalImages/" . $ID . "." . $ExtensionArray[0];
	$upload = fopen($fileDir, "w");
	if(!fwrite($upload, $uploadFile)){
		$uploaded = false;
	}
	fclose($upload);
}


if(sizeof($ImagesArray) > 1){
$AlbumID = 0;
	for($i=0; $i<sizeof($ImagesArray); $i++){
    	        //Insert image info into database
        	$ImageInsert->execute(array($_POST['type'], $ExtensionArray[$i], $AlbumID));
        	$ID = $pdo->lastInsertId();
		//If first in album uploaded, set album = ID
		if($i==0){
			$AlbumID = $ID;
			$UpdateFirst->execute(array($ID, $ID));
		}
        	//Decode image
        	//Get all data after "base64"
        	$fileData = explode(',', $ImagesArray[$i]);
        	$uploadFile = base64_decode($fileData[1]);
        	//Upload file
        	$fileDir = "GalImages/" . $ID . "." . $ExtensionArray[$i];
        	$upload = fopen($fileDir, "w");
        	fwrite($upload, $uploadFile);
        	fclose($upload);
	}
}

if($uploaded && sizeof($ImagesArray) == 1){
	echo "Image uploaded!";
}
else if($uploaded && sizeof($ImagesArray) > 1){
	echo "Album uploaded!";
}
else if(!$uploaded && sizeof($ImagesArray) == 1){
	$deleteSingleEntry = $pdo->prepare('DELETE FROM Images WHERE ID = ?');
	$deleteSingleEntry->execute(array($ID));
	echo "Image not uploaded.";
}
else if(!$uploaded && sizeof($ImagesArray) > 1){
	$deleteAlbumEntry = $pdo->prepare('DELETE FROM Images WHERE Album = ?');
	$deleteAlbumEntry->execute(array($AlbumID));
	echo "Album not uploaded.";
}
?>
