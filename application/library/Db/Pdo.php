<?php

/**
 * User: Peak
 * Date: 2016/5/24
 * Time: 15:40
 */
class Pdo
{
    private $link;

    /**
     * Pdo constructor.
     * @param Pdo $link
     */
    public function __construct(PDO $link)
    {
        $this->link = $link;
    }

    /**
     * @param String $sql
     * @return maxid
     */
    protected function query(String $sql)
    {
        try{
            $reg = '/^(select) ([a-z0-9A-Z\,\.*]+) (from) (\`[a-z0-9A-Z]+\`|[a-z0-9A-Z]+)/i';
            if(preg_match($sql, $reg)){
                $re = $this->link->query($sql, $this->link->FETCH_ASSOC);
                if(is_array($re)){
                    return $re;
                }
            };
        } catch(PDOException $e){
            return $e -> getMessage();
        }

    }

    protected function getAll($table,$field = '*',$where = '',$limit = '',$join = '',$order = ''){
        try{

        } catch(PDOException $e){
            return $e -> getMessage();
        }

    }

    private function where($where){
        if(is_string($where)){

        }elseif(is_array($where)){

        }
    }
}