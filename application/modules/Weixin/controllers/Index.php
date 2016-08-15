<?php

/**
 * User: Peak
 * Date: 2016/4/25
 * Time: 17:09
 */
class indexController extends Yaf_Controller_Abstract
{
    private $view;
    private $appId='wx5f74314bc15752fa';
    private $appSecret='6322a81d64056bb4a97c1332d404d832';
    public function init(){
        $this->view = $this->initView()->_view;
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()){
            Yaf_Dispatcher::getInstance()->disableView();
        }
    }
    public function indexAction(){
        $button = array(
            array('type'=>'click','name'=>'测试1','key'=>'test_1'),
        );
        echo json_encode($button);
        Curl('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=5fc21599b10793a858811d1f172be466',json_encode($button));
        Yaf_Dispatcher::getInstance()->disableView();
    }
    public function get_accTokenAction(){
        var_dump( Curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->appSecret}",'GET'));
        Yaf_Dispatcher::getInstance()->disableView();
    }
    public function checkWeiXinAction(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = WEB_KEY;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            echo $_GET['echoStr'];
        }else{
            return false;
        }
    }
}
