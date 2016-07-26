<?php

/**
 * User: Peak
 * Date: 2016/4/25
 * Time: 17:09
 */
class indexController extends Yaf_Controller_Abstract
{
    private $view;

    public function init()
    {
        $this->view = $this->initView()->_view;
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