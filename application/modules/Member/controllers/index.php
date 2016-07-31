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
        $message = null;
        $data['title'] = $_POST['title'] ? $_POST['title'] : $message = 1;
        $data['content'] = $_POST['content'] ? $_POST['content'] : $message = 1;
        $data['introduce'] = $_POST['introduce'];
        $data['gid'] = $_POST['group'] ? $_POST['group'] : $message = 1;
        $data['cid'] = $_POST['category'] ? $_POST['category'] : $message = 1;
        $data['add_time'] = time();
        $data['uid'] = $_SESSION['userInfo']['id'];
        if ($message) {
            echo '数据错误';
            echo '<meta http-equiv="refresh" content="3;url=index"> ';
            exit;
        }
        $re = $this->model->addArticle($data);
        if ($re) {
            echo '添加成功';
            echo '<meta http-equiv="refresh" content="3;url=index"> ';
        } else {
            echo '添加失败';
            echo '<meta http-equiv="refresh" content="3;url=index"> ';
        }
        Yaf_Dispatcher::getInstance()->disableView();
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

    public function articleAction()
    {
        $data = $this->model->getAllArticle();
        $group = $this->model->getCategory();
        $this->view->assign('list', $data['list']);
        $this->view->assign('page',$data['page']);
        $this->view->assign('group', $group);

    }

    public function articleActAction()
    {
        $act = $_GET['act'];
        switch ($act) {
            case 'edit':
                echo $act;
                break;
            case 'del':
                echo $act;
                break;
            default:
                ajax_message(['status' => 0, 'message' => '非法操作']);
        }
        var_dump($_GET);
    }
}