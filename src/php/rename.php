<?	
	include('app.php');
	$defaultPath = "../../";
	$file = $defaultPath.rawurldecode($_GET['file']) ?: '.';

	$filename = basename($file);
	$newname = $_GET['newname'];
	$path = $defaultPath.$_GET['path'];

	logs('mv', 'Rename: "'.$file.'" in "'.$path.$newname.'"');
	logs('', 'Rename: "'.$file.'" in "'.$path.$newname.'"');

	rename($file, $path.$newname);
	exit;
?>