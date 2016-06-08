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
    private $field;
    private $table;
    private $limit;
    private $like;
    private $group;
    private $order;
    private $mos;

    /**
     * Pdo constructor.
     * @param Pdo $link
     */
    public function __construct(PDO $link)
    {
        $this->link = $link;
    }

    public function select($talbe = '')
    {
        try {
            if (empty($this->table) && $talbe == '') {
                throw new PDOException('没有表名');
            }
            $this->mosaicSql();
            $field = $this->field?$this->field:'*';
            $this->mos->bindParam('_field',$field);
            $this->mos->bindParam('_table',$this->table);
            $this->where ? $this->mos->bindParam('_where' , $this->where) : '';
            $this->limit ? $this->mos->bindParam('_limit' , $this->limit) : '';
            $this->like  ? $this->mos->bindParam('_like' , $this->like) : '';
            $this->group ? $this->mos->bindParam('_group' , $this->group) : '';
            $this->order ? $this->mos->bindParam('_order' , $this->order) : '';
            return $this->mos->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }


    }

    /**获取表名
     * @param String $table
     * @return Object $this
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * 获取最后一条sql
     * @return String
     */
    public function getLastSql()
    {
        $field = !empty($this->field) ? $this->field : '*';
        $where = !empty($this->where) ? ' WHERE ' . $this->where : '';
        $like = !empty($this->like) ? ' LIKE ' . $this->like : '';
        $order = !empty($this->order) ? ' ORDER BY ' . $this->order : '';
        $group = !empty($this->group) ? ' GROUP BY ' . $this->group : '';
        $limit = !empty($this->limit) ? ' LIMIT ' . $this->limit : '';
        $sql = 'SELECT ' . $field . ' FROM  ' . $this->table . $where . $like . $order . $group . $limit;
        $this->sql = $sql;
        return $this->sql;
    }

    /**
     * 获取查询字段
     * @return Object $this
     */
    public function field($field = NULL)
    {
        $field ? $field : $this->field = ' * ';
        if (is_string($field)) {
            $this->field = $field;
        } else {
            $this->field = ' * ';
        }
        return $this;
    }

    /**
     * @param String $sql
     * @return maxid
     */
    protected function query($sql)
    {

        if (is_string($sql)) {
            $reg = '/^(select) ([a-z0-9A-Z\,\.*]+) (from) (\`[a-z0-9A-Z]+\`|[a-z0-9A-Z]+)/i';
            try {
                if (preg_match($reg, $sql)) {
                    $this->sql = $sql;
                    $query = $this->link->query($sql, PDO::FETCH_ASSOC);
                    return $query->fetchAll();
                };
            } catch (PDOException $e) {
                echo $e->getMessage();
                exit;
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
    public function where($where = null)
    {
        $exp = '';
        $str = '';
        if (!$where) return $this;
        if (is_array($where) ) {
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
                        if (strstr($exp, 'in') ) {
                            $this->where = $key . str_replace('::', $e, $exp);
                        }elseif(!in_array($e,['gt','lt','in','egt','elt','neq','eq'])){
                            $this->where ? $this->where .= ' AND ' . $key.$exp.$e : $this->where = $key . $exp . $e . ' ';
                        }
//                        if(!empty($exp)) $exp = '';
                    }
                } else {
                    $this->where? $this->where .=' AND '. $key . ' = ' . $value:$this->where = $key . ' = ' .$value;
                }
            }

        }elseif(is_string($where)){
            $this->where = $where;
        }
        return $this;
    }


    /**
     * 拼接SQL
     * @return void
     */
    private function mosaicSql()
    {
        $where = !empty($this->where) ? ' WHERE _where ' : '';
        $like = !empty($this->like) ? ' LIKE _linke' : '';
        $order = !empty($this->order) ? ' ORDER BY _order ' : '';
        $group = !empty($this->group) ? ' GROUP BY _group ' : '';
        $limit = !empty($this->limit) ? ' LIMIT _limit ' : '';
        $sql = 'SELECT _field FROM _table ' . $where . $like . $order . $group . $limit;
        try {
            $this-> mos = $this->link->prepare($sql);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}