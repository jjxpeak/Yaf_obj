<?php

/**
 * User: Peak
 * Date: 2016/4/25
 * Time: 17:09
 */
class indexController extends Yaf_Controller_Abstract
{
    private $view;
    private function init(){
        $this->view = $this->initView()->_view;
    }
    public function indexAction(){
        $id = $this->getRequest()->getParam('id');
        $this->view->assign('id',$id);
    }
}