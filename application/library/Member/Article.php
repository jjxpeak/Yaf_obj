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
}