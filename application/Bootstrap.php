<?php

/**
 * User: Peak
 * Date: 2016/4/27
 * Time: 10:17
 */

/**
 * Class Bootstrap
 * 初始化系统配置类
 */
class Bootstrap extends Yaf_Bootstrap_Abstract
{
    /**
     * 注册系统配置项
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initConfig(Yaf_Dispatcher $dispatcher){
        ini_set('yaf.thowException' , 1);
        require( CONFIG_PATH."config.php");
        $config['server_config'] = $conf;
        $config['yaf_config'] = Yaf_Application::app()->getConfig();
        Yaf_Registry::set("config" , $config);
    }

    /**
     * 注册数据库连接
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initDb(Yaf_Dispatcher $dispatcher){

        $config = Yaf_Registry::get('config')['server_config'];
        $db = new Db($config);
        Yaf_Registry::set('db',$db);
    }

    public function __initSerurity(Yaf_Dispatcher $dispatcher){
        $secunrity = new Security();
        Yaf_Registry::set('secunrity',$secunrity);
    }


}