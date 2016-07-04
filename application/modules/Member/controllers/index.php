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
        if (isset($_SESSION['userInfo']['id'])) {
            $this->view = $this->initView()->_view;
            $host = Yaf_Registry::get('host');
            $db = Yaf_Registry::get($host);
            $this->model = new Model($db);
            if ($this->getRequest()->isXmlHttpRequest()) {
                Yaf_Dispatcher::getInstance()->disableView();
            }
        } else {
            header("HTTP/1.1 302 Moved Permanently");
            header('Location:http://localhost' . ROOT_PATH . '/member/login/index');
//            $this->forward('Member','Login','index');
        }
    }


    public function indexAction()
    {

    }
}