<?php

namespace App\Libs;

class Paginator{
    public $limit=15;
    public $pager=1;
    private $recordsSize=0;
    public $pagers=0;
    public $start=0;

    public function __construct($pager=0)
    {
        $this->pager = ($pager==0) ? 1 : $pager;
    }

    public function setLimit($limitPaginator){
        $this->limit = $limitPaginator;
        $this->start=($this->pager * $this->limit) - $this->limit;

    }

    public  function sizeRecords($size) {
        $this->recordsSize =  $size;
        $this->pagers = ceil($this->recordsSize / $this->limit);
    }

    public function paginator(){
        return [
            "pages" => $this->pagers,
            "limit" => $this->limit,
            "current" => $this->pager
        ];
    } 
}