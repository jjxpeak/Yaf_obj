<?php

/**
 * Created by PhpStorm.
 * User: peak
 * Date: 2016/7/7
 * Time: 15:58
 */
class User extends Model
{
    public function getUserInfo($username,$password,$find){
        $sql = "SELECT {$find} FROM `user` WHERE `username` = {$username} AND `password` = {$password}";
        return $this->link->query($sql);
    }
}