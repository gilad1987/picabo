<html>
<head>
	<title>Picabo</title>
	<link rel="stylesheet" type="text/css" href="view/css/main.css">
</head>
<body>
	<article id="main" class="center" style="">
        <header>
            <div id="counter">
                <?
                require_once 'Uploads.php';
                ?>
                <span id="number"><?php echo htmlspecialchars(Uploads::getCount())?></span> images has been self-destructively shared
            </div>
        </header>

		<div id="page404">
			<p>:/</p>
			<h1>Nope, not here.</h1>
		</div>

        <footer>
            <hr>
            <div id="copyright">
                All copyrights reserved &copy; 2014 <strong>&#183;</strong> Made with &#9825 in Tel-Aviv <strong>&#183;</strong> ### Images shared!
            </div>
            <div id="menu">
                <a href="./">Home</a>
                <strong>&#183;</strong>
                <a href="mailto:a@a.com">Contact Us</a>
                <strong>&#183;</strong>
                <a href="changes.php">Changes</a>

            </div>

        </footer>

	</article>


</body>
</html>
