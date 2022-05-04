<?php
namespace app\index\controller;

use think\Controller;

class Login extends Controller
{
	public function initialize()
	{
		
	}

	public function _empty($name)
    {
        
    }

    // 显示登录页面
	public function login()
	{
		// 站点配置信息
        $siteconfig = config('mysystem.');
		if($siteconfig['allow_login'] != 1){
			return "登录功能已关闭";
		}
		return $this->fetch();
	}

	public function reg()
	{
		// 站点配置信息
        $siteconfig = config('mysystem.');
		if($siteconfig['allow_register'] != 1){
			return "注册功能已关闭";
		}
		return $this->fetch();
	}

    public function doLogin()
    {
        $user_name = request()->param('user_name');
		$password = request()->param('password');
		if(!$user_name || !$password){
			$this->error('用户名或密码不能为空');
		}

		$user = db('user')->where(['user_name'=>$user_name]) -> find();
		if(!$user){
			$this->error('用户名或密码错误');
		}
		if($user['password'] != md5($password)){
			$this->error('用户名或密码错误');
		}
		if($user['is_locked'] == 1){
			$this->error('账号已被锁定，无法登录');
		}

		session("user_name", $user_name);
		session("user_level", $user['user_level']);
		session("uid", $user['id']);

		$this->success('正在登录...', 'Index/index');
    }

    // 显示注册页面，与登录共用同一个
	public function register()
	{
		return $this->fetch('login');
	}

    public function doRegister()
	{

		$user_name = request()->param('user_name');
		$password = request()->param('password');
		$email = request()->param('email');
		$nickname = request()->param('nickname');
		if(!$user_name || !$password){
			$this->error('用户名或密码不能为空');
		}

        if($user_name== strtolower('admin') || $user_name == strtolower('superadmin')|| $user_name == strtolower('manager')){
			$this->error('该用户名已被使用...');
		}

		// 检查用户名重复性
		$user = db('user')->where(array('user_name'=>$user_name))->find();
		if($user){
			$this->error('该用户名已被使用...');
		}

		$data = array(
			'user_name' => $user_name,
			'password' => md5($password),
			'email' => $email,
			'nickname' => $nickname,
			//'phone' => '1231231',
			'created_at' => time()
		);

		$uid = db('user')->insertGetId($data);

		if($uid){
			session("user_name", $user_name);
			session("uid", $uid);
			session("user_level", 1);
			$this->success('注册成功...', 'Index/index');
		}else{
			$this->error('注册失败...');
		}
	}


    // 用户退出
    public function logout(){
        // 删除（当前作用域）
        session('user_name', null);
		session("uid", null);

        $this->success('已退出', 'index/index');
    } 

}
?>