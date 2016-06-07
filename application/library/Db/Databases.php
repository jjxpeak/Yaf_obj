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
    private $where;

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
        try{
            if (preg_match($reg, $sql)) {
                $this->sql = $sql;
                $query = $this->link->query($sql, PDO::FETCH_ASSOC);
                $re = $query->fetchAll();
                if (is_array($re)) {
                    return $re;
                }
            };
        }catch (PDOException $e){
                echo $e->getMessage();
            }
        } else {
            return NULL;
        }

    }

    protected function getAll($table, $field = '*', $where = '', $limit = '', $join = '', $order = '')
    {
        try {

        } catch (PDOException $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param mixed $where
     * @return Object $this
     */
    protected function where($where = null)
    {
        $exp = '';
        $str = '';
        if(!$where) return $this;
        if (is_array($where) && !is_null($where) && $where != 'null') {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $e) {
                        if (!$exp) {
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
                        if (strstr($exp, 'in')) {
                            $this->where = '`WHERE`' . str_replace('::', $e, $exp);
                        }
                        $this->where ? $this->where : $this->where = ' `WHERE`' . $key . $exp . $e . ' ';

                    }

                } else {
                    $this->where = ' `WHERE` ' . $key . ' = ' . $value;
                }
            }
        }
        return $this;
    }

    /**
     * 获取最后一条sql
     * @return String
     */
    public function getLastSql()
    {
        return $this->sql;
    }

    /**
     * 获取查询字段
     * @return Object $this
     */
    public function field(){

    }
}