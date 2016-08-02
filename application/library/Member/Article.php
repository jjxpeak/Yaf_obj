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
        $sql = "SELECT * FROM `category` WHERE type = 0 ";
        $re = $this->query($sql);
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
        $sql = "SELECT * FROM `category` WHERE name = '{$data['name']}'";
        $re = $this->query($sql);
        if($re){
            return $this->update('category',['type'=>0],'id='.$re[0]['id']);
        }else{
           return $this->insert('category',$data);
        }
    }

    public function addArticle($data){
        return $this->insert('Article',$data);
    }

    public function getAllArticle(){
        $count = $this->query("SELECT COUNT('id') as count FROM `article` WHERE is_show = 1 ")[0]['count'];
        $page = new Page($count);
        $sql = "SELECT id,title,cid,gid,introduce FROM `article` WHERE is_show = 1 LIMIT $page->firstRow,$page->lastRow";
        $data['list'] = $this->query($sql);
        $data['page'] = $page;
        return $data;
    }

    public function getArticleContent($id){
        $sql = "SELECT a.id,a.title,a.cid,a.gid,content,add_time,save_time,introduce FROM `article` a WHERE a.id = {$id}";
        $content =  $this->query($sql);
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
        $sql = "SELECT id,username,status,power FROM `user` WHERE status = 1";
        return $this->query($sql);
    }
    /**
     * 添加用户
     */
    public function addUser($data){
        return $this->insert('user',$data);
    }

    /**
     * 检查用户名是否重复
     */
    public function
}