<?php

/**
 * Created by PhpStorm.
 * User: peak
 * Date: 2016/7/31
 * Time: 14:21
 */
class Page
{

    public $firstRow;
    public $lastRow;
    public $total;
    private $page;
    private $nowPage = 1;
    private $p = 'p';
    private $last;
    private $next;
    private $row;

    /**
     * Page constructor.
     * @param $count 总数
     * @param int $row 每页条数
     */
    public function __construct($count, $row = 15)
    {
        $this->lastRow = $this->row = $row;
        $this->total = $count;
        empty($_GET[$this->p]) ? $this->firstRow = 0 : $this->firstRow = (intval($_GET[$this->p]) - 1) * $this->lastRow;
        $this->page = ceil($this->total / $this->lastRow);
        $this->nowPage = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
        $this->nowPage = $this->nowPage > 0 ? $this->nowPage : 1;
        $this->last = $this->nowPage - 1;
        $this->next = $this->nowPage + 1;

    }

    public function pageShow($action)
    {
        if($this->row > $this->total){
            return false;
        }
        echo '<div class="row page"><ul class="pagination">';
        if (($this->nowPage - 1) <= 0) {
            echo "<li class='disabled'><a href='javascript:void(0);' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
        } else {
            echo "<li><a href='$action?p={$this->last}' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
        }
        for ($i = 1; $i <= $this->page; $i++) {
            echo "<li><a href='$action?p=$i'>$i</a></li>";
        }
        if (($this->nowPage) >= $this->page) {
            echo "<li class='disabled'><span aria-hidden='true'><a href='javascript:void(0);' aria-label='Next'>&raquo;</a></span></li>";
        } else {
            echo "<li><a href='$action?p={$this->next}' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
        }
        echo "</ul></div>";
    }
}