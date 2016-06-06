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
    private $_config;
    /**
     * 注册系统配置项
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initConfig(Yaf_Dispatcher $dispatcher){
        //ini_set('yaf.thowException' , 1);
        $config = require( CONFIG_PATH."config.php") ;
        $this->_config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set("config" , $config);
    }

    /**
     * 注册数据库连接
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initDb(Yaf_Dispatcher $dispatcher){

        $config = Yaf_Registry::get('config');
        new Db($config);
    }

    /**
     * 注册加密类
     * @param Yaf_Dispatcher $dispatcher
     */
    public function __initSerurity(Yaf_Dispatcher $dispatcher)
    {
        $secunrity = new Security();
        Yaf_Registry::set('secunrity', $secunrity);
    }

    /**
     * 注册路由
     * @param Yaf_Dispatcher $dispatcher
     */
    public function _initRoute(Yaf_Dispatcher $dispatcher){
        $router = Yaf_Dispatcher::getInstance()->getRouter();
        $router->addConfig($this->_config->routes);
    }




}