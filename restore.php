<?PHP
$tempDir = 'temp/';
$tempBackUp = $tempDir."site-data/";
$wpDir = 'wordpress/';
$wpInstallation = 'wordpress-4.5.3-pt_BR.zip';

$files = prepareAndSortFiles(scandir('.'));
//print_r($files['main']);

//Cria uma nova instalação do wp
deleteDir($wpDir);
extractZIP($wpInstallation, ".");

//Extrai o banco de dados e as configs
//for($i=0; $i<count($files['main']); $i++){
	//$file = $files['main'][$i];
	
//Copia os arquivos do thema
foreach($files['themes'] as $file){
	deleteDir($tempDir);
	extractZIP($file, $tempDir);
	
	copyDir($tempDir."wp-content-themes", $wpDir."wp-content/themes/");
}	

//Translations
foreach($files['others'] as $file){
	deleteDir($tempDir);
	extractZIP($file, $tempDir);
	
	copyDir($tempDir."wp-content-other/", $wpDir."wp-content/");
}	
	
//Plugins
foreach($files['plugins'] as $file){
	deleteDir($tempDir);
	extractZIP($file, $tempDir);
	
	copyDir($tempDir."wp-content-plugins/", $wpDir."wp-content/plugins/");
}	
	
//Uploads
foreach($files['uploads'] as $file){
	deleteDir($tempDir);
	extractZIP($file, $tempDir);
	
	copyDir($tempDir."wp-content-uploads/", $wpDir."wp-content/uploads/");
}	

	

foreach($files['main'] as $file){	
	deleteDir($tempDir);
	extractZIP($file, $tempDir);
	//Copia o arquivo de configuração
	rename( $tempBackUp."wp-config.txt" , $wpDir."wp-config.php" );
	@require($wpDir."wp-config.php");
}

//print_r($files);
/*
$zip = new ZipArchive;
if ($zip->open('test.zip') === TRUE) {
    $zip->extractTo('/my/destination/dir/');
    $zip->close();
    echo 'ok';
} else {
    echo 'failed';
}*/




function prepareAndSortFiles(Array $listOfFiles){	
	$sorteredFiles = array();
	$sorteredFiles['main'] = array();
	$sorteredFiles['others'] = array();
	$sorteredFiles['plugins'] = array();
	$sorteredFiles['themes'] = array();
	$sorteredFiles['uploads'] = array();
	
	for($i=0; $i<count($listOfFiles); $i++){
		$file = $listOfFiles[$i];
		if( $file == "." || $file == ".." ){
			continue;
		}
		if(strpos($file, 'main') !== false ){
			$sorteredFiles['main'][] = $file;
		}else if(strpos($file, 'others') !== false ){
			$sorteredFiles['others'][] = $file;
		}else if(strpos($file, 'plugins') !== false ){
			$sorteredFiles['plugins'][] = $file;
		}else if(strpos($file, 'themes') !== false ){
			$sorteredFiles['themes'][] = $file;
		}else if(strpos($file, 'uploads') !== false ){
			$sorteredFiles['uploads'][] = $file;
		}		
	}
	
	return $sorteredFiles;
}

function extractZIP($fileToExtract, $folder){
	$zip = new ZipArchive;
	if ($zip->open($fileToExtract) === TRUE) {
		$zip->extractTo($folder);
		$zip->close();
		echo 'Extract '.$fileToExtract.': <span style="color: green">success</span><BR>';
	} else {
		echo 'Extract '.$fileToExtract.': <span style="color: red">failed</span><BR>';
	}	
}
function deleteDir($dir) { 
	if(!is_dir($dir))
		return true;
		
	$files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
		(is_dir("$dir/$file")) ? deleteDir("$dir/$file") : unlink("$dir/$file"); 
    } 
    return rmdir($dir); 
}
function copyDir($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                copyDir($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 
?>