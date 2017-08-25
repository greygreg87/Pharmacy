<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
	
	$imie=$_SESSION['imie'];
	$id=$_SESSION['idPacjenta'];
	 
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
		
	try
	{
		$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
		mysqli_query($polaczenie, "SET CHARSET utf8");
		mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

		if ($polaczenie->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			$rezultat = $polaczenie->query("SELECT r.Data_realizacji, r.Nazwa_leku, r.Dawkowanie, l.Imie_lekarza, l.Nazwisko_lekarza, f.Imie_farmaceuty, f.Nazwisko_farmaceuty, f.Nazwa_apteki, f.Adres_apteki FROM recepty AS r, lekarze AS l, farmaceuci AS f WHERE r.Status = 'Zrealizowane' AND r.idLekarza = l.idLekarza AND r.idFarmaceuty = f.idFarmaceuty AND r.idPacjenta = '$id'");
			if (!$rezultat) throw new Exception($polaczenie->error);
			$ile = mysqli_num_rows($rezultat);
			$polaczenie->close();
		}
	
	}
	
	catch (Exception $e)
	{
		echo 'Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!';
		echo '<br />Informacja deweloperska: '.$e;
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
		<div class="logo"><a href="patient.php" class="mainLink"><i class="icon-plus"></i>APTEKA CYFROWA - Twoje leki pod kontrolą</a></div>
	</header>
	
	<main>
			
			<div class="table">
				
				<?php if ($ile>=1)
				{
					echo<<<END
					<table class = "resultTable">
					<h3> $imie oto Twoja historia leczenia:</h3>
					<tr>
	
					<td class = "column">Data</td>
					<td class = "column">Nazwa leku</td>
					<td class = "column">Dawkowanie</td>
					<td class = "column">Imię lekarza</td>
					<td class = "column">Nazwisko lekarza</td>
					<td class = "column">Imię farmaceuty</td>
					<td class = "column">Nazwisko farmaceuty</td>
					<td class = "column">Adres apteki</td>
					</tr><tr>
END;
				
					for ($i = 1; $i <= $ile; $i++) 
					{
						$row = mysqli_fetch_assoc($rezultat);
						$a1 = $row['Data_realizacji'];
						$a2 = $row['Nazwa_leku'];
						$a3 = $row['Dawkowanie'];
						$a4 = $row['Imie_lekarza'];
						$a5 = $row['Nazwisko_lekarza'];
						$a6 = $row['Imie_farmaceuty'];
						$a7 = $row['Nazwisko_farmaceuty'];
						$a8 = $row['Adres_apteki'];
					
						echo<<<END
						<td class = "row">$a1</td>
						<td class = "row">$a2</td>
						<td class = "row">$a3</td>
						<td class = "row">$a4</td>
						<td class = "row">$a5</td>
						<td class = "row">$a6</td>
						<td class = "row">$a7</td>
						<td class = "row">$a8</td>
						</tr><tr> 
END;
					} echo '</tr></table>';
				}
				if ($ile==0) 
				{
						?><p><?php echo "<p> $imie brak leków w Twojej historii leczenia";
				} ?>
			</div>
			
			<div class="return">
			
				<a href="patient.php" class="button">Powrót</a>
				
			</div>
	
	</main>
	
	<footer>
	
		<div class="rights">Wszelkie prawa zastrzeżone &copy; 2017, dziękujemy za wizytę</div>
	
	</footer>

	
</body>
</html>