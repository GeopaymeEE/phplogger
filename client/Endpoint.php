<?php
//this file will be endpoint for server to download log file and signal to truncate the file after it's downloaded
//client should connect here by http

//key needed to download file
$KEY = '12345';
//servers that can download file
$VALID_SERVERS_IPS=array('127.0.0.1');
//

//1) check valid ip
$server_ip=$_SERVER['REMOTE_ADDR'];
if (!in_array($server_ip,$VALID_SERVERS_IPS)){
    die('This IP is not authorized');
}

//2) check if key is valid
if (!isset($_GET['key'])||$_GET['key']!=$KEY){
    die('Not authorized');
}

require dirname(__FILE__).'/Logger.php';
$logfile = Logger::FILENAME;

//http://stackoverflow.com/questions/6914912/streaming-a-large-file-using-php
define('CHUNK_SIZE', 1024*1024); // Size (in bytes) of tiles chunk

// Read a file and display its content chunk by chunk
function readfile_chunked($filename, $retbytes = TRUE) {
  $buffer = '';
  $cnt =0;
  $handle = fopen($filename, 'rb');
  if ($handle === false) {
    return false;
  }
  while (!feof($handle)) {
    $buffer = fread($handle, CHUNK_SIZE);
    echo $buffer;
    ob_flush();
    flush();
    if ($retbytes) {
      $cnt += strlen($buffer);
    }
   }
   $status = fclose($handle);
   if ($retbytes && $status) {
      return $cnt; // return num. bytes delivered like readfile() does.
   }
  return $status;
}

header('Content-Type: text/plain');
readfile_chunked($logfile);

copy($logfile,Logger::ARCHIVE_DIR.'/logger-'.date('Y-m-d').'_'.time());
unlink($logfile);

// ok so now the problem ... there will be a moment when files are written to a log file
// when we are serving the file, then we'll delete file which can lead to data loss
// fix someday ... for now it's not so crutial for me ... but for sure this is a bug
