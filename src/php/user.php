<?
function getPseudo(){
	$pseudo = $_SERVER['REMOTE_USER'];

	switch ($_SERVER['REMOTE_USER']) {
		case 'lunik':
			$pseudo = "Lunik";
			break;
		case 'PLSQL_POWA':
			$pseudo = "Sol3mbum";
			break;
		case 'tomjou':
			$pseudo = "Tomjou";
			break;
		case 'chips':
			$pseudo = "Chips";
			break;
		case 'punkyman':
			$pseudo = "Punkyman";
			break;
		case 'link':
			$pseudo = "FalseLink";
			break;
		default:
			$pseudo = "Anonymous";
			break;
	}

	return $pseudo;
}

function changePasswd($username, $new_password){
	$new_password = crypt($new_password, base64_encode($new_password));

    //read the file into an array
	$lines = explode("\n", file_get_contents('/var/www/rutorrent/.htpasswd'));

	//read the array and change the data if found
    $new_file = "";
    foreach($lines as $line)
    {
        $line = preg_replace('/\s+/','',$line); // remove spaces
        if ($line) {
            list($user, $pass) = split(":", $line, 2);
            if ($user == $username) {
                $new_file .= $user.':'.$new_password."\n";
            } else {
                $new_file .= $user.':'.$pass."\n";
            }
        }
    }

    //save the information
    $f=fopen("/var/www/rutorrent/.htpasswd","w") or die("couldn't open the file");
    fwrite($f,$new_file);
    fclose($f);
}
?>