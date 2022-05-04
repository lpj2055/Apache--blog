<?php
namespace app\index\controller;

use think\Request;

class Error 
{
    public function index(Request $request)
    {
        //根据当前控制器名来判断要执行那个城市的操作
        //$cityName = $request->controller();
    }
    
  

    public function _empty($name)
    {
        //return $name;
    }
}