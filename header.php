<html>
<?php
include("connection.php");
?>
<div id="banner">
<div class="headRut" id="linkStyle1"></div>
<a href="index.php" class="headlink" id="headlink1">All Images</a>
<div class="headRut" id="linkStyle2"></div>
<a href="sculpture.php" class="headlink" id="headlink2">Sculpture</a>
<div class="headRut" id="linkStyle3"></div>
<a href="drawing.php" class="headlink" id="headlink3">Drawing</a>
<div class="headRut" id="linkStyle4"></div>
<a href="miscellaneous.php" class="headlink" id="headlink4">Miscellaneous</a>
</div>
</html>
<script>
$(".headlink").hover(function(){
	$(this).stop().animate({top: '10px'},"fast");
});
$(".headlink").mouseleave(function(){
	$(this).stop().animate({top: '5px'},"fast");
});
</script>
