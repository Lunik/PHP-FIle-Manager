<?
	include('src/php/user.php');

	//Verif si un formulaire est post et non vide
	function FORM_ispost($champs){
		foreach($champs as $c){
			if(empty($_POST[$c])) return false;
		}
		return true;
	}

	//recup les reponses d'un formulaire
	function FORM_get($champs){
		$result = array();
		foreach($champs as $c){
			if(clean($_POST[$c],""))
				$result[$c] = $_POST[$c];
			else{
				$result['erreur'] = true;
				$result['champs'] = $c;
			}
			if($c == 'password' && strlen($_POST[$c])<6){
				$result['password'] = NULL;
				$result['erreur'] = true;
				$result['champs'] = $c;
			}
		}
		return $result;
	}

	//Verif contient un string char spéciaux
	//true = clean
	//false = contient special char
	function clean($string,$addchar){
		$lenb = strlen($string);
		$string = str_replace(' ', '-', $string);
		$string = preg_replace('/[^A-Za-z0-9'.$addchar.']/', '', $string);
		$lena = strlen($string);
		if($lenb == $lena) return true;
		else return false;
	}

	$champs = ["username","password","password2"];

	if(FORM_ispost($champs)){
		$result = FORM_get($champs);
		if(empty($result['erreur'])){
			if($result['password'] === $result['password2']){
				changePasswd($_SERVER['REMOTE_USER'], $result['password']);
				echo "<script>window.close();</script>";
			} else {
				echo "Password non identiques.<br>";
			}
		} else {
			echo "Password incorrect.<br>";
			echo "- Longueur de plus de 6 charactères.<br>";
			echo "- Uniquement des chiffres et des lettres.<br><br>";
		}
	}
?>
<!DOCTYPE html>
<head>
	<title>Passwod Modification</title>
</head>
<body>
<form action="" method="post" accept-charset="utf-8">
	<label for="username">Username:</label> <span><? echo getPseudo(); ?></span>
	<input type="hidden" name="username" value="<? echo getPseudo(); ?>"/><br>
	<label for="password">Password:</label>
	<input type="password" name="password" placeholder="Password..." /><br>
	<label for="password2">Confirm Password:</label>
	<input type="password" name="password2" placeholder="Password..." /><br>
	<label></label>
	<input type="submit" name="valid" value="Submit" />
</form>
<style type="text/css">
	*{
		margin: 0px;
		padding: 0px;

		outline: none;
	}

	body{
		background-color: #DF7401;

		text-align: center;
	}

	form{
		padding: 10px;
		width: 280px;

		font-size: 120%;
		font-weight: bold;

		text-align: left;
	}
	form label{
		display: inline-block; 

		width: 140px; 

		padding: 10px 0px 10px 0px;

		text-align: left;
	}
	form input[name=password], form input[name=password2]{
		height: 15px;

		padding: 3px;

		background-color: rgba(255, 255, 255, 0.2);

		border: none;
		border-radius: 5px;

	}

	::-webkit-input-placeholder { /* WebKit, Blink, Edge */
    	color:    #111;
	}
	:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
	   color:    #111;
	   opacity:  1;
	}
	::-moz-placeholder { /* Mozilla Firefox 19+ */
	   color:    #111;
	   opacity:  1;
	}
	:-ms-input-placeholder { /* Internet Explorer 10-11 */
	   color:    #111;
	}
</style>
</body>