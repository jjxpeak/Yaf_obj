<?php

/**
 * User: Peak
 * Date: 2016/4/25
 * Time: 17:09
 */
class indexController extends Yaf_Controller_Abstract
{
    private $model;
    private $view;

    public function init()
    {
        $this->view = $this->initView()->_view;
        $host = Yaf_Registry::get('host');
        $db = Yaf_Registry::get($host);
        $this->model = new Model($db);
        if ($this->getRequest()->isXmlHttpRequest()) {
            Yaf_Dispatcher::getInstance()->disableView();
        }
    }

    public function indexAction()
    {
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}