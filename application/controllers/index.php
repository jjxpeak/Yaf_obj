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
            $this->locationNotFountAction();
        }
        $this->view->assign('content',$content);
    }

    public function articleListAction($g){
        $act = intval($g);
        if($this->model->chackGroup($act)){
            $data = $this->model->getArticleList($act);
            $this->view->assign('list',$data['list']);
            $this->view->assign('act',$act);
            if(!empty($data['page'])){
                $this->view->assign('page',$data['page']);
            }
        }else{
            $this->locationNotFountAction();
        }
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    public function locationNotFountAction(){
        header("HTTP/1.1 404 Not Found");
        header('Location:' . $_SERVER['HOST_NAME'] . '/NotFound.html');
    }
}