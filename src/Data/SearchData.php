<?php

namespace App\Data;

class SearchData
{
    /**
     * @var Genre[]
     */
    public $genre = [];

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var null|string
     */
    public $sort;
}