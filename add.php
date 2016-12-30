<html>
<head>
<link rel="stylesheet" type="text/css" href="gallery.css">
<script src="jquery-3.1.0.js"></script>
<script src="exif/exif.js"></script>
</head>
<body>
<?php
include 'header.php';
?>
<div id="addbody">
	<div id="options">
		<input type="file" name="file" id="file" class="inputfile" multiple = "multiple"/>
		<label for="file">Choose Image</label>
		<div>
			<p><input type="radio" name="type" value="sculpture">Sculpture</p>
                	<p><input type="radio" name="type" value="drawing">Drawing</p>
                	<p><input type="radio" name="type" value="miscellaneous">Miscellaneous</p>
		</div>
	</div>
<div id="previewdiv">
</div>
</div>
</body>
</html>
<script>
$(document).ready(function(){
var totalImages = 0;
var orientation;
var imgCount = 0;
var imagesArray = [];
var extensionArray = [];
var orientationArray = [];
var exifCheck = false;
$('#file').on('change', function(){
	for(i=0; i<this.files.length; i++){
		exifCheck = false;
		EXIF.getData(this.files[i], function(){
			orientation = EXIF.pretty(this).indexOf("Orientation : ");
			orientation = EXIF.pretty(this)[orientation + 14];
			exifCheck = true;
		});
		var reader = new FileReader();
		var file = document.getElementById("file").files[i];
        	if(file){
        	        reader.readAsDataURL(file);
        	}
		reader.onload = function(e){
			var img = e.target.result;
			var fileInfo = img.split(",")[0].split(":")[1].split(";")[0].split("/");
        		if(fileInfo[0] != "image" || (fileInfo[1] != "jpeg" && fileInfo[1] != "jpg" && fileInfo[1] != "gif" && fileInfo[1] != "png")){
                	alert("A File is not an image or is not jpeg, jpg, png, or gif.");
                	return;
        	}
		//Push image extension to extension array
                extensionArray.push(fileInfo[1]);

		//One append line necessary to insert all the content of previewImgDiv and then close it. I know it looks messy.
                $("#previewdiv").append("<div class='previewImgDiv'><img src='"+ img +"' class='previewImg' id='img" + imgCount + "'/><img src='BaseImages/DeleteImg.png' data-index='" + imgCount + "' class='deleteImg'></div>");
		$("#previewdiv").append("<canvas class='uploadCanvas' id='canvas" + imgCount + "'/>");

		//Get original file width/height to size canvas
		$("body").append("<img style ='visibility: hidden' src='" + img +"'/>");
		var origImgHeight = $("img:last").height();
		var origImgWidth = $("img:last").width();
		$("img:last").remove();

		var canvas = document.getElementById("canvas" + imgCount);
		//Set canvas size
		if(orientation > 4){
			canvas.width = origImgHeight;
			canvas.height = origImgWidth;
			var ctx = canvas.getContext("2d");
			var newImage = new Image();
			newImage.src = img;
		}
		else{
                        canvas.width = origImgWidth;
                        canvas.height = origImgHeight;
                        var ctx = canvas.getContext("2d");
                        var newImage = new Image();
                        newImage.src = img;
		}

                var element = $(".previewImg:last");
		var elementDelete = $(".deleteImg:last");

		//Rotate/mirror image in canvas depending on EXIF orientation
		if(orientation == 2){
			ctx.translate(canvas.width, 0);
                        ctx.scale(-1, 1);
                        ctx.drawImage(newImage, 0, 0);
		}
		else if(orientation == 3){
			ctx.translate(canvas.width, canvas.height);
                        ctx.rotate(180 * Math.PI/180);
                        ctx.drawImage(newImage, 0, 0);
		}
        	else if(orientation == 4){
                        ctx.translate(0,canvas.height);
                        ctx.scale(1, -1);
                        ctx.drawImage(newImage, 0, 0);
        	}
        	else if(orientation == 5){
                        ctx.translate(newImage.height, 0);
                        ctx.rotate(90 * Math.PI/180);
			ctx.translate(0,canvas.width);
			ctx.scale(1, -1);
                        ctx.drawImage(newImage, 0, 0);
        	}
		else if(orientation == 6){
        	        ctx.translate(newImage.height, 0);
                	ctx.rotate(90 * Math.PI/180);
                	ctx.drawImage(newImage, 0, 0);
		}
        	else if(orientation == 7){
                        ctx.translate(0, newImage.width);
                        ctx.rotate(270 * Math.PI/180);
                        ctx.translate(0,canvas.width);
                        ctx.scale(1, -1);
                        ctx.drawImage(newImage, 0, 0);
        	}
        	else if(orientation == 8){
                        ctx.translate(0, newImage.width);
                        ctx.rotate(270 * Math.PI/180);
                        ctx.drawImage(newImage, 0, 0);
        	}
		else{
			ctx.drawImage(newImage, 0, 0);
		}
		var finalImage = canvas.toDataURL("image/" + fileInfo[1]);
		imagesArray.push(finalImage);
		element.attr("src", finalImage);
		//Place delete box
		elementDelete.css("top", parseInt(element.css("margin-top")) + parseInt(element.css("border-width")));
		elementDelete.css("left", element.outerWidth()+ parseInt(element.css("margin-left")));

		//Update upload button presence/text
		updateUploadButton(totalImages, totalImages+1);
		imgCount++;
		}
	}
});

//Update upload button presence/text
function updateUploadButton(prev, current){
	if(current == 1 && prev == 0){
		$("#options").append("<input name='uploadButton' id='uploadButton' type='button'> ");
		$("#options").append("<label id='uploadLabel' for='uploadButton'>Upload Image</label>");
	}
	else if(current == 1 && prev == 2){
		$("#uploadLabel").empty();
		$("#uploadLabel").append("Upload Image");
	}
	else if(current > 1){
		$("#uploadLabel").empty();
		$("#uploadLabel").append("Upload Album");
	}
	else if(current == 0){
		$("#uploadLabel").remove();
		$("#uploadButton").remove();
	}
	totalImages=current;
}

//Remove preview image if delete button clicked and remove from arrays
$(document).on('click', '.deleteImg', function(){
	var index = $(this).attr("data-index");
	imagesArray.splice(index, 1);
	extensionArray.splice(index, 1);
	$("#canvas" + index).remove();
	$(this).parent().remove();
	$(this).remove();
	imgCount--;
	updateUploadButton(totalImages, totalImages-1);
});

//Upload image/album on click
$(document).on('click', '#uploadButton', function(){
	if($("input[name=type]:checked").length == 0){
		alert("Select image/album type.");
		return;
	}
	var type = $("input[name=type]:checked").val();
	$.ajax({
		data: {
			'images': imagesArray,
			'extension': extensionArray,
			'type': type
		},
		type: 'POST',
		url: "uploadImages.php",
		success: function(message){
			alert(message);
			location.reload(true);
		},
		error: function(){
			alert("Failed to upload images.");
		}
	});
});


});
</script>
