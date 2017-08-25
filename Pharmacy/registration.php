<?php

	session_start();

	if (isset($_POST['email']))
	{ 
		$wszystkoOK=true;
		
		// Walidacja imienia
		$imie = htmlentities($_POST['imie'], ENT_QUOTES, "UTF-8");
		
		if ((strlen($imie)!=0))
		{
		
			if (preg_match('/^[A-Za-z]+$/iD',$imie))
			{
				if ((strlen($imie)==1))
				{
					$wszystkoOK = false;
					$_SESSION['e_imie']="Wprowadzone imię jest za krótkie";
				}
			}
			else
			{
				$wszystkoOK = false;
				$_SESSION['e_imie']="Podano niedozwolone znaki";
			}
		}
		else
		{
			$wszystkoOK = false;
			$_SESSION['e_imie']="Wprowadź imię - pole wymagane";
		}
		
		
		// Walidacja nazwiska
		$nazwisko = htmlentities($_POST['nazwisko'], ENT_QUOTES, "UTF-8");
		
		if ((strlen($nazwisko)!=0))
		{
			
			if (preg_match('/^[A-Za-z]+$/iD',$nazwisko))
			{
				if ((strlen($nazwisko)==1))
				{
					$wszystkoOK = false;
					$_SESSION['e_nazwisko']="Wprowadzone nazwisko jest za krótkie";
				}

			}
			else
			{
				$wszystkoOK = false;
				$_SESSION['e_nazwisko']="Podano niedozwolone znaki";
			}
		}
		else
		{
			$wszystkoOK = false;
			$_SESSION['e_nazwisko']="Wprowadź nazwisko - pole wymagane";
		}
		
		// Walidacja e-mail'a
		$email = htmlentities($_POST['email'], ENT_QUOTES, "UTF-8");
		$emailSanityzacja = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailSanityzacja, FILTER_VALIDATE_EMAIL) == false) || ($emailSanityzacja!=$email))
		{
			$wszystkoOK = false;
			$_SESSION['e_email'] = "Podaj poprawny e-mail";
		}
		
		// Walidacja PESEL
		$pesel = htmlentities($_POST['pesel'], ENT_QUOTES, "UTF-8");
		$sameCyfry = true;
		
		for ($z=0; $z<strlen($pesel); $z++)
		{
			if (!is_numeric($pesel[$z])) $sameCyfry = false;
		}
			
		if ($sameCyfry)
		{
			if (strlen($pesel)==11)
			{
				$szyfr = array (1,3,7,9,1,3,7,9,1,3,1);
				$suma = 0;
				for ($k=0; $k<strlen($pesel); $k++)
				{
					$suma = $suma + $pesel[$k]*$szyfr[$k];
				}
				if ($suma%10!=0) 
				{
					$wszystkoOK = false;
					$_SESSION['e_pesel']="Taki Pesel nie istnieje";
				}
			
			}
			else
			{
				$wszystkoOK = false;
				$_SESSION['e_pesel']="Podano nieprawidłowy numer Pesel";
			}
		}
		else
		{
			$wszystkoOK = false;
			$_SESSION['e_pesel']="Podano niedozwolone znaki";
		}
		
		// Walidacja adresu
		
		$adres = htmlentities($_POST['adres'], ENT_QUOTES, "UTF-8");
		
		if ((strlen($adres)==0))
		{
			$wszystkoOK = false;
			$_SESSION['e_adres']="Wprowadź adres - pole wymagane";
		}
		
		// Walidacja hasła
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		
		if ((strlen($haslo1)<6) || (strlen($haslo1)>20))
		{
			$wszystkoOK = false;
			$_SESSION['e_haslo1'] = "Hasło musi zawierać od 8 do 20 znaków";
		}
		
		if ($haslo1 != $haslo2)
		{
			$wszystkoOK = false;
			$_SESSION['e_haslo2'] = "Podane hasła nie są identyczne";
		}	
		
		$hasloHash = password_hash($haslo1, PASSWORD_DEFAULT);
		
		// Walidacja regulaminu
		
		if (!isset($_POST['regulamin']))
		{
			$wszystkoOK = false;
			$_SESSION['e_regulamin'] = "Potwierdź akceptację regulaminu";
		}
		
		$_SESSION['f_imie'] = $imie;
		$_SESSION['f_nazwisko'] = $nazwisko;
		$_SESSION['f_email'] = $email;
		$_SESSION['f_pesel'] = $pesel;
		$_SESSION['f_adres'] = $adres;
		$_SESSION['f_haslo1'] = $haslo1;
		$_SESSION['f_haslo2'] = $haslo2;
		if (isset($_POST['regulamin'])) $_SESSION['f_regulamin'] = true;
		
		// Walidacja z bazą danych
		
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
				$rezultat = $polaczenie->query("SELECT idPacjenta FROM pacjenci WHERE Email='$email'");
				if (!$rezultat) throw new Exception($polaczenie->error);
				
				$ileTakichMaili = $rezultat->num_rows;
				if ($ileTakichMaili>0)
				{
					$wszystkoOK = false;
					$_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu";
				}
				
				$rezultat = $polaczenie->query("SELECT idPacjenta FROM pacjenci WHERE PESEL='$pesel'");
				if (!$rezultat) throw new Exception($polaczenie->error);
				
				$ileTakichPeseli = $rezultat->num_rows;
				if ($ileTakichPeseli>0)
				{
					$wszystkoOK = false;
					$_SESSION['e_pesel'] = "Podany numer PESEL jest już zarejestrowany";
				}
				
				if ($wszystkoOK==true)
				{
					if ($polaczenie->query("INSERT INTO pacjenci VALUES (NULL, '$imie', '$nazwisko', '$pesel', '$email', '$adres', '$hasloHash')"))
					{
						$_SESSION['udanarejestracja']=true;
						header('Location: welcome.php');
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
	
		<div class ="registrationWindow">
		
			<header>
			
				<div class="registrationHeader"><h2>Formularz rejestracyjny</h2><p>Aby zarejestrować się w systemie wypełnij poniższy formularz. Obok pól formularza podano wskazówki</p></div>
				
			</header>
			
			<section>
			
				<div class ="description"><h3>Podaj imię:</h3><h3>Podaj nazwisko:</h3><h3>Podaj adres e-mail:</h3><h3>Podaj numer PESEL:</h3><h3>Podaj swój adres:</h3><h3>Wpisz swoje hasło:</h3><h3>Powtórz hasło:</h3><h3>Akceptuję regulamin</h3>
				</div>
				
				<div class="form">
			
					<form method="post">
						<input type="text" value="<?php
						if (isset($_SESSION['f_imie']))
						{
							echo $_SESSION['f_imie'];
							unset($_SESSION['f_imie']);
						}
						?>" name="imie" /><br />
						<input type="text" value="<?php
						if (isset($_SESSION['f_nazwisko']))
						{
							echo $_SESSION['f_nazwisko'];
							unset($_SESSION['f_nazwisko']);
						}
						?>" name="nazwisko" /><br />
						<input type="text" value="<?php
						if (isset($_SESSION['f_email']))
						{
							echo $_SESSION['f_email'];
							unset($_SESSION['f_email']);
						}
						?>" name="email" /><br />
						<input type="text" value="<?php
						if (isset($_SESSION['f_pesel']))
						{
							echo $_SESSION['f_pesel'];
							unset($_SESSION['f_pesel']);
						}
						?>" name="pesel" /><br />
						<input type="text" value="<?php
						if (isset($_SESSION['f_adres']))
						{
							echo $_SESSION['f_adres'];
							unset($_SESSION['f_adres']);
						}
						?>" name="adres" /><br />
						<input type="password" value="<?php
						if (isset($_SESSION['f_haslo1']))
						{
							echo $_SESSION['f_haslo1'];
							unset($_SESSION['f_haslo1']);
						}
						?>" name="haslo1" /><br />
						<input type="password" value="<?php
						if (isset($_SESSION['f_haslo2']))
						{
							echo $_SESSION['f_haslo2'];
							unset($_SESSION['f_haslo2']);
						}
						?>" name="haslo2" /><br />
						<label>
							<input type="checkbox" name="regulamin"<?php
						if (isset($_SESSION['f_regulamin']))
						{
							echo "checked";
							unset($_SESSION['f_regulamin']);
						}
						?>/>
						</label></br>
						<input type="submit" value="Zarejestruj się" />
					</form>
				</div>
				
				<div class="registrationError">
					
					<div class="note">
						<p><?php
							if (isset($_SESSION['e_imie']))
							{
								echo $_SESSION['e_imie'];
								unset($_SESSION['e_imie']);
							}
						?></p>
					</div>
					
					<div class="note">
						<p><?php
							if (isset($_SESSION['e_nazwisko']))
							{
								echo $_SESSION['e_nazwisko'];
								unset($_SESSION['e_nazwisko']);
							}
						?></p>
					</div>
					
					<div class="note">
						<p><?php
							if (isset($_SESSION['e_email']))
							{
								echo $_SESSION['e_email'];
								unset($_SESSION['e_email']);
							}
						?></p>
					</div>
					
					<div class="note">
						<p><?php
							if (isset($_SESSION['e_pesel']))
							{
								echo $_SESSION['e_pesel'];
								unset($_SESSION['e_pesel']);
							}
						?></p>	
					</div>
					
					<div class="note">
						<p><?php
							if (isset($_SESSION['e_adres']))
							{
								echo $_SESSION['e_adres'];
								unset($_SESSION['e_adres']);
							}
						?></p>
					</div>
					
					<div class="note">
						<p><?php
							if (isset($_SESSION['e_haslo1']))
							{
								echo $_SESSION['e_haslo1'];
								unset($_SESSION['e_haslo1']);
							}
						?></p>
					</div>
					
					<div class="note">
						<p><?php
							if (isset($_SESSION['e_haslo2']))
							{
								echo $_SESSION['e_haslo2'];
								unset($_SESSION['e_haslo2']);
							}
						?></p>
					</div>
					
					<div class="note">
						<p><?php
							if (isset($_SESSION['e_regulamin']))
							{
								echo $_SESSION['e_regulamin'];
								unset($_SESSION['e_regulamin']);
							}
						?></p>
					</div>
					
				</div>
				
				</div>
			</section>
		</div>
	
	</main>
	
	<footer>
	
		<div class="rights">Wszelkie prawa zastrzeżone &copy; 2017, dziękujemy za wizytę</div>
	
	</footer>
	
	<?php
	if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
	?>
	
</body>
</html>