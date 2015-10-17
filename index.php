<?php

	require_once('config.php');

	if(isset($_POST['logout'])) {
		session_destroy();
		$isAdmin = false;
	}
	else if(isset($_POST['password']) && !$isAdmin) {
		if($_POST['password'] == $adminpass) {
			$_SESSION['isAdmin'] = true;
			$isAdmin = true;
			$_SESSION['attempts'] = 0;
		}
		else if(isset($_SESSION['attempts'])) {
			if(++$_SESSION['attempts'] > 5)
				banIP();
		}
		else {
			$_SESSION['attempts'] = 1;
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Bernie Sanders Quotes</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.24.1" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css?nocache">
	<script src="index.js?nocache"></script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script>
	<?php
		echo 'var isAdmin = '.(isAdmin?'true':'false').';';
	?>
	</script>
</head>

<body>
	<h1>Bernie Sanders Quotes</h1>
	<div id="total">Total number of quotes: <span id="total-no"></span></div>
	<?php
		if(isset($_GET['admin'])) {
			if($isAdmin)
				echo '<div><form name="admin-form" method="POST"><input name="logout" type="submit" value="logout"></form></div>';
			else
				echo '<div><form name="admin-form" method="POST">Enter Admin Password: <input name="password" type="password"><input type="submit" value="go"></form></div>';
		}
	?>
	<div id="frame">
		<h2>Find a quote:</h2>
		<div class="form" id="getquotes" name="getquotes">
			<div class="p">
				Query (opt.): <input id="query" name="query">
				</div>
			<div class="p" id="tag-cloud">
			</div>
			<div class="p">
				<input type="button" value="Search" onclick="doAjax(true,'getquotes')">
			</div>
		</div> 
		<h2>Add a quote:</h2>
		<div class="form" id="addquote" name="addquote">
			<div class="p">
				Quote: <br/>
				<textarea id="quote" name="quote" cols=64 rows="6" cols="40"  maxlength="2000"></textarea><br/>
			</div>
			<div class="p">
				Source Title (opt.): <input id="sourcename" name="sourcename"><br>
			</div>
			<div class="p">
				Source URL (req.): <input id="source" name="source"><br>
			</div>
			<div class="p">
				Tags (comma-separated, req.): <input id="tags" name="tags"><br>
			</div>
			<?php
				if(isset($_GET['admin'])) {
					if(!$isAdmin)
						echo '			<div class="g-recaptcha" data-sitekey="6LfW-g4TAAAAAFWShjhL46OrG7RGYtIedrbdzlx9"></div>';
				}
			?>
			<div>
			   <input type="button" value="Add" onclick="doAjax(true,'addquote')">
			   <input type="button" onclick="clearForm()" value="Clear">
			</div>
		</div> 
		<h2 id="quote-title">Random Quote:</h2>
		<div id="randquote" name="randquote">
			<div id="quote-list"></div>
			<div class="p">
				<input type="button" value="Spin!" onclick="doAjax(true,'randquote')">
			</div>
		</div> 
	</div>
</body>

</html>
