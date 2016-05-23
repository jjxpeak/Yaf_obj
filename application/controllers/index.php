<?php

/**
 * User: Peak
 * Date: 2016/4/25
 * Time: 17:09
 */
class indexController extends Yaf_Controller_Abstract
{
    public function indexAction(){
        $db = Yaf_Registry::get('db');
        $a = new Uploader();
        $a -> index();
    }
}