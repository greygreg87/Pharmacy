<?php
	session_start();
	
	if (!isset($_SESSION['udanarejestracja']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		unset($_SESSION['udanarejestracja']);
	}
	
	if (isset($_SESSION['f_imie'])) unset($_SESSION['f_imie']);
	if (isset($_SESSION['f_nazwisko'])) unset($_SESSION['f_nazwisko']);
	if (isset($_SESSION['f_email'])) unset($_SESSION['f_email']);
	if (isset($_SESSION['f_pesel'])) unset($_SESSION['f_pesel']);
	if (isset($_SESSION['f_adres'])) unset($_SESSION['f_adres']);
	if (isset($_SESSION['f_haslo1'])) unset($_SESSION['f_haslo1']);
	if (isset($_SESSION['f_haslo2'])) unset($_SESSION['f_haslo2']);
	if (isset($_SESSION['f_regulamin'])) unset($_SESSION['f_regulamin']);
	
	if (isset($_SESSION['e_imie'])) unset($_SESSION['e_imie']);
	if (isset($_SESSION['e_nazwisko'])) unset($_SESSION['e_nazwisko']);
	if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
	if (isset($_SESSION['e_pesel'])) unset($_SESSION['e_pesel']);
	if (isset($_SESSION['e_adres'])) unset($_SESSION['e_adres']);
	if (isset($_SESSION['e_haslo1'])) unset($_SESSION['e_haslo1']);
	if (isset($_SESSION['e_haslo2'])) unset($_SESSION['e_haslo2']);
	if (isset($_SESSION['e_regulamin'])) unset($_SESSION['e_regulamin']);
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
	
		<div class ="welcome">
		
			<h2>Rejestracja przebiegła pomyslnie. witamy w seriwise. Kliknij, poniżej aby powrócić do strony głównej i się zalogować</h2>
		
			<a href="index.php" class="button">Przejście do strony głównej</a>
		
		</div>
	
	</main>
	
	<footer>
	
		<div class="rights">Wszelkie prawa zastrzeżone &copy; 2017, dziękujemy za wizytę</div>
	
	</footer>

	
</body>
</html>