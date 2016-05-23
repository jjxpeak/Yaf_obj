<?php

/**
 * User: Peak
 * Date: 2016/4/27
 * Time: 11:08
 * 数据库连接
 */
class Db
{
    private $dns;
    private $user;
    private $password;
    public $link;
    private $type;
    private $host;
    private $database;
    public function __construct($config){
        $this -> parseConfig($config);
        $this -> linkDb();
    }
    /*
     * 解析config
     * @param  $config
     */
    private function parseConfig($config){
        if(is_array($config)){
            if(!empty($config['db_type'])){
                switch($config['db_type']){
                    case 'mysql':
                        $this->user = $config['user'];
                        $this->password = $config['password'];
                        $this->type = $config['db_type'];
                        $this->host = $config['host'];
                        $this->database = $config['database'];
                        break;
                    case 'PDO':
                    default:
                    $this->dns = "mysql:dbname = ".$config['database'].';host='.$config['host'];
                    $this->user = $config['user'];
                    $this->password = $config['password'];
                    $this->type=$config['db_type'];
                }
            }
        }
    }

    /**
     * 连接数据库
     */
    private function linkDb(){
        switch($this->type){
            case 'mysql':
                $this->link = mysqli_connect($this->host,$this->user,$this->password,$this->database);
                break;
            case 'PDO':
            default;
            $this->link = new $this->type($this->dns,$this->user,$this->password);
        }
    }
}