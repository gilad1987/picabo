<?php
if(!isset($_GET['file_token'])){
    die('No Access Permission');
}

require_once 'Images.php';
$image = new Images();
$src = $image->get($_GET['file_token']);
?>


<html>
<head>
	<title>Picabo - send self-deleted images through any platform</title>	
	<link rel="stylesheet" type="text/css" href="view/css/main.css">
</head>
<body>
	<article id="main" class="center" style="">

		<?php include 'templates/header.php';?>
		<a href="/" class="logo-side"></a>


        <?php if(empty($src)):?>

            <div id="page404">
                <p>:/</p>
                <h1>Nope, not here.</h1>
            </div>

        <? else:?>

            <div id="image-container">
                <img src="<?php echo $src; ?>">
            </div>
        <?php endif; ?>

		<?php include 'templates/footer.php';?>


	</article>


</body>
</html>