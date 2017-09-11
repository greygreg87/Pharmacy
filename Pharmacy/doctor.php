<?php

	session_start();
	
	if (!isset($_SESSION['zalogowanyLekarz']))
	{
		header('Location: index.php');
		exit();
	}
	
	if (isset($_POST['data']))
	{ 
		$wszystkoOK=true;
		$data = htmlentities($_POST['data']);
		$pesel = htmlentities($_POST['pesel']);
		$lek = htmlentities($_POST['lek']);
		$dawkowanie = htmlentities($_POST['dawkowanie']);
		$idLekarza = $_SESSION['idLekarza'];
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			
			if ($polaczenie->connect_errno!=0 && $wszystkoOK == true)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$rezultat = $polaczenie->query("SELECT * FROM pacjenci WHERE PESEL='$pesel'");
				
				if (!$rezultat) throw new Exception($polaczenie->error);
				$wiersz = $rezultat->fetch_assoc();
				$idPacjenta = $wiersz['idPacjenta'];
				
				if ($idPacjenta == 0)
				{
					$wszystkoOK = false;
					$_SESSION['e_recepta'] = "Wprowadzono błędny numer Pesel";
				}
				
				if ($wszystkoOK==true)
				{
					if ($polaczenie->query("INSERT INTO recepty VALUES (NULL, '$data', '$idLekarza', '$idPacjenta', '0', 'Niezrealizowano', NULL, '$lek', '$dawkowanie')"))
					{
						$_SESSION['udanarejestracja']=true;
						?><script src="code.js"></script><?php
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
				}
				
				$polaczenie->close();
			}
			
		}
		catch (Exception $e)
		{
			echo "Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!";
			echo "Informacja deweloperska: ".$e;
		}
	}
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
	
		<div class ="doctorPanel">
		
			
			<div class = "doctorGreeting"><h3>Witaj w panelu lekarza. Możesz wystawić receptę dla pacjenta lub powrócić do strony głównej</h3></div>
		
			<div class="doctorForm">
			
					<form method="post">
						<input type="date" placeholder="Data wystawienia" onfocus="this.placeholder=''" onblur="this.placeholder='Data wystawienia'" name="data" />
						<input type="text" placeholder="Pesel pacjenta" onfocus="this.placeholder=''" onblur="this.placeholder='Pesel pacjenta'" name="pesel" />
						<input type="text" placeholder="Nazwa leku" onfocus="this.placeholder=''" onblur="this.placeholder='Nazwa leku'" name="lek" />
						<input type="text" placeholder="Dawkowanie" onfocus="this.placeholder=''" onblur="this.placeholder='Dawkowanie'" name="dawkowanie" /><br />
						<input type="submit" value="Wystaw receptę" /></br>
						<span class="loginError"><?php
						if(isset($_SESSION['e_recepta']))
						{
						echo $_SESSION['e_recepta'];
						}
						?></span>
					</form>
			</div>
		
			
			
		</div>
		
		<div class="doctorLogout">
			
			<a href="logout.php" class="button">Wyloguj się</a>
				
		</div>
	
	</main>
	
	<footer>
	
		<div class="rights">Wszelkie prawa zastrzeżone &copy; 2017, dziękujemy za wizytę</div>
	
	</footer>

	
</body>
</html>