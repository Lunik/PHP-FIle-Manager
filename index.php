<?php
// must be in UTF-8 or `basename` doesn't work
setlocale(LC_ALL,'fr_FR.UTF-8');

$file = rawurldecode($_REQUEST['file']) ?: '.';

include('src/php/app.php');

if($_GET['do'] == 'list') {
	if (is_dir($file)) {
		$totalsize = foldersize($file);;
		$directory = $file;
		$result = array();
		$files = array_diff(scandir($directory), array('.','..'));
	    foreach($files as $entry) if($entry !== basename(__FILE__) && $entry !== "src" && $entry !== "logs" && $entry !== "pass.php")  {
    		$i = $directory . '/' . $entry;
	    	$stat = stat($i);
	    	if(is_dir($i))
	    		$stat['size'] = foldersize($i);
	        $result[] = array(
	        	'mtime' => $stat['mtime'],
	        	'size' => $stat['size'],
	        	'name' => basename($i),
	        	'path' => preg_replace('@^\./@', '', $i),
	        	'is_dir' => is_dir($i),
	        	'is_deleteable' => ((!is_dir($i) && is_writable($directory)) || 
	        					   (is_dir($i) && is_writable($directory) && is_recursively_deleteable($i))),
	        	'is_readable' => is_readable($i),
	        	'is_writable' => is_writable($i),
	        	'is_executable' => is_executable($i),
	        	'extension' => strtolower(pathinfo($i)['extension']),
	        );
	    }
	} else {
		err(412,"Not a Directory");
	}
	echo json_encode(array('success' => true, 'is_writable' => is_writable($file), 'results' =>$result, 'total_size' =>$totalsize));
	exit;
} 
/*} elseif ($_GET['do'] == 'zip') {
	$filename = basename($file);
	$zipname = $filename.'.zip';
	$shname = str_replace(' ','\ ', $filename);
	var_dump();
	if(!file_exists($zipname) && strpos($filename, '.zip') === false && !shell_exec('ps -ef | grep zip | grep -v grep')){
		shell_exec('nice -n 19 zip -r -b /tmp '.$shname.'.zip '.$shname.' &> /dev/null &');
	}*/

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>Downloads</title>
	<link rel="stylesheet" href="src/css/style.css">
	<link rel="stylesheet" href="src/css/icon.css">
	<link rel="icon" href="src/image/favicon.ico">
	<script src="../src/js/jquery.min.js"></script>
	<script src="../src/js/jquery-ui.min.js"></script>
	<script src="src/js/jqueryAddon.js"></script>
	<link href="src/rss/rss.xml" rel="alternate" type="application/rss+xml" title="Rss Torrent Flux" />
</head>
<body>	
	<div id="top">
		<div id="breadcrumb">&nbsp;</div>
	</div>
	<table id="table" align="center">
		<thead>
			<tr>
				<th>Name</th>
				<th>Size</th>
				<th>Modified</th>
				<!--<th>Permissions</th>-->
				<th>Actions</th>
			</tr>
		</thead>
		<tbody id="list">
		</tbody>
	</table>
	<a href="#" class="mkdir">+</a>
	<a href="#" class="ytdl">Ajouter</a>
	<a href="#" class="change-password">Change Password</a>
	<script src="src/js/app.js"></script>
	<script src="src/js/mediainfo.js"></script>
</body>
</html>
