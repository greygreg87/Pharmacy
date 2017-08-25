<?php

	session_start();
	
	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: pacjent.php');
		exit();
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
	
		<div class ="container">
		
			<div class="content">
			
				<article>
					<div class="article"><h2>Antybiotyki</h2> <p>Antybiotyki to naturalne wtórne produkty metabolizmu mikroorganizmów, które działając wybiórczo w niskich stężeniach wpływają na struktury komórkowe lub procesy metaboliczne innych mikroorganizmów, hamując ich wzrost i podziały. Działanie antybiotyków polega na powodowaniu śmierci komórki bakteryjnej lub wpływaniu w taki sposób na jej metabolizm, aby ograniczyć jej możliwości rozmnażania się. Leczenie chorób zakaźnych polega na zabiciu mikroorganizmów wywołujących chorobę. Zbyt częste przyjmowanie antybiotyków ma negatywny wpływ na naszą naturalną florę bakteryjną. Historia przyjmowanych antybiotyków jest bardzo ważna. Przy przepisaniu przez lekarza antybiotyku należy poinformować go w wcześniej przyjmowanych lekach.</p>
					</div>
					<div class="article"><h2>Interkacje leków</h2> <p>Interakcją nazywa się wpływ jednego leku na działanie drugiego leku przyjmowanego równolegle, wpływ ten może być także wywołany przez spożywany pokarm. Nauka wyróżnia dwa rodzaje interakcji, farmakokinetyczne i farmakodynamiczne.</br>Przyjmowanie tych samych substancji leczniczych sprzedawanych pod różnymi nazwami może spowodować poważne konsekwencje zdrowotne. Należy zawsze sprawdzać jakie interakcje zachodzą pomiędzy substancjami zawartymi w przyjmowanych lekach. </br> Niektóre substancje lecznicze wchodzą w interakcje z pewnymi grupami artykułów spożywczych. Dlatego podczas leczenia duże znaczenia ma dieta pacjenta. </br> O interakcje można zapytać lekarza, lub farmaceutę. W sieci znajduje się także wiele portali wyszukujących niekorzystne połączenia i przedstawiających je w przystępnej dla pacjenta formie.</p>
					</div>
				</article>
			
			</div>
			
			<div class="user">
			
				<section>
					<div class="registration"><h2>Rejestracja w systemie</h2><p>Wykonujac rejestracje otrzymujesz zdalny, darmowy dostęp do wystawionych dla Ciebie recept. Znajomość historii leczenia, jak i statusu recept to Twoje prawo.</p></br><a href="registration.php" class="button">Zarejestruj się</a></div>
					
					<div class="login"><h2>Logowanie do systemu</h2>
					
						<form action="login.php" method="post">
	
							<input type="text" name="pesel" placeholder="PESEL" onfocus="this.placeholder=''" onblur="this.placeholder='PESEL'" />
							
							<input type="password" name="haslo" placeholder="Hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Hasło'" />
							
							<input type="submit" value="Zaloguj się" /></br>
								
								<span class="loginError"><?php
								if(isset($_SESSION['blad']))
								{
									echo $_SESSION['blad'];
									unset($_SESSION['blad']);
								}
								?></span>
	
						</form>
					
					</div>
				</section>
			
			</div>
		
		</div>
	
	</main>
	
	<footer>
	
		<div class="rights">Wszelkie prawa zastrzeżone &copy; 2017, dziękujemy za wizytę</div>
	
	</footer>

	
</body>
</html>