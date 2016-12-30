<html>
<head>
<link rel="stylesheet" type="text/css" href="gallery.css">
<script src="jquery-3.1.0.js"></script>
<script src="animateShadows/jquery.animate-shadow.js"></script>
</head>
<body>
<?php
include 'header.php';
$ImageQuery = "SELECT * FROM Images ORDER BY ID DESC";
$GrabbedImages = $pdo->query($ImageQuery);
$Images = $GrabbedImages->fetchAll(PDO::FETCH_NUM);
$count = 1;
$ypos = 0;
echo '<div id="gallerymain">';
//Iterate left
echo '<div class="galleryleft">';
foreach($Images as $Image){
	if($Image[0] == $Image[3] || $Image[3] == 0){
		if($count == 1){
			echo '<div class="galleryimagediv" style="top:' . $ypos .'px;">';
			echo '<img class="galleryimage" src="GalImages/';
			echo $Image[0] . "." . $Image[2];
			echo '" ' . 'data-album="' . $Image[3];
                        echo '" ' . 'data-id="' . $Image[0];
                        echo '" ' . 'data-orientation="' . $Image[4];
			echo '">';
			echo '</div>';
			$count++;
		}
		else if($count == 2){
			$count++;
		}
		else{
			$count = 1;
			$ypos = $ypos + 450;
		}
	}
}
echo '</div>';
$count = 1;
$ypos = 0;
//Iterate middle
echo '<div class="gallerymiddle">';
foreach($Images as $Image){
	if($Image[0] == $Image[3] || $Image[3] == 0){
        	if($count == 1){
        	        $count++;
        	}
        	else if($count == 2){
        	        echo '<div class="galleryimagediv" style="top:' . $ypos . 'px;">';
        	        echo '<img class="galleryimage" src="GalImages/';
        	        echo $Image[0] . "." . $Image[2];
                        echo '" ' . 'data-album="' . $Image[3];
                        echo '" ' . 'data-id="' . $Image[0];
                        echo '" ' . 'data-orientation="' . $Image[4];
        	        echo '">';
        	        echo '</div>';
        	        $count++;
        	}
        	else{
			$ypos = $ypos + 450;
        	        $count = 1;
		}
	}
}
echo '</div>';
$count = 1;
$ypos = 0;
//Iterate right
echo '<div class="galleryright">';
foreach($Images as $Image){
	if($Image[0] == $Image[3] || $Image[3] == 0){
        	if($count == 1){
        	        $count++;
        	}
        	else if($count == 2){
        	        $count++;
        	}
        	else{
        	        echo '<div class="galleryimagediv" style="top:' . $ypos . 'px;">';
        	        echo '<img class="galleryimage" src="GalImages/';
        	        echo $Image[0] . "." . $Image[2];
                        echo '" ' . 'data-album="' . $Image[3];
                        echo '" ' . 'data-id="' . $Image[0];
                        echo '" ' . 'data-orientation="' . $Image[4];
        	        echo '">';
        	        echo '</div>';
        	        $ypos = $ypos + 450;
        	        $count = 1;
        	}
	}
}
echo '</div>';
echo '</div>';
?>
</body>
</html>
<script>
$(document).ready(function(){
$(".galleryimage").each(function(){
	var imageContainer = $(this).parent();
	var rightPos = $(this).css("margin-right");
	imageContainer.append("<img src='BaseImages/DeleteImg.png' data-id='" + $(this).attr("data-id") + "' data-album='"+ $(this).attr("data-album") + "' style='top: 0; right:"+rightPos+";' class='deleteImg'>/>");
});

$(".deleteImg").click(function(){
var ID = $(this).attr("data-id");
var Album = $(this).attr("data-album");
var c = confirm("Delete this image or album?");
if(!c){return;}
        $.ajax({
                data: {
                        'ID': ID,
                        'Album': Album
                },
                type: 'POST',
                url: "removeImages.php",
                success: function(message){
                        alert(message);
                        location.reload(true);
                },
                error: function(){
                        alert("Failed to remove images.");
                }
        });
});

$(".galleryimage").click(function(){
	var Album = $(this).attr('data-album');
	var ID = $(this).attr('data-id');
	if(Album == undefined){Album = 0;}
	$("body").append("<div class='galleryimagezoomdiv'></div>");
	var img = $(this).attr("src");
	$("body").append("<img class='galleryimagezoom' src='" + img + "' data-album='" + Album + "' data-id='" + ID + "'/>");
        var left = $(".galleryimagezoom:last").css("left").slice(0,-2);
        var width = $(".galleryimagezoom:last").width();
        left=left - (width/2);
        $(".galleryimagezoom").css("left", left);
	if(Album != 0){
        var left = parseInt($(".galleryimagezoom:last").css("left"));
        var width = parseInt($(".galleryimagezoom:last").css("width"),10);
        var height = (parseInt($(".galleryimagezoom:last").css("height"), 10) + 60);
        $("body").append("<img src='BaseImages/NextImg.png' class='leftselect'/>");
        $(".leftselect:last").css({
                "top":((parseInt($(".galleryimagezoom").css("height"),10)/2)+50)
        });
        $("body").append("<img src='BaseImages/NextImg.png' class='rightselect'/>");
        $(".rightselect:last").css({
                "top":((parseInt($(".galleryimagezoom").css("height"),10)/2)+50)
        });

	}


        $("html, body").css({
                "overflow": "hidden",
                "height": "100%"
        });
});

$(document).on("click", ".galleryimagezoomdiv", function(){
	$(".galleryimagezoom").remove();
	$(".galleryimagezoomdiv").remove();
	$(".leftselect:last").remove();
	$(".rightselect:last").remove();

	$("html, body").css({
		"overflow": "auto",
		"height": "auto"
	});
});

$(document).on("mouseup", ".leftselect", function(){
	var element = $(".galleryimagezoom:last");
        var Album = element.attr('data-album');
        var imgID = element.attr('data-id');
        if(Album == undefined){Album = 0;}

	$.ajax({
		data: {
			'album': Album,
			'id': imgID
		},
		type: 'GET',
		url: "scrollLeft.php",
		success: function(img){
			$(".galleryimagezoom:last").attr("src", "GalImages/" + img);
			imgID = img.split(".")[0];
			element.attr("data-id", imgID);
			},
		error: function(){
			alert("Failed to retrieve album image.");
		}
	});
	addSelectors();

});
$(document).on("mouseup", ".rightselect", function(){
        var element = $(".galleryimagezoom:last");
        var Album = element.attr('data-album');
        var imgID = element.attr('data-id');
        if(Album == undefined){Album = 0;}

        $.ajax({
                data: {
                        'album': Album,
                        'id': imgID
                },
                type: 'GET',
                url: "scrollRight.php",
                success: function(img){
                        $(".galleryimagezoom:last").attr("src", "GalImages/" + img);
                        imgID = img.split(".")[0];
                        element.attr("data-id", imgID);
                        },
                error: function(){
                        alert("Failed to retrieve album image.");
                }
        });
});


//End document ready
});

</script>
