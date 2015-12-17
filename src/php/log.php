<?
include("user.php");
/*if($_GET['type']){
	logs($_GET['type'], $_GET['text']);
}*/

function logs($type,$text){
	$text = join('',explode('../../',$text));
	$text = getPseudo()." ".$text;

	switch ($type) {
		case 'dl':
			$filename = '../../logs/download.log';
			break;
		case 'rm':
			$filename = '../../logs/remove.log';
			break;
		case 'mv':
			$filename = '../../logs/rename.log';
			break;
		case 'mkdir':
			$filename = '../../logs/folder.log';
			break;
		case 'ytdl':
			$filename = '../../logs/youtube.log';
			break;
		default:
			$filename = '../../logs/default.log';
			break;
	}

	$fp = fopen($filename, 'a+');
	fwrite($fp, date('[d/m/y h:i:s]'));
	fwrite($fp, " ".$text);
	fwrite($fp, "\r\n");

	fclose($fp);
}
?>