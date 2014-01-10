<?php

class Logger {
    //CONFIGURATION
    //I didn't want to keep it in separate file, but maybe it's wrong decision ?
    const SITE     = 'mysite';
    const GROUP    = 'default';
    const TIMEZONE = 'GMT';
    const FILENAME = '/tmp/phplogger_ramdisk/mylogger.log';
    const ARCHIVE_DIR = '/tmp'; // when logfile is consumed by server it will be copied to archive directory

    //LOG LEVELS
    const LOG_NORMAL  = 'NORMAL';
    const LOG_DEBUG   = 'DEBUG' ;
    const LOG_ERROR   = 'ERROR' ;
    const LOG_WARNING = 'WARNING';

    public static function log($message,$level=self::LOG_NORMAL,$tag=''){
        $_log_data = array(
            'timestamp'=>self::_getTimestamp(),
            'site'=>self::SITE,
            'group'=>self::GROUP,
            'level'=>$level,
            'tag'=>$tag,
            'message'=>$message,
            'ip'=>self::_getRemoteIp(),
            'useragent'=>self::_getUserAgent(),
            'referer'=>self::_getReferer(),
            'current_url'=>self::_getCurrentUrl(),
            'timezone'=>self::TIMEZONE,
            'landingpage'=>self::_getLandingPage(),
            'pageType'=>self::_getPageType(),        // for A/B testing
            'var1'=>self::_getVar1(),
        );

        self::_save($_log_data);
    }

    private static function _getTimestamp()
    {
        $microtime = floatval(substr((string)microtime(), 1, 8));
        $rounded = round($microtime, 3);
        return date("Y-m-d H:i:s") . str_replace(',','.',substr((string)$rounded, 1, strlen($rounded)));
    }

    private static function _save($data){
//        self::_store(self::_parse_serialize($data));
//        self::_store(self::_parse_json($data));
          self::_store(self::_parse_csv($data));        //need to decide but csv will be most easy to read ...
    }

    private static function _parse_serialize($data){
        return serialize($data);
    }

    private static function _parse_json($data){
        return json_encode($data);
    }

    private static function _parse_csv($data){
        $ndata=[];                      // ok - for sure there's better way to to this ...
        foreach($data as $line){        // i just want to enclose all fields in " "
            $ndata[]='"'.$line.'"';
        };
        $line = implode(',',$ndata);
        $line.="\n";
        return $line;
    }

    private function _store($line){
        //make this as fast as can be .. ramdisk probably best options
        file_put_contents(self::FILENAME,$line,FILE_APPEND | LOCK_EX);
    }

    private static function _getRemoteIp(){
        return '';
    }

    private static function _getUserAgent(){
       return @$_SERVER['HTTP_USER_AGENT'];
    }

    private static function _getReferer(){
        return @$_SERVER['HTTP_REFERER'];
    }

    private static function _getLandingPage(){
        return $_SESSION['LOGGER_LANDINGPAGE'];
    }

    public static function setLandingPage($lp=null){
        if (empty($lp)){
            $_SESSION['LOGGER_LANDINGPAGE']=self::_getCurrentUrl();
        }else{
            $_SESSION['LOGGER_LANDINGPAGE']=$lp;
        }
    }

    private static function _getCurrentUrl(){
        return '';
    }

    private static function _getPageType(){
        return $_SESSION['LOGGER_PAGE_TYPE'];
    }

    public static function setPageType($page_type){
        $_SESSION['LOGGER_PAGE_TYPE']=$page_type;
    }

    private static function _getVar1(){
        return $_SESSION['LOGGER_VAR1'];
    }

    public static function setVar1($var1){
        $_SESSION['LOGGER_VAR1']=$var1;
    }


} 