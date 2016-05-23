<?php
/**
 * User: Peak
 * Date: 2016/4/25
 * Time: 9:45
 */
//phpinfo();
//echo YAF_ERR_STARTUP_FAILED;
header("Content-Type:text/html;Charset = UTF-8");
define('DS',DIRECTORY_SEPARATOR);
define('APPLICATION_PATH',realpath(dirname(__FILE__).DS.'..'.DS));
define('CONFIG_PATH' , realpath(dirname(__FILE__).DS.'..'.DS.'conf').DS);
//echo CONFIG_PATH;
//echo APPLICATION_PATH.DS."application".DS."conf".DS."application.ini";exit;
$application = new Yaf_Application(APPLICATION_PATH.DS."conf".DS."application.ini");
$application
    ->bootstrap()
    ->run();