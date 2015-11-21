<?	
	include('app.php');
	$defaultPath = "../../";
	$file = rawurldecode($_GET['file']) ?: '.';
	$path = $defaultPath.rawurldecode($_GET['path']);

	logs('mkdir', 'Creation: "'.$path.$file.'"');
	logs('', 'Creation: "'.$path.$file.'"');

	if(isset($file))
		mkdir($path.$file);

	exit;
?>