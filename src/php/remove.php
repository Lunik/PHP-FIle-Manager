<?	
	include('app.php');
	$defaultPath = "../../";
	if($_GET['file']){
		$file = $defaultPath.rawurldecode($_GET['file']);

		if(basename($file) != '.'){
			logs('rm', 'Remove: "'.$file.'"');
			logs('', 'Remove: "'.$file.'"');
			rmrf($file);
		}
	}

	exit;
?>