<?php

/**
 * User: Peak
 * Date: 2016/6/3
 * Time: 10:13
 */
class Model extends Db_Databases
{
    public function __construct()
    {
        $host = Yaf_Registry::get('host');
        $db = Yaf_Registry::get($host);
        parent::__construct($db);
    }
}