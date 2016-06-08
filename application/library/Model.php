<?php

/**
 * User: Peak
 * Date: 2016/6/3
 * Time: 10:13
 */
class Model extends Db_Databases
{
    public function __construct($link)
    {
        parent::__construct($link);
    }

    public function test()
    {
        $where = array('id'=>array('in','1,2,3'));
        $this->table('city');
        var_dump($this->select());
        return $this->getLastSql();
    }
}