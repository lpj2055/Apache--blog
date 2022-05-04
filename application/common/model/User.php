<?php
namespace app\common\model;

use think\Model;

class User extends Model
{
    //protected $pk = 'id';
    
    public function news()
    {
        return $this->hasOne('News');
    }
}