<?php


namespace megaoffice\client\models;


class ColumnSchema
{
    public function __construct($name)
    {
        $this->name = $name;
    }
    public $name;
}