<?

function foldersize($path) {
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/'). '/';

    foreach($files as $t) {
        if ($t<>"." && $t<>"..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile);
                $total_size += $size;
            }
            else {
                $size = filesize($currentFile);
                $total_size += $size;
            }
        }   
    }

    return $total_size;
}

function rmrf($dir) {
	if(is_dir($dir)) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file)
			rmrf("$dir/$file");
		rmdir($dir);
	} else {
		unlink($dir);
	}
}
function is_recursively_deleteable($d) {
	$stack = array($d);
	while($dir = array_pop($stack)) {
		if(!is_readable($dir) || !is_writable($dir)) 
			return false;
		$files = array_diff(scandir($dir), array('.','..'));
		foreach($files as $file) if(is_dir($file)) {
			$stack[] = "$dir/$file";
		}
	}
	return true;
}

function err($code,$msg) {
	echo json_encode(array('error' => array('code'=>intval($code), 'msg' => $msg)));
	exit;
}

function asBytes($ini_v) {
	$ini_v = trim($ini_v);
	$s = array('g'=> 1<<30, 'm' => 1<<20, 'k' => 1<<10);
	return intval($ini_v) * ($s[strtolower(substr($ini_v,-1))] ?: 1);
}

?>