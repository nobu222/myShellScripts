<?php
//* リンク張替えスクリプト

$url = "http://www.jfr-card.co.jp";
$dir_root = "/Users/CRD129/_jfrcard_renew_作業用/www_jfrcard/dev";
$csv_file = "/Volumes/projects/JFRカード/000_JFRカード_リニューアル/make_dir_file/20151230.csv";
$ls = array();
$table = makeTable($csv_file);
// print_r($table);
$pwd = "";

function getFileList($dir) 
{
  $files = glob(rtrim($dir, '/').'/*html');
  $list = array();

  foreach ($files as $file) {
    if (is_file($file)) {
      $list[] = $file;
    }
  }

  $dirs = glob(rtrim($dir, '/').'/*', GLOB_ONLYDIR);

  foreach ($dirs as $d) {
    $list = array_merge($list, getFileList($d));
  }

  return $list;
}

function makeTable($file)
{
  $table = array();
  $csv = array_map('str_getcsv',file($file));

  foreach ($csv as $record) {
    if (!empty($record[0]) && !empty($record[1])) {
      $table[$record[0]] = $record[1];
    }
  }

  return $table;
}

function replaceLink($matches) {
  global $url,$dir_root,$csv_file,$table,$pwd;
  $path = "";

  if (isset($table[$matches[2]])) {
    $link = str_replace( $url, $dir_root, str_replace('https://', 'http://', $table[$matches[2]]) );

    $link_ar = explode("/", trim($link, "/") );
    $diff = array_diff($link_ar, $pwd);
    $diff2 = array_diff($pwd, $link_ar);
    
    $level = count($diff2)-1;
    for ($i=0; $i < $level; $i++) { 
      $path = "../".$path;
    }
    $path = $path.implode('/', $diff);
    if ($path == "") {
      $path = ".";
    }
    if (!preg_match("/.*\/$/", $path)) {
      $path = $path . "/";
    }
  }
  else {
    $path = $matches[2];
  }
  echo $matches[1].$path.$matches[3].PHP_EOL; //変更後のパス

  return $matches[1].$path.$matches[3];
}

$ls = getFileList($dir_root);
// var_dump($ls);

foreach ($ls as $html) {
  echo $html.PHP_EOL; //変更したファイル名
  $pwd = explode("/", trim($html, "/") );
  $cont = file_get_contents($html);

  ob_start();

  echo $cont;
  /* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
  $page = ob_get_contents();

  ob_end_clean();

  $ret = preg_replace_callback("/(href=[\"|\'])(\s*[0-9]+\s*-\s*[0-9]+\s*)([\"|\'])/", "replaceLink", $page);

  // $fp = fopen($html, 'w');
  // fwrite($fp, $ret);
  // fclose($fp);
}
