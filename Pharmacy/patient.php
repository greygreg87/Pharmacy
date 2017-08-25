<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
	
	$imie=$_SESSION['imie'];
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Cyfrowa Apteka</title>
	<meta name="description" content="Cyfrowa platforma informacyjna o zażywanych lekach">
	<meta name="keywords" content="recepta, lekarz, zdrowie, antybiotyk, pacjent, leczenie">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="fontello/css/fontello.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>

<body>
	
	<header>
		<div class="logo"><a href="index.php" class="mainLink"><i class="icon-plus"></i>APTEKA CYFROWA - Twoje leki pod kontrolą</a></div>
	</header>
	
	<main>
	
		<div class ="interface">
		
			
			<div class = "greeting"><h3><?php echo $imie ?> witaj w Cyfrowe Aptece. Aby skorzystać z platformy wybierz jedną z poniższych opcji:</h3></div>
		
			<div class="option">
			
				<a href="history.php" class="button">Historia</a>
				
			</div>
			
			<div class="option">
			
				<a href="status.php" class="button">Staus recept</a>
			
			</div>
		
			<div class="option">
			
				<a href="logout.php" class="button">Wyloguj się</a>
				
			</div>
			
		</div>
	
	</main>
	
	<footer>
	
		<div class="rights">Wszelkie prawa zastrzeżone &copy; 2017, dziękujemy za wizytę</div>
	
	</footer>

	
</body>
</html>