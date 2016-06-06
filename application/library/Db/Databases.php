<?php

/**
 * User: Peak
 * Date: 2016/5/24
 * Time: 15:40
 */
class Db_Databases
{
    private $link;
    private $sql;
    public $where;

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
    protected function query($sql)
    {

        if (is_string($sql)) {
            $reg = '/^(select) ([a-z0-9A-Z\,\.*]+) (from) (\`[a-z0-9A-Z]+\`|[a-z0-9A-Z]+)/i';

            if (preg_match($reg, $sql)) {
                $this->sql = $sql;
                $query = $this->link->query($sql, PDO::FETCH_ASSOC);
                $re = $query->fetchAll();
                if (is_array($re)) {
                    return $re;
                }
            };
        } else {
            return false;
        }

    }

    protected function getAll($table, $field = '*', $where = '', $limit = '', $join = '', $order = '')
    {
        try {

        } catch (PDOException $e) {
            return $e->getMessage();
        }

    }

    public function where($where)
    {
        $exp = '';
        $str = '';
        try {
            if (is_string($where) && !is_null($where) && $where != 'null') {
                $this->where = ' WHERE ' . $where . ' ';
            } elseif (is_array($where) && !is_null($where) && $where != 'null') {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $e) {
                            if(!$exp){
                                switch ($e) {
                                    case 'gt':
                                        $exp = ' > ';
                                        break;
                                    case 'lt':
                                        $exp = ' < ';
                                        break;
                                    case 'in':
                                        $exp = ' in(::) ';
                                        break;
                                    case 'egt':
                                        $exp = ' >= ';
                                        break;
                                    case 'elt':
                                        $exp = ' <= ';
                                        break;
                                    case 'neq':
                                        $exp = ' != ';
                                        break;
                                    case 'eq':
                                    default:
                                        $exp = ' = ';
                                }
                            }
                            if (is_array($e)) {
                                try {
                                    if (strstr($exp,'in')) {
                                        foreach ($e as $s) {
                                            $str .= $s . ',';
                                        }

                                        $str = substr($str, 0,-1);
                                        $this->where  =  '`WHERE`' . str_replace('::',$str ,$exp);
                                    } else {
                                        throw new Yaf_Exception('使用=、>、<、>=、<=、!= 只接收字符串，不接收数组');
                                    }
                                } catch (Yaf_Exception $e) {
                                    echo '错误信息:' . $e->getMessage() . '</br>';
                                    echo '文件位置:' . $e->getFile() . '</br>';
                                    echo '行号:' . $e->getLine() . '</br>';
                                    exit;
                                }
                            } else {
                                $this->where = ' `WHERE`' . $key . $exp . $e . ' ';
                            }
                        }

                    } else {
                        $this->where = ' `WHERE` ' . $key . ' = ' . $value;
                    }
                }
            } else{
                throw new Yaf_Exception('只接收Array和String并且不能为NULL');
            }
        }catch(Yaf_Exception $e){
            echo '错误信息:' . $e->getMessage() . '</br>';
            echo '文件位置:' . $e->getFile() . '</br>';
            echo '行号:' . $e->getLine() . '</br>';
            exit;
        }
        return $this;
    }

    public function getLastSql()
    {
        return $this->sql;
    }
}