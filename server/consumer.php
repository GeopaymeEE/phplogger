<?php
// this will consume endpoint data and put it into DB
if (empty($argv[2])) die('Usage : php '.__FILE__.' [endpoint] [key] '."\n");

require dirname(__FILE__).'/config.php';
//********************************************************************************

$endpoint = $argv[1];
$key=$argv[2];
$endpoint.='?'.http_build_query(array('key'=>$key));

$site=$endpoint;

function sanitize_file_name( $string ) {
    $parse = parse_url($string);
    return @$parse['host'];
}

$site=sanitize_file_name($site);

$ARCHIVE_DIR=ARCHIVE_DIR.'/'.$site.'/'.date('Y-m-d');  //we want files into db but also into
//create directory if not exists
if (!file_exists($ARCHIVE_DIR)) {
    mkdir($ARCHIVE_DIR, 0777, true);
}

//curl is needed
if (!function_exists('curl_version')) die('CURL not found ! ');

//get file with curl
$ch = curl_init($endpoint);
$archiveFile=$ARCHIVE_DIR.'/'.date('Y-m-d').time().'.log';
$fp = fopen($archiveFile, "w");
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);

//import rows into db
//find if it's correct file - if so it's first line should be the key


$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
$sql = <<<EOT
INSERT INTO log(timestamp,site,sitegroup,level,tag,message,ip,useragent,referer,current_url,timezone,landingpage,pagetype,var1)
VALUES(:timestamp,:site,:sitegroup,:level,:tag,:message,:ip,:useragent,:referer,:current_url,:timezone,:landingpage,:pagetype,:var1);
EOT;

$sth = $dbh->prepare($sql);

if (($handle = fopen($archiveFile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //map fields to names ...
        $timestamp = $data[0];
        $site = $data[1];
        $sitegroup = $data[2];
        $level = $data[3];
        $tag=$data[4];
        $message=$data[5];
        $ip=$data[6];
        $useragent=$data[7];
        $referer=$data[8];
        $current_url=$data[9];
        $timezone=$data[10];
        $landingpage=$data[11];
        $pagetype=$data[12];
        $var1=$data[13];

        //insert data into db line by line
        //probably better solution would be to use mysql function load inline but it has some security issues
        //so for now i will try line by line

        $sth->execute(array(
            'timestamp'=>$timestamp,
            'site'=>$site,
            'sitegroup'=>$sitegroup,
            'level'=>$level,
            'tag'=>$tag,
            'message'=>$message,
            'ip'=>$ip,
            'useragent'=>$useragent,
            'referer'=>$referer,
            'current_url'=>$current_url,
            'timezone'=>$timezone,
            'landingpage'=>$landingpage,
            'pagetype'=>$pagetype,
            'var1'=>$var1,
        ));

    }
    fclose($handle);
}


