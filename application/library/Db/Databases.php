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
    private $mso;
    private $date = [];
    /**
     * Pdo constructor.
     * @param Pdo $link
     */
    public function __construct(PDO $link)
    {
        $this->link = $link;
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
     * 查询
     * @param String $sql
     * @param int $type
     * @return array $query
     */
    public function query($sql, $type = PDO::FETCH_ASSOC)
    {

        if (is_string($sql) && preg_match_all('/^select/i',$sql)) {
            try {
                $this->sql = $sql;
                $this->mso = $this->link->prepare($sql);
                $result = $this->mso->execute();
                if ($result == false) {
                    throw new PDOException($this->getError());
                }
                return $this->mso->fetchAll($type);
            } catch (PDOException $e) {
                $this->getErrorMessage($e);
                exit;
            }
        } else {
            return NULL;
        }
    }

    /**
     * 更新操作
     *
     * @param string $table
     * @param mixed $date
     * @param mixed $where
     * @param string $sundry
     * @return bool
     */
    public function update($table, $date = array(), $where,$sundry = '')
    {
        $whereString = ' WHERE ';
        $dateString = ' ';
        $sqlWhere = ' WHERE ';
        $sqlDate = ' ';
        try{

            if (empty($where) && !is_array($where)) {
                return false;
            }
            if (empty($table) && !is_string($table)) {
                return false;
            }
            if (empty($date) && !is_array($date) || empty($this->date)) {
                return false;
            }
            !empty($this->date) && $date = array_merge($date,$this->date);
            if(is_string($where)){
                $whereString .= $where;
                $sqlWhere .= $where;
            }elseif(is_array($where)){
                foreach($where as $key => $value){
                    $whereString .= " {$key} = :{$key} AND ";
                    $sqlWhere .= $key . ' = ' .$value . ' AND ';
                }
                unset($key,$value);
                $whereString = substr($whereString , 0 , -4);
                $sqlWhere = substr($sqlWhere , 0 , -4);

            }

            if(is_string($date)){
                $dateString .= $date;
                $sqlDate = $date;
            }elseif(is_array($date)){
                foreach($date as $key => $value){
                    $dateString .= " {$key} = :{$key} , ";
                    $sqlDate .= " {$key} = {$value} , ";
                }
                $dateString = substr($dateString , 0 , -2);
                $sqlDate = substr($sqlDate , 0 , -2);
            }
            $sql = "UPDATE `{$table}` SET ".$dateString.$whereString.$sundry;
            $this->sql = "UPDATE `{$table}` SET ".$sqlDate.$sqlWhere.$sundry;
            $this->mso = $this->link->prepare($sql);
            $this->bindSql($date);
            $this->bindSql($where);
            $re = $this->mso->execute();
            if(!$re){
                throw new PDOException($this->getError());
            }else{
                return $this->mso->rowCount();
            }
        }catch(PDOException $e){
            $this->getErrorMessage($e);
            exit;
        }
    }

    /**
     * 删除行
     * @param string $table
     * @param mixed$where
     * @return bool|int
     */
    public function delete($table,$where){

        $deleteSql = '';
        $deleteWhere = ' WHERE ';
        if(empty($table) && empty($where)){
            return false;
        }
        if(is_string($where)){
            $deleteWhere .= $where;
        }elseif(is_array($where)){
            foreach($where as $key => $value){
                $deleteWhere .= " {$key} = '{$value}' AND ";
            }
            $deleteWhere = substr($deleteWhere,0,-4);
        }
        $deleteSql .= "DELETE FROM `{$table}` {$deleteWhere}";
        $this->sql = $deleteSql;
        try{
            $this->mso = $this->link->prepare($deleteSql);
            $re = $this->mso->execute();
            if(!$re){
                throw new PDOException($this->getError());
            }else{
                return $this->mso->rowCount();
            }
        }catch (PDOException $e){
            $this->getErrorMessage($e);
            exit;
        }

    }



    /**
     * 添加行
     * @param $table
     * @param $date
     * @return bool
     */
    public function insert($table,$date){

        $sqlStr = '';
        $listStr = '';
        $bindListStr = '';
        $listSql = '';
        $bindSql = '';
        if(empty($table) && empty($date)) return false ;
        if(is_array($date)){
            !empty($this->date) && $date = array_merge($date,$this->date);
            foreach($date as $key => $value){
                $listStr .= $key . ', ';
                $bindListStr .= ':'.$key.', ';
                $listSql .= '\''.$key.'\' , ';
                $bindSql .=  $value. ', ';
            }
            $listStr = substr($listStr , 0 , -2);
            $bindListStr = substr($bindListStr , 0 , -2);
            $listSql = substr($listSql , 0 , -2);
            $bindSql = substr($bindSql , 0 , -2);
        }else{
            return false;
        }

        $this->sql = "INSERT INTO `{$table}` ( {$listSql} ) VALUES ( $bindSql )";
        $sqlStr .= "INSERT INTO `{$table}` ( {$listStr} ) VALUES ( $bindListStr )";
        try{
            $this->mso = $this->link->prepare($sqlStr);
            $this->bindSql($date);
            $re =  $this->mso->execute();
            if(!$re){
                throw new PDOException($this->getError());
            }else{
               return $this->mso->rowCount();
            }
        }catch(PDOException $e){
            $this->getErrorMessage($e);
            exit;
        }
    }

    /**
     * 执行SQL
     * @param $sql
     * @return bool
     */
    public function exec($sql){
        if(!is_string($sql) && preg_match_all('/^[select|update|insert]/i',$sql)) return false;
        $this->mso = $this->link->prepare($sql);
        try{
            if($this->mso->execute()){
                return true;
            }else{
                throw new PDOException($this->getError());
            }
        }catch(PDOException $e){
            $this->getErrorMessage($e);
        }
    }

    public function __set($name,$value)
    {
        $this->date[$name] = $value;
    }

    public function __get($name)
    {
        return $this->date[$name];
    }


    /**
     * 绑定参数
     * @param array $date
     */
    private function bindSql($date){
        if(!is_string($date)) {
            foreach ($date as $key => $value) {
                $this->mso->bindValue(':'.$key , $value);
            }
        }
    }

    /**
     * 获取错误信息
     * @return string
     */
    private function getError()
    {

        $errorInfo = $this->mso->errorInfo();
        $errorMessage = '<b>[ 错误信息 ] : </b>' . ' [No] ' . $errorInfo[1] . ' : ' . $errorInfo[2] . '</br>';
        if ($this->sql) {
            $errorMessage .= "\n<b>[ SQL语句 ] : </b> " . $this->sql . '</br>';
        }
        return $errorMessage;
    }

    /**
     * 输出错误信息
     * @param PDOException $e
     */
    private function getErrorMessage($e)
    {
        $str = $e->getMessage();
        $str .= "\n<b>[ 文件位置 ] : </b> " . $e->getFile() . '</br>';
        $str .= "\n<b>[ TRACE ] : </b><p>" . $e->getTraceAsString() . '</p>';
        echo $str;
        exit;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        if($this->mso){
            $this->mso = NULL;
        }
        $this->link = NUll;
    }

}