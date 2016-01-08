<?php
//* make dirctory and files from xml


// $pattern1 = '/(http(s)?:\/\/www.jfr-card.co.jp\/)/';
date_default_timezone_set('Asia/Tokyo');
$today = getdate();

$pattern1 = '/(http(s)?:\/\/www.jfr-card.co.jp\/)|(\w+\.\S{2,4}$)/';
$pattern2 = '/(http(s)?:\/\/www.jfr-card.co.jp\/*.*\/[1]?)/';
$root = dirname(__FILE__)."/www_".$today["year"].$today["mon"].$today["mday"]."/";

// 初期htmlファイルデータ読込
ob_start();

$cont = file_get_contents('./index.html');
echo $cont;
/* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
$page = ob_get_contents();

ob_end_clean();

// CSV読込
$csv = array_map('str_getcsv',file('./urls.csv'));


function MakeDir($path)
{
	// echo "MakeDir   ".$path.PHP_EOL;
	if(!file_exists($path)) {
		mkdir($path,0777,true);
	}
}

function MakeFile($file_name)
{
	global $page;

	// echo "MakeFile   ".$file_name.PHP_EOL;
	if(!file_exists($file_name)) {
		touch($file_name);
	}
	if(preg_match('/html/', $file_name)) {
		$cwd = getcwd();
		$file = $file_name;
		@chmod($file,0755);
		$fw = fopen($file, "w");
		fputs($fw,$page, strlen($page));
		fclose($fw);
	}

	$default_set = array('_img','_css','_js');
	if (preg_match('/index.html/', $file_name)) {
		foreach ($default_set as $value) {
			$path = preg_replace('/index.html/', $value, $file_name);
			if (!file_exists($path)) {
					// echo "MakeDir DF  ".$path.PHP_EOL;
					mkdir($path,0777,true);
					touch($path."/.gitkeep");
			}
		}
	}
}

function ReplaceWord($file_name, $key, $value, $level=0) {
	if (!file_exists($file_name)) {
		return;
	}

	// 初期htmlファイルデータ読込
	ob_start();

	$cont = file_get_contents($file_name);
	echo $cont;
	/* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
	$page = ob_get_contents();

	ob_end_clean();

	if ($level != 0) {
		for ($i=0; $i < $level; $i++) { 
			$value = '../' . $value;
		}
	}
	else if(preg_match('/path/', $key)) {
		$value = './' . $value;
	}

	$reg = '/'.$key.'/';
	// if(count(preg_match($reg, $page)[0])) {
	// 	echo "hit!!!!".count(preg_match($reg, $page)).PHP_EOL;
	// }
	$page = preg_replace($reg, $value, $page);

	$cwd = getcwd();
	$file = $file_name;
	@chmod($file,0755);
	$fw = fopen($file, "w");
	fputs($fw,$page, strlen($page));
	fclose($fw);
}

foreach ($csv as $url) {
	print_r($url);

	if ($url[0] == "") {
		continue;
	}

	// URIの除去、パスの抽出
	$dirs = preg_replace($pattern1, "", $url[0]);
	$path = $root . $dirs;
	// 階層
	echo PHP_EOL.$dirs .PHP_EOL;
	preg_match_all('/\//', $dirs, $matches);
	$level = count($matches[0]);
	// ファイル名
	$file_name = preg_replace($pattern2, "", $url[0]);

	MakeDir($path);

	if($file_name != "") {
		MakeFile($path.$file_name);
	} else {
		MakeFile($path.'index.html');

		ReplaceWord($path.'index.html', "{#common_path}", "_common/", $level);
		ReplaceWord($path.'index.html', "{#title}", $url[2]);
		ReplaceWord($path.'index.html', "{#description}", $url[3]);
	}
}

$common_set = array(
	'_common',
	'_common/__dev_only__',
	'_common/__sass__',
	'_common/_css',
	'_common/_img',
	'_common/_js',
	);

foreach ($common_set as $value) {
	MakeDir($root.$value);
}
