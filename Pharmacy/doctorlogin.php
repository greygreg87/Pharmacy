<?php

	session_start();

	if (isset($_POST['hasloLekarz']))
	{ 
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$prawo = $_POST['prawo'];
				$hasloLekarz = $_POST['hasloLekarz'];
			
				$prawo = htmlentities($prawo, ENT_QUOTES, "UTF-8");
			
				if ($rezultat = $polaczenie->query(
				sprintf("SELECT * FROM lekarze WHERE Prawo_wykonywania_zawodu='%s'",
				mysqli_real_escape_string($polaczenie,$prawo))))
				{
					$ilepraw = $rezultat->num_rows;
					if($ilepraw>0)
					{
						$wiersz = $rezultat->fetch_assoc();
						if ($hasloLekarz == $wiersz['Haslo'])
						{	
							$_SESSION['zalogowanyLekarz'] = true;
							$_SESSION['idLekarza'] = $wiersz['idLekarza'];
							$_SESSION['imieLekarza'] = $wiersz['Imie_Lekarza'];
							$_SESSION['nazwiskoLekarza'] = $wiersz['Nazwisko_Lekarza'];
							$_SESSION['adresPrzychodni'] = $wiersz['Adres_przychodni'];
							$_SESSION['prawoLekarza'] = $wiersz['Prawo_wykonywania_zawodu'];
							
							unset($_SESSION['bladLekarz']);
							$rezultat->free_result();
							header('Location: doctor.php');
						}
	
						else
						{
							$_SESSION['bladLekarz'] = "Podano nieprawidłowe hasło";
							header('Location: doctorlogin.php');
						}
						
					}
					else 
					{
						$_SESSION['bladLekarz'] = "Podano nieprawidłowy numer wykonywania zawodu";
						header('Location: doctorlogin.php');
					}
						
				}
				else
				{
					throw new Exception($polaczenie->error);
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
								
		<div class="doctorPanel"><h2>Logowanie do panelu lekarza</h2><p>Przykładowe dane: numer prawa - 5425740 hasło - qwerty</p>
					
			<form method="post">
	
				<input type="text" name="prawo" placeholder="Prawo wykonywania zawodu" onfocus="this.placeholder=''" onblur="this.placeholder='Prawo wykonywania zawodu'" />
							
				<input type="password" name="hasloLekarz" placeholder="Hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Hasło'" />
							
				<input type="submit" value="Zaloguj się" /></br>
								
					<span class="loginError"><?php
					if(isset($_SESSION['bladLekarz']))
					{
					echo $_SESSION['bladLekarz'];
					}
					?></span>
	
			</form>
					
		</div>
	
	</main>
	
	<footer>
	
		<div class="rights">Wszelkie prawa zastrzeżone &copy; 2017, dziękujemy za wizytę</div>
	
	</footer>

	
</body>
</html>