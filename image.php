<!--
if(!isset($_GET['file_token'])){
    die('No Access Permission');
}

require_once 'Images.php';
$image = new Images();
$image->get($_GET['file_token']);

 -->


<html>
<head>
	<title>Picabo - send self-deleted images through any platform</title>	
	<link rel="stylesheet" type="text/css" href="view/css/main.css">
</head>
<body>
	<article id="main" class="center" style="">

		<?php include 'templates/header.php';?>
		<a href="/" class="logo-side"></a>


		<div id="image-container">		
			<img src="http://news.distractify.com/wp-content/uploads/2014/01/new-userguide-image.jpg">			
		</div>


		<?php include 'templates/footer.php';?>


	</article>


</body>
</html>