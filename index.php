<html>
<head>
    <title>Picabo - send self-deleted images through any platform</title>
    <link rel="stylesheet" type="text/css" href="view/css/main.css">
    <link rel="stylesheet" type="text/css" href="view/css/dropzone.css"/>
    <link rel='stylesheet' id='google-font2-css'  href='//fonts.googleapis.com/css?family=Merriweather%3A300%2C400%2C700&#038;ver=2.0.0-RC2' type='text/css' media='all'/>
    <link href='http://fonts.googleapis.com/css?family=Hammersmith+One' rel='stylesheet' type='text/css'>
    <script src="view/js/jquery.js"></script>
    <script src="view/js/dropzone.js"></script>

    <script>
        Dropzone.options.uploadfile = {
            success:function(file,response,xhr){
                response = JSON.parse(response);
                if(response.success){
                    var input = document.createElement('input');
                    input.setAttribute('type','text');
                    input.className = "input_url";
                    input.value = response.url;
                    $('#form_container').html(input);
                }
            }
        };
    </script>

    <style>
        .dropzone-conteiner{width:800px; height:360px}
        .dropzone-conteiner input{display: block; margin:0 auto; width:80%; line-height: 30px; font-size: 30px; text-align: center}
    </style>
	
	<meta property="og:type"            content="website" /> 
	<meta property="og:url"             content="http://www.giladt.com/picabo" />
	<meta property="og:title"           content="<?php echo $title ?>" /> 
	<meta property="og:image"           content="" /> 
	
</head>
<body>


<article id="main" class="center" style="">
	<?php require_once "templates/header.php" ?>
    <h1 class="center"><img src="view/images/logo-200.png"></h1>		
    <h2 id="subtitle">Share Self-Deleted Images<br> Secretly & Effortlessly.</h2>
    <div class="dropzone-conteiner" id="form_container">
        <form  class='dropzone' id="uploadfile" action="upload_file.php" method="post" enctype="multipart/form-data"></form>
    </div>


    <div id="description-menu">
        <div class="col-3">
            <img src="view/images/dragndrop.png">
            <h2>Drag . Drop . Link</h2>
            <p>

            </p>
        </div>
        <div class="col-3">
            <img src="view/images/shred.png">
            <h2>Secure</h2>
            <p>

            </p>
        </div>
        <div class="col-3">
            <img src="view/images/mobile.png">
            <h2>Android Soon!</h2>
            <p></p>

        </div>
    </div>
    <div class="clear"></div>

	<?php require_once "templates/footer.php" ?>



</article>



</body>
</html>
