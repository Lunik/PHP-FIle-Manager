<?	
	/*include('app.php');
	$defaultPath = "../../";
	if (empty($_GET["file"])) {
    	header("HTTP/1.1 404 Not Found");
    	exit;
	}
	
	$file = $defaultPath.rawurldecode($_GET['file']);
	if(basename($file) != '.'){
		$filename = basename($file);
		$filesize = filesize($file);

		logs('dl', 'Download: "'.$file.'"');
		logs('', 'Download: "'.$file.'"');

		header("Cache-Control: no-cache, must-revalidate");
		header("Cache-Control: post-check=0,pre-check=0");
		header("Cache-Control: max-age=0");
		header("Pragma: no-cache");
		header("Expires: 0");
		 
		header("Content-Type: application/force-download");
		header('Content-Disposition: attachment; filename="'.$filename.'"');

		//Reprise sur DL
		header("Accept-Ranges: bytes");
		$start = 0;
		$end = $filesize - 1;
		if (isset($_SERVER["HTTP_RANGE"])) {
			if (!preg_match("#bytes=([0-9]+)?-([0-9]+)?(/[0-9]+)?#i", $_SERVER['HTTP_RANGE'], $m)) {
			    header("HTTP/1.1 416 Requested Range Not Satisfiable");
			    exit;
			}

			$start = !empty($m[1])?(int)$m[1]:null;
			$end = !empty($m[2])?(int)$m[2]:$end;
			if (!$start && !$end || $end !== null && $end >= $filesize
			    || $end && $start && $end < $start) {
			    header("HTTP/1.1 416 Requested Range Not Satisfiable");
			    exit;
			}

			if ($start === null) {
			    $start = $filesize - $end;
			    $end -= 1;
			}

			header("HTTP/1.1 206 Partial Content");
    		header("Content-Range: ".$start."-".$end."/".$size);
		}

		header("Content-Length: ".($end-$start+1));

		$f = fopen($file, "rb");
		fseek($f, $start);
		$remainingSize = $end-$start+1;
		$length = $remainingSize < 4096?$remainingSize:4096;
		while ($datas = fread($f, $length)) {
			if($datas){
			    echo $datas;
			    $remainingSize -= $length;
			    if ($remainingSize <= 0) {
			         break;
			    }
			    if ($remainingSize < $length) {
			        $length = $remainingSize;
			    }
			} else break;
		}
		fclose($f);
	}
	exit;*/
?>

<?	
	/*
	include('app.php');
	$defaultPath = "../../";
	if($_GET['file']){
		$file = $defaultPath.rawurldecode($_GET['file']);
		if(basename($file) != '.'){
			$filename = basename($file);
			logs('dl', 'Download: "'.$file.'"');
			logs('', 'Download: "'.$file.'"');
			header('Content-Type: ' . mime_content_type($file));
			header('Content-Length: '. filesize($file));
			header(sprintf('Content-Disposition: attachment; filename=%s',
				strpos('MSIE',$_SERVER['HTTP_REFERER']) ? rawurlencode($filename) : "\"$filename\"" ));
			ob_end_clean();
			ob_flush();
			readfile($file);
		}
	}
	exit;*/
?>

<?
	include('app.php');
	$defaultPath = "../../";
	if($_GET['file']){
		$xfile = rawurldecode($_GET['file']);
		$file = $defaultPath.$xfile;
		if(basename($file) != '.'){
			$filename = basename($file);
			logs('dl', 'Download: "'.$file.'"');
			logs('', 'Download: "'.$file.'"');
			header('Content-Type: ' . mime_content_type($file));
			header('Content-Length: '. filesize($file));
			header(sprintf('Content-Disposition: attachment; filename=%s',
				strpos('MSIE',$_SERVER['HTTP_REFERER']) ? rawurlencode($filename) : "\"$filename\"" ));
			header('X-SendFile: /var/www/downloads/'.$xfile);
		}
	}
	//exit;
?>