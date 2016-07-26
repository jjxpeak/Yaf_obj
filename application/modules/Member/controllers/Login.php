<?php

/**
 * Created by PhpStorm.
 * User: peak
 * Date: 2016/7/4
 * Time: 11:52
 */
class loginController extends Yaf_Controller_Abstract
{
    private $view;
    public function init()
    {
        $this->view = $this->initView()->_view;
        if ($this->getRequest()->isXmlHttpRequest()) {
            Yaf_Dispatcher::getInstance()->disableView();
        }
    }

    public function indexAction(){
        unset($_SESSION['userInfo']);
        $returnUrl = !empty($_GET['returnUrl']) ?$_GET['returnUrl']:'';
        $this->view->assign('returnUrl',$returnUrl);
    }
    public function actAction(){
        $_SESSION['num'] = 0;
        if($_SESSION['num'] > 4){
            $data = array('massage'=>'尝试次数太多了！！','state'=>0);
            ajax_massage($data);
        }
        if(!isset($_POST['username']) && !isset($_POST['password']) && !is_string($_POST['password']) && !is_string($_POST['username']) ) return false;
        $user = $_POST['username'];
        $pass = encrypt(md5($_POST['password']),WEB_KEY);
        $_SESSION['num'] +=1;
        $userLink= new Index_User();
        $info = $userLink->getUserInfo($user,$pass,'id,username,password');

        if($info){
            $_SESSION['userInfo'] = $info;
            $data = array(
                'massage'=>'登陆成功',
                'state' => 1
            );
        }else{
            $data = array('massage'=>'用户名或密码错误！','state'=>0);
        }
        ajax_massage($data);
    }
}