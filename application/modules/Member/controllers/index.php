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
            $this->model = new Member_Article();
            if ($this->getRequest()->isXmlHttpRequest()) {
                Yaf_Dispatcher::getInstance()->disableView();
            }
        } else {
            header("HTTP/1.1 302 Moved Permanently");
            header('Location:' . $_SERVER['HOST_NAME'] . '/member/login/index?returnUrl=' . urlencode($_SERVER['HOST_NAME'] . $_SERVER['PATH_INFO']));
        }
    }


    public function indexAction()
    {
        $category = $this->model->getCategory();
        $this->view->assign('category', json_encode($category));
    }

    public function saveContentAction()
    {
        var_dump($_POST);
    }

    public function categoryAction()
    {
        $category = $this->model->getCategory();
        $this->view->assign('category', json_encode($category));
    }

    public function delGroupAction(){
        if(empty($_POST['cid'])){
            ajax_massage(['status'=>0,'message'=>'数据错误']);
        }
        $cid = $_POST['cid'];
        if(is_numeric($_POST['cid'])){
            if(is_array($_POST['genus'])){
                if($this->delAction($cid)){
                    $data['status'] =1;
                    $data['genus'] = $this->model->getCategory();
                    ajax_massage($data);
                }
            }else{
                ajax_massage(['status'=>0,'message'=>'数据错误']);
            }
        }else{
            ajax_massage(['status'=>0,'message'=>'只能删除一个分类']);
        }
    }

    private function delAction($cid){
        return $this->model->updateCategory($cid);
    }
}