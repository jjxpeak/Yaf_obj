<?php
/**
 * Created by PhpStorm.
 * User: peak
 * Date: 2016/7/31
 * Time: 14:21
 */

class Page{

    public $firstRow;
    public $lastRow;
    public $total;
    public $page;
    public $nowPage = 1;
    public $p='p';
    /**
     * Page constructor.
     * @param $count 总数
     * @param int $row 每页条数
     */
    public function __construct($count,$row=15)
    {
        $this->lastRow=$row;
        $this->total=$count;
        empty($_GET[$this->p])?$this->firstRow= 0 :$this->firstRow=(intval($_GET[$this->p])-1)*$this->lastRow;
        $this->page=ceil($this->total/$this->lastRow);
        $this->nowPage    = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
        $this->nowPage    = $this->nowPage>0 ? $this->nowPage : 1;
    }
}