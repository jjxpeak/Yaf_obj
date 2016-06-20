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
        echo '<pre>';
        $re = $this->insert('city',['name' => 'peak3','CountryCode'=>'PSE','district'=>'test','population'=> 000]);

        var_dump($re);
        echo $this->getLastSql();
        return $this->getLastSql();
    }
}