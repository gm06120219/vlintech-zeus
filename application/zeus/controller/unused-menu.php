<?php
namespace app\zeus\controller;

use think\Controller;

class Menu extends BaseController
{
    public function index()
    {
        return $this->fetch('index');
    }
}
