<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

function force_download($filepath,$filename=''){
    if(file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        if($filename!='')
            header('Content-Disposition: attachment; filename="'.$filename.'"');
        else
            header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer//*/
        readfile($filepath);
        exit;
    }
}

class GNSSController extends Controller
{
    public function index(){
    	return view('gnss.index');
    }

    public function get_rnx(){
    	$fn=storage_path().'/GNSS';
    	if(isset($_GET['download'])){
    		$dir=$fn.'/OUT/GETOK.CRD';
    		if(file_get_contents($dir)!='')
    			force_download($dir,'OUTPUT.CRD');
    		file_put_contents($dir,'');
    	}elseif(isset($_GET['newcalc'])){
    		$fn.='/RNX';
		    if(file_exists($fn)){
		        $sd=scandir($fn);
		        if(isset($sd[2])){
		        	$bu=dirname($fn).'/BACKUP';
		        	if(!file_exists($bu))mkdir($bu,0755);
		            rename($fn,$bu.'/RNX-'.date('YmdHis'));
		            mkdir($fn,0755);
		        }
		    }
		    return 'ok';
    	}else{
    		$fn.='/RNX';
			if(file_exists($fn)){
				$fn=scandir($fn);unset($fn[0]);unset($fn[1]);
				$fn=array_values($fn);
			}else $fn=array();
			return json_encode($fn);
		}
    }

    public function upload(){
    	$dir=storage_path().'/GNSS/';
    	if(!file_exists($dir))mkdir($dir,0755);
    	if(isset($_POST['mode'])){
    		$fn=$dir.'GNSS_KF.INP';
    		$inp ='USER    : '.$_POST['user'].chr(10);
    		$inp.='MODE    : '.$_POST['mode'].chr(10);
    		$inp.='DATA    : '.$_POST['data'].chr(10);
    		$inp.='STA REF : '.$_POST['stasiun'].chr(10);
    		$x=sprintf('%14.4f',(float)$_POST['x']);
    		$y=sprintf('%14.4f',(float)$_POST['y']);
    		$z=sprintf('%14.4f',(float)$_POST['z']);
    		$inp.='STA POS :'.$x.''.$y.''.$z.chr(10);
    		$inp.='ELEV    : '.$_POST['elev'].chr(10);
    		file_put_contents($fn, $inp);
    		shell_exec('cd "'.$dir.'" & GNSS_KF_DD.exe');
    		return 'ok';
	   	}else{
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			@set_time_limit(5 * 3600);
			$targetDir = $dir.'RNX';
			$cleanupTargetDir = true;
			$maxFileAge = 5 * 3600;
			if (!file_exists($targetDir))
				@mkdir($targetDir);
			if (isset($_REQUEST["name"])) {
				$fileName = $_REQUEST["name"];
			} elseif (!empty($_FILES)) {
				$fileName = $_FILES["file"]["name"];
			} else {
				$fileName = uniqid("file_");
			}

			$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
			// Chunking might be enabled
			$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
			$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

			// Remove old temp files	
			if ($cleanupTargetDir) {
				if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
				}

				while (($file = readdir($dir)) !== false) {
					$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

					// If temp file is current file proceed to the next
					if ($tmpfilePath == "{$filePath}.part") {
						continue;
					}

					// Remove temp file if it is older than the max age and is not the current file
					if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
						@unlink($tmpfilePath);
					}
				}
				closedir($dir);
			}	


			// Open temp file
			if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			}

			if (!empty($_FILES)) {
				if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
				}

				// Read binary input stream and append it to temp file
				if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				}
			} else {	
				if (!$in = @fopen("php://input", "rb")) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				}
			}

			while ($buff = fread($in, 4096)) {
				fwrite($out, $buff);
			}

			@fclose($out);
			@fclose($in);

			// Check if file has been uploaded
			if (!$chunks || $chunk == $chunks - 1) {
				// Strip the temp .part suffix off 
			    rename("{$filePath}.part", $filePath);
			}

			// Return Success JSON-RPC response
			die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');

			return 'ok';
	    }
    }
}
