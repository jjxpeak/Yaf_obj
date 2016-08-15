<?php

/**
 * Created by PhpStorm.
 * User: peak
 * Date: 2016/7/7
 * Time: 15:58
 */
class Index_User extends Model
{
    public function getUserInfo($username,$password,$find){
        $sql = "SELECT {$find} FROM `user` WHERE `username` =  ?  AND `password` =  ? LIMIT 1 ";
        $re = $this->query($sql,[$username,$password]);
        if(empty($re)){
           return false;
        }else{
            return $re[0];
        }
    }
}