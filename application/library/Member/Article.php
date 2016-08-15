<?php

/**
 * Created by PhpStorm.
 * User: peak
 * Date: 2016/7/12
 * Time: 15:17
 */
class Member_Article extends Model
{
    public function getCategory(){
        $sql = "SELECT * FROM `category` WHERE type = ? ";
        $re = $this->query($sql,[0]);
        if(empty($re)){
            return false;
        }else{
            return $re;
        }
    }

    public function updateCategory($id){

        return $this->update('category',['type'=>1],"id = {$id}");
    }

    public function addCategory($data){
        $sql = "SELECT * FROM `category` WHERE name = ?";
        $re = $this->query($sql,[$data['name']]);
        if($re){
            return $this->update('category',['type'=>0],'id='.$re[0]['id']);
        }else{
           return $this->insert('category',$data);
        }
    }

    public function addArticle($data){
        return $this->insert('article',$data);
    }

    public function getAllArticle(){
        if($_SESSION['userInfo']['power'] == 1){
            $count = $this->query("SELECT COUNT('id') as count FROM `article` WHERE is_show = ? ",[1])[0]['count'];
            if($count > 15){
                $page = new Page($count);
                $sql = "SELECT id,title,cid,gid,introduce FROM `article` WHERE is_show = ? LIMIT $page->firstRow,$page->lastRow";
                $param = [1];
                $data['page'] = $page;
            }else{
                $sql = "SELECT id,title,cid,gid,introduce FROM `article` WHERE is_show = ?";
                $param = [1];
            }
        }else{
            $count = $this->query("SELECT COUNT('id') as count FROM `article` WHERE is_show = ? AND uid = ?",[1,$_SESSION['userInfo']['id']])[0]['count'];
            if($count > 15){
                $page = new Page($count);
                $sql = "SELECT id,title,cid,gid,introduce FROM `article` WHERE is_show = ? AND uid = ? LIMIT $page->firstRow,$page->lastRow";
                $param = [1,$_SESSION['userInfo']['id']];
                $data['page'] = $page;
            }else{
                $sql = "SELECT id,title,cid,gid,introduce FROM `article` WHERE is_show = ? AND uid = ?";
                $param = [1,$_SESSION['userInfo']['id']];
            }
        }
        $data['list'] = $this->query($sql,$param);
        return $data;
    }

    public function getArticleContent($id){
        $sql = "SELECT a.id,a.title,a.cid,a.gid,content,add_time,save_time,introduce FROM `article` a WHERE a.id = ?";
        $content =  $this->query($sql,[$id]);
        $group = $this->getCategory();
        foreach($content as $k => &$v){
            foreach($group as $gk => $gv){
                if($v['cid'] == $gv['id']){
                    $v['cname'] = $gv['name'];
                }
                if($v['gid'] == $gv['id']){
                    $v['gname'] = $gv['name'];
                }
            }
        }
        unset($k,$v);
        return $content[0];
    }
    public function updateArticle($data,$id){
        return $this->update('article',$data,'id='.$id);
    }

    /**
     * 逻辑删除文章
     * @param $id
     * @return bool
     */
    public function delArticle($id){
        return $this->update('article',['is_show'=>0],'id='.$id);
    }

    /**
     * 获取用户列表
     */
    public function getUser(){
        $sql = "SELECT id,username,status,power FROM `user` WHERE status = ?";
        return $this->query($sql,[1]);
    }
    /**
     * 添加用户
     */
    public function addUser($data){
        if($this->insert('user',$data)){
            return $this->query("SELECT id,username,power FROM `user` WHERE username =? AND password = ?",[$data['username'],$data['password']])[0];
        }

    }

    /**
     * 检查用户名是否重复
     */
    public function checkUser($username){
        $sql = "SELECT id FROM `user` WHERE username = ?";
        $user = $this->query($sql,[$username]);
        if($user){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 获取列表页内容
     * @param $group 分组级别
     * @return mixed 分页及数据
     */
    public function getArticleList($group){
        $count = $this->query("SELECT count(*) as count FROM `article` WHERE is_show = 1 AND gid = ? OR cid=?",[$group,$group])[0]['count'];
        if($count > 9){
            $page = new Page($count,9);
            $sql = "SELECT path,id,title,introduce,gid,cid,add_time FROM `article` WHERE is_show = 1 AND gid = ? OR cid=? LIMIT {$page->firstRow},{$page->lastRow}";
            $param = [$group,$group];
            $data['page'] = $page;
        }else{
            $sql = "SELECT path,id,title,introduce,gid,cid,add_time FROM `article` WHERE is_show = 1 AND gid = ? OR cid=?";
            $param = [$group,$group];
        }
        $data['list'] = $this->query($sql,$param);
        return $data;
    }

    public function chackGroup($group){
        return $this->query("SELECT count(*) as count FROM `article` WHERE is_show  = 1 AND gid = ? OR cid= ?",[$group,$group])[0]['count'];
    }

    public function getListLinkGroup($id){
        return $this->query("SELECT id,name FROM `category` WHERE id = ?",[$id])[0];
    }

}