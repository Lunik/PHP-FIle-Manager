<?	
	include('app.php');
	$url = rawurldecode($_GET['url']);

	logs('ytdl', 'DlYoutube: "'.$url.'"');
	logs('', 'DlYoutube: "'.$url.'"');

	echo shell_exec("/usr/local/sbin/ytdl \"".$url."\" > /dev/null");

	exit;
?>