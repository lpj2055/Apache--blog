<?php
namespace app\admins\controller;

use think\captcha\Captcha;
use think\Controller;
use think\Db;


class Login extends Controller
{
    public function login(){
        return $this->fetch();
    }

    public function doLogin(){
        $username = trim(request()->param('username'));
        $password = trim(request()->param('password'));
        $verify = request()->param('verify');

        if(!$username){
            $this->error('用户名不能为空');
        }
        if(!$password){
            $this->error('密码名不能为空');
        }

        $captcha = new Captcha();
        if( !$captcha->check($verify))
        {
            $this->error('验证失败');
        }

        $user = Db::name('admin')->where('is_del', 0)->where('is_locked', 0)->where('user_name', $username)->find();
        if(!$user){
            $this->error('账号或密码错误');
        }
        if($user['password'] != md5($password)){
            $this->error('账号或密码错误');
        }
        session('admin_id', $user['id']);
        session('admin_name', $user['user_name']);
        $this->success('正在登录...', 'Index/index');
    }

    public function logOut(){
        //session(null);
        session('admin_id', null);
        session('admin_name', null);
        $this->success('已退出', 'Login/login');
    }

    public function verify()
    {
        $config =    [
            'fontSize'    =>    30,   
            'length'      =>    5,   
            'useCurve'    =>    false, 
            'reset' => true
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();    
    }
}
?>