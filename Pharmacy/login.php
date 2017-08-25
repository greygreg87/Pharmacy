<?php

	session_start();
	
	if ((!isset($_POST['pesel'])) || (!isset($_POST['haslo'])))
	{
		header('Location: index.php');
		exit();
	}

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
			$pesel = $_POST['pesel'];
			$haslo = $_POST['haslo'];
		
			$pesel = htmlentities($pesel, ENT_QUOTES, "UTF-8");
		
			if ($rezultat = $polaczenie->query(
			sprintf("SELECT * FROM pacjenci WHERE PESEL='%s'",
			mysqli_real_escape_string($polaczenie,$pesel))))
			{
				$ilePeselow = $rezultat->num_rows;
				if($ilePeselow>0)
				{
					$wiersz = $rezultat->fetch_assoc();
					if (password_verify($haslo, $wiersz['Haslo']))
					{	
						$_SESSION['zalogowany'] = true;
						$_SESSION['idPacjenta'] = $wiersz['idPacjenta'];
						$_SESSION['email'] = $wiersz['Email'];
						$_SESSION['imie'] = $wiersz['Imie'];
						$_SESSION['nazwisko'] = $wiersz['|Nazwisko'];
						$_SESSION['adres'] = $wiersz['Adres'];
						$_SESSION['pesel'] = $wiersz['Pesel'];
						
						unset($_SESSION['blad']);
						$rezultat->free_result();
						header('Location: patient.php');
					}
					
					else
					{
						$_SESSION['blad'] = "Podano nieprawidłowe hasło";
						header('Location: index.php');
					}
					
				}
				else 
				{
					$_SESSION['blad'] = "Podano nieprawidłowy PESEL";
					header('Location: index.php');
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
	
	
?>