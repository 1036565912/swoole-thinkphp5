<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return '';
    }

    public function hello($name = 'hha')
    {
        return 'hello,' . $name;
    }
}
