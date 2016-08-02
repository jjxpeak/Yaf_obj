<?php

/**
 * User: Peak
 * Date: 2016/4/25
 * Time: 17:09
 */
class indexController extends Yaf_Controller_Abstract
{
    private $view;
    private $model;

    public function init()
    {
        $this->view = $this->initView()->_view;
        $this->model = new Member_Article();
        if ($this->getRequest()->isXmlHttpRequest()) {
            Yaf_Dispatcher::getInstance()->disableView();
        }
    }

    public function indexAction()
    {

    }
    public function listAction(){
        $id = intval($this->getRequest()->getParam('id'));
        $content = $this->model->getArticleContent($id);
        if(!$content){
            header("HTTP/1.1 404 Not Found");
            header('Location:' . $_SERVER['HOST_NAME'] . '/NotFound.html');
        }
        $this->view->assign('content',$content);
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}