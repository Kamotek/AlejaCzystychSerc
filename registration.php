<?php
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_OK=true;
		
		//Sprawdź poprawność imienia i nazwiska
		$name = $_POST['name'];
		$surname = $_POST['surname'];

		$check = '/(*UTF8)^[A-ZŁŚ]{1}+[a-ząęółśżźćń]+$/';

		if(!preg_match($check, $name)) 
		{
			$wszystko_OK=false;
			$_SESSION['e_name'] = "Imię musi zaczynać się wielką literą";
		}
		
		if(!preg_match($check, $surname))
		{
			$wszystko_OK=false;
			$_SESSION['e_surname'] = "Nazwisko musi zaczynać się wielką literą";
		}
		
		// Sprawdź poprawność adresu email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="Wzór: adres@gmail.com";
		}
		
		//Sprawdź poprawność hasła
		$haslo1 = $_POST['password1'];
		$haslo2 = $_POST['password2'];
		
		if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if ($haslo1!=$haslo2)
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
		}	

		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
		
		//Czy zaakceptowano regulamin?
		if (!isset($_POST['regulamin']))
		{
			$wszystko_OK=false;
			$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
		}				
		
		//ReCaptcha sprawdza poprawność
		$sekret = "6Lc9bIAaAAAAAFEk6ZNenPgqBdfouluD8NiOTpn2";
		
		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
		
		$odpowiedz = json_decode($sprawdz);
		
		if ($odpowiedz->success==false)
		{
			$wszystko_OK=false;
			$_SESSION['e_bot']="Potwierdź, że nie jesteś robotem!";
		}		
		
		//Zapamiętaj wprowadzone dane
		$_SESSION['fr_name'] = $name;
		$_SESSION['fr_surname'] = $surname;
		$_SESSION['fr_email'] = $email;
		if (isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;
		
		$host = "mysql.ct8.pl";
		$db_user = "m20451_spacer";
		$db_password = "8GscvBRcTHa8YSq3Jn0w";
		$db_name = "m20451_spacer";
			
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
				//Czy email już istnieje?
				$rezultat = $polaczenie->query("SELECT id FROM users WHERE email='$email'");
				
				if (!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_maili = $rezultat->num_rows;
				if($ile_takich_maili>0)
				{
					$wszystko_OK=false;
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
				}		

				
				if ($wszystko_OK==true)
				{
					//Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
					
					if ($polaczenie->query("INSERT INTO users VALUES (NULL, '$name', '$surname', '$email', '', '$haslo_hash', CURRENT_TIMESTAMP)"))
					{
						$_SESSION['udanarejestracja']=true;
						header('Location: ?a=news.php');
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
					
				}
				
				$polaczenie->close();
			}
			
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
		
?> 