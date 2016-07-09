<?php
/**
 * User: Peak
 * Date: 2016/4/25
 * Time: 9:45
 */
header("Content-Type:text/html;Charset = UTF-8");
define('DS',DIRECTORY_SEPARATOR);
define('WEB_KEY',md5('jzk_peak'));
define('APPLICATION_PATH',realpath(dirname(__FILE__).DS.'..'.DS));
define('CONFIG_PATH' , realpath(dirname(__FILE__).DS.'..'.DS.'conf').DS);
define('HEADER_PATH',APPLICATION_PATH . '/application/views/public/Header.html');
define('FOOTER_PATH',APPLICATION_PATH . '/application/views/public/Footer.html');
define('ROOT_PATH',dirname($_SERVER['SCRIPT_NAME']));
require APPLICATION_PATH.'/application/function/function.php';
$application = new Yaf_Application(APPLICATION_PATH.DS."conf".DS."application.ini");
$application
    ->bootstrap()
    ->run();

