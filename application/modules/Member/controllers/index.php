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
    private $power = array('delgroup','addgroup','adduser');


    public function init()
    {
        if (isset($_SESSION['userInfo']['id'])) {
            $request = $this->getRequest();
            if(in_array($request->action,$this->power)){
                if($_SESSION['userInfo']['power'] !=1 ) {
                    if ($request->isXmlHttpRequest()) {
                        ajax_message(['status' => 0, 'message' => '权限不足']);
                    } else {
                        $this->locationNotFountAction();
                    }
                }
            }
            $this->view = $this->initView()->_view;
            $this->model = new Member_Article();
            if ($request->isXmlHttpRequest()) {
                Yaf_Dispatcher::getInstance()->disableView();
            }
        } else {
            header("HTTP/1.1 302 Moved Permanently");
            header('Location:http://' . $_SERVER['HTTP_HOST'] . '/member/login/index?returnUrl=' . urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL']));
        }
    }

    /**
     * 添加文章
     */
    public function indexAction()
    {
        $category = $this->model->getCategory();

        $this->view->assign('category', json_encode($category));

    }

    /**
     * 保存文章
     */
    public function saveContentAction()
    {
        $this->contentActAction('add');
    }

    /**
     * 分类列表
     */
    public function categoryAction()
    {
        $category = $this->model->getCategory();
        $this->view->assign('category', json_encode($category));
    }

    /**
     * 删除分类
     */
    public function delGroupAction()
    {
        if (empty($_POST['cid'])) {
            ajax_message(['status' => 0, 'message' => '数据错误']);
        }
        $cid = $_POST['cid'];
        if (is_numeric($_POST['cid'])) {
            if (is_array($_POST['genus'])) {
                if ($this->delAction($cid)) {
                    $data['status'] = 1;
                    $data['genus'] = $this->model->getCategory();
                    ajax_message($data);
                }
            } else {
                ajax_message(['status' => 0, 'message' => '数据错误']);
            }
        } else {
            ajax_message(['status' => 0, 'message' => '只能删除一个分类']);
        }
    }

    private function delAction($cid)
    {
        return $this->model->updateCategory($cid);
    }

    /**
     * 添加分类
     */
    public function addGroupAction()
    {
        $name = $_POST['name'];
        $cid = $_POST['cid'];
        if ($cid) {
            $data = array('name' => $name, 'cid' => $cid, 'type' => 0, 'Grade' => 2);
        } else {
            $data = array('name' => $name, 'cid' => 0, 'type' => 0, 'Grade' => 1);
        }

        $re = $this->model->addCategory($data);
        if ($re) {
            ajax_message(['status' => 1, 'message' => '添加成功', 'data' => $this->model->getCategory()]);
        } else {
            ajax_message(['status' => 0, 'message' => $this->model->getLastSql()]);
        }
    }

    /**
     * 文章列表
     */
    public function articleAction()
    {
        $data = $this->model->getAllArticle();
        $group = $this->model->getCategory();
        $this->view->assign('list', $data['list']);
        if(!empty($data['page'])){
            $this->view->assign('page',$data['page']);

        }
        $this->view->assign('group', $group);

    }

    /**
     * 文章操作
     */
    public function articleActAction()
    {
        $act = $_GET['act'];
        $id = intval($_GET['id']);
        switch ($act) {
            case 'del':
                $re = $this->model->delArticle($id);
                if($re){
                    ajax_message(['status'=>1]);
                }else{
                    ajax_message(['status'=>0,'message'=>'删除失败或文章不存在']);
                }
                break;
            default:
                ajax_message(['status' => 0, 'message' => '非法操作']);
        }
    }

    /**
     * 修改文章页
     */
    public function modifyAction(){
        $id = intval($_GET['id']);
        $category = $this->model->getCategory();
        $content = $this->model->getArticleContent($id);
        if(!$content){
            $this->locationNotFountAction();
        }
        $this->view->assign('category', json_encode($category));
        $this->view->assign('content', $content);
    }

    /**
     * 文章修改保存
     */
    public function updateContentAction(){
        $this->contentActAction('update');
    }

    /**
     * 添加修改操作
     * @param $act 操作方法
     */
    private function contentActAction($act){
        if(empty($_POST['title'])){
            $this->locationNotFountAction();
            exit;
        }
        $message = null;
        $data['title'] = $_POST['title'] ? $_POST['title'] : $message = 1;
        $data['content'] = $_POST['content'] ? $_POST['content'] : $message = 1;
        $data['introduce'] = $_POST['introduce'];
        $data['gid'] = $_POST['group'] ? $_POST['group'] : $message = 1;
        $data['cid'] = $_POST['category'] ? $_POST['category'] : $message = 1;
        $data['uid'] = $_SESSION['userInfo']['id'];
        if ($message) {
            echo '<h1>数据错误</h1>';
            echo '<meta http-equiv="refresh" content="3;url=index"> ';
            exit;
        }
        if($act == 'update'){
            if(empty($_POST['add_time']) && empty($_POST['id'])) {
                echo '<h1>数据错误</h1>';
                echo '<meta http-equiv="refresh" content="3;url=index"> ';
                exit;
            }
            $add_time = intval($_POST['add_time']);
            $data['add_time']= $add_time;
            $id = intval($_POST['id']);
            $data['save_time'] = time();
            $path = 'html/'.date('Ymd',$add_time);
            $fileName = date('YmdHis',$add_time).'.html';
            if($this->buildHtmlAction($data,$path,$fileName,'update',$id)) {
                $data['path'] =$path.'/'.$fileName;
            }
            $re = $this->model->updateArticle($data,$id);
            if ($re) {
                echo '<h1>修改成功</h1>';
                echo '<meta http-equiv="refresh" content="3;url=article"> ';
            } else {
                echo '<h1>修改失败</h1>';
                echo '<meta http-equiv="refresh" content="3;url=modify?id=".$id> ';
            }
        }else if($act == 'add'){
            $data['add_time'] = time();
            $path = 'html/'.date('Ymd');
            $fileName = date('YmdHis').'.html';
            if($this->buildHtmlAction($data,$path,$fileName)){
                $data['path'] = $path.'/'.$fileName;
            }
            $re = $this->model->addArticle($data);
            if ($re) {
                echo '<h1>添加成功</h1>';
                echo '<meta http-equiv="refresh" content="3;url=index"> ';
            } else {
                echo '<h1>添加失败</h1>';
                echo '<meta http-equiv="refresh" content="3;url=index"> ';
            }
        }
        Yaf_Dispatcher::getInstance()->disableView();
    }

    /**
     * 用户管理
     */
    public function userAction(){
        $userList = $this->model->getUser();
        $this->view->assign('userList',$userList);
    }

    public function addUserAction(){
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $password = enCrypt($password,WEB_KEY);
        $this->model->checkUser($username)?1:ajax_message(['status'=>0 ,'message'=>'用户名存在']);
        $data = compact('username','password');
        $user = $this->model->addUser($data);
        if($user){
            ajax_message(['status'=>1,'data'=>$user]);
        }else{
            ajax_message(['status'=>0,'message'=>'添加失败请重试']);
        }
    }

    private function getPassAction(){
        echo enCrypt(@md5($_GET['tt']),WEB_KEY);
        Yaf_Dispatcher::getInstance()->disableView();
    }

    public function cellAction($p){
        if($p == 'peakget'){
            $this->getPassAction();
        }else{
            $this->locationNotFountAction();
        }
    }
    private function locationNotFountAction(){
        header("HTTP/1.1 404 Not Found");
        header('Location:' . $_SERVER['HOST_NAME'] . '/NotFound.html');
    }

    private function buildHtmlAction($data,$path,$fileName){
        $this->view->assign('group',$this->model->getListLinkGroup($data['gid']));
        $this->view->assign('category',$this->model->getListLinkGroup($data['cid']));
        $this->view->assign('content',$data);
        $html =  $this->render('list');
        if(!file_exists($path)){
            mkdir($path,0777,true);
        }
        $file = fopen($path.'/'.$fileName,'w+');
        if($file){
            if(fwrite($file,$html)){
                fclose($file);
                return true;
            }else{
                return false;
            }
        }
    }
}
