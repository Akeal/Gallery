<html>
<head>
<link rel="stylesheet" type="text/css" href="gallery.css">
<script src="jquery-3.1.0.js"></script>
<script src="animateShadows/jquery.animate-shadow.js"></script>
</head>
<body>
<?php
include 'header.php';
$ImageQuery = "SELECT * FROM Images WHERE Type = 'sculpture' ORDER BY ID DESC";
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
var orientation = $(this).attr("data-orientation");
//alert($(this).attr("data-orientation"));
	        if(orientation == 2){
                        $(this).css("transform","scaleX(-1)");
                }
                else if(orientation == 3){
                        $(this).css("transform", "rotate(180deg)");
                }
                else if(orientation == 4){
                        $(this).css("transform", "scaleY(-1)");
                }
                else if(orientation == 5){
                        $(this).css("rotate(90deg)", "scaleY(-1)");
                }
                else if(orientation == 6){
                        $(this).css("transform", "rotate(90deg)");
                }
                else if(orientation == 7){
                        $(this).css("transform", "rotate(270deg) scaleY(-1)");
                }
                else if(orientation == 8){
                        $(this).css("transform", "rotate(270deg)");
                }

                var width=$(this).width();
                var height=$(this).height();
                var ymargin=((height-width)/2);
                var xmargin=((width-height)/2);
                if(orientation > 4){
                        $(this).css("max-width", 350);
                        $(this).css("max-height", 'calc(100% - 60px)');
                        $(this).width(height);
                        $(this).height(width);
                        $(this).css("margin-top",ymargin);
                        $(this).css("margin-bottom",ymargin);
                        $(this).css("margin-left",xmargin);
                        $(this).css("margin-right",xmargin);
			$(this).css("box-shadow", "15px 0px 15px black")
                }
                else{
                        $(this).css("max-width", 'calc(100% - 60px)');
                        $(this).css("max-height", 350);
                }

});
$(".galleryimage").hover(function(){
	if(Number($(this).parent().css("top").slice(0,-2))%450 == 0 || Number($(this).parent().css("top").slice(0,-2)) == 0 ){
		var start = $(this).parent().css("top");
		start = start.slice(0,-2);
		start = Number(start);
	}
	var end = start - 20;
	$(this).parent().stop().animate({ top: end },"fast");
	$(this).stop().animate({boxShadow: "0px 35px 40px black"},"fast");
}, function(){
        var start = $(this).parent().css("top");
        start = start.slice(0,-2);
        start = Number(start);
	start = Math.round(start / 450) * 450;
	$(this).parent().stop().animate({ top: start },"fast");
	$(this).stop().animate({ boxShadow: "0px 15px 15px black" },"fast");
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
