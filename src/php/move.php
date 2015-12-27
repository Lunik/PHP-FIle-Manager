<?
	include('app.php');
	$defaultPath = "../../";
	$file = $defaultPath.rawurldecode($_GET['file']) ?: '.';

	$filename = basename($file);
	$name = $_REQUEST['name'];
	$path = $defaultPath.$_REQUEST['path'];

	logs('mv', 'Move: "'.$file.'" in "'.$path.$name.'"');
	logs('', 'Move: "'.$file.'" in "'.$path.$name.'"');

	rename($file, $path.$name);
	exit;
?>