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
        $sql = "SELECT id,title,cid,gid,introduce FROM `article` WHERE is_show = 1 ";
        return $this->query($sql);
    }
}