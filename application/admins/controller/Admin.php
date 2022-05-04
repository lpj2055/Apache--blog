<?php
namespace app\admins\controller;
use think\Db;

class Admin extends Common
{
    // 管理员列表
    public function index()
    {
        $phone = '';
        $where = array('is_del'=>0);
        if(request()->isPost() && request()->param('phone')){
            $where['phone'] = $phone = request()->param('phone');
        }
        $adms = db('admin') -> where($where)->where('id','<>',1)->order('id', 'desc')->paginate(10);

        $this->assign('adms', $adms);
        $this->assign('phone', $phone);
        return $this->fetch();
        
    }

    // 添加管理员
    public function add()
    {
        if(request()->isPost()){
            $user_name = request()->param('user_name');
            $phone = request()->param('phone');
            $nickname = request()->param('nickname');
            $pass = request()->param('pass');
            $repass = request()->param('repass');
            $is_locked = request()->param('is_locked');
            $photo = request()->param('thumb_pic');

            if($pass != $repass){
                $response = array('status'=>400, 'data'=>'', 'msg'=>'两次密码不一致');
                return json($response);
            }

            $user = db('admin')->where(['is_del'=>0, 'user_name'=>$user_name])->find();
            if($user){
                $response = array('status'=>400, 'data'=>'', 'msg'=>'登录名已存在');
                return json($response);
            }

            $data = [
                'user_name' => $user_name,
                'phone' => $phone,
                'password' => md5($pass),
                'nickname' => $nickname,
                'photo' => $photo,
                'is_locked' => $is_locked,
                'created_time' => time()
            ];
            $userId = db('admin')->insertGetId($data);

            if($userId){
                $response = array('status'=>200, 'data'=>'', 'msg'=>'添加成功');
            }else{
                $response = array('status'=>400, 'data'=>'', 'msg'=>'添加失败');
            }

            return json($response);
        }else{
            return $this->fetch();
        }
    }

    public function edit()
    {
        if(request()->isAjax()){
            $aid = request()->param('aid');
            $user_name = request()->param('user_name');
            $phone = request()->param('phone');
            $nickname = request()->param('nickname');
            $pass = request()->param('pass');
            $repass = request()->param('repass');
            $is_locked = request()->param('is_locked');
            $photo = request()->param('thumb_pic');

            $admin = db('admin')->where('user_name', $user_name)->where('id', '<>', $aid)->find();
            if($admin){
                $response = array('status'=>400, 'data'=>'', 'msg'=>'用户名已被占用');
                return json($response);
            }
            if($pass && ($pass != $repass)){
                $response = array('status'=>400, 'data'=>'', 'msg'=>'两次密码不一致');
                return json($response);
            }
            if($pass && (strlen($pass)<6 || strlen($pass)>12)){
                $response = array('status'=>400, 'data'=>'', 'msg'=>'密码必须6到12位');
                return json($response);
            }

            $data['id'] = $aid;
            $data['user_name'] = $user_name;
            $data['phone'] = $phone;
            $data['nickname'] = $nickname;
            $data['photo'] = $photo;
            $data['is_locked'] = $is_locked;
            if($pass && $pass==$repass){
                $data['password'] = md5($pass);
            }
            $nid = db('admin')->update($data);
            if($nid !== false){
                $response = array(
                    'status' => 200,
                    'msg' => '修改成功',
                    'data' => [],
                );
            }else{
                $response = array(
                    'status' => 400,
                    'msg' => '修改失败',
                    'data' => [],
                );
            }
            return json($response);

        }else{
            $id = request()->param('id');
            $admin = db('admin')->where('id', $id)->find();
            $this->assign('admin', $admin);
       
            return $this->fetch();
        }
    }

    // 修改头像
    public function photo()
    {
        if(request()->isAjax()){
            $aid = request()->param('aid');
            $photo = request()->param('thumb_pic');
            $data['id'] = $aid;
            $data['photo'] = $photo;
            $nid = db('admin')->update($data);
            if($nid !== false){
                $response = array(
                    'status' => 200,
                    'msg' => '修改成功',
                    'data' => [],
                );
            }else{
                $response = array(
                    'status' => 400,
                    'msg' => '修改失败',
                    'data' => [],
                );
            }
            return json($response);
        }else{
            $id = session('admin_id');
            $admin = db('admin')->where('id', $id)->find();
            $this->assign('admin', $admin);
       
            return $this->fetch();
        }
    }

    // 修改管理员状态
    public function chgStatus()
    {
        if(request()->isAjax()){
            $id = request()->param('id');
            $admin = db('admin')->where('id', $id)->find();
            $is_locked = $admin['is_locked'] ? 0 : 1;
            $nid = db('admin')->update(['is_locked'=>$is_locked, 'id'=>$id]);
            if($nid){
                $response = array(
                    'status' => 200,
                    'msg' => '修改成功',
                    'data' => [],
                );
            }else{
                $response = array(
                    'status' => 400,
                    'msg' => '修改失败',
                    'data' => [],
                );
            }
            return json($response);
        }else{
            $this->error('您访问的页面不存在');
        }
    }
    // 修改管理员信息

    // 删除管理员
    public function del()
    {
        if(request()->isAjax()){
            $admin_id = request()->param('admin_id');
            $nid = db('admin')->where('id', $admin_id)->data(['is_del'=>1])->update();
            if($nid){
                $response = array(
                    'status' => 200,
                    'msg' => '删除成功',
                    'data' => [],
                );
            }else{
                $response = array(
                    'status' => 400,
                    'msg' => '删除失败',
                    'data' => [],
                );
            }
            return json($response);
        }else{
            $this->error('您访问的页面不存在');
        }
    }   
    
    // 批量删管理员
    public function delAll()
    {
        if(request()->isAjax()){
            $admin_ids = request()->param('admin_ids');
            $admin_ids = $admin_ids ? array_values($admin_ids) : 0;
         
            $nid = db('admin')
            ->where('id','in',$admin_ids)
            ->data(['is_del' => 1])
            ->update();

            if($nid){
                $response = array(
                    'status' => 200,
                    'msg' => '删除成功',
                    'data' => [],
                );
            }else{
                $response = array(
                    'status' => 400,
                    'msg' => '删除失败',
                    'data' => [],
                );
            }
            return json($response);
        }else{
            $this->error('您访问的页面不存在');
        }
    }

    // 修改个人信息
    public function myinfo()
    {
        if(request()->isAjax()){
            $pwd1 = request()->param('pwd1');
            $pwd2 = request()->param('pwd2');
            if($pwd1 != $pwd2){
                $this->error('两次密码不一致');
            }
            if(mb_strlen($pwd1,"utf-8")<6 || mb_strlen($pwd1,"utf-8")>12){
                $this->error('密码必须6到12位');
            }
            $data = array(
                'id' => session('admin_id'),
                'password' => md5($pwd1)
            );
            $nid = db('admin')->update($data);
            if($nid !== false){
                $response = array(
                    'status' => 200,
                    'msg' => '修改成功',
                    'data' => [],
                );
            }else{
                $response = array(
                    'status' => 400,
                    'msg' => '修改失败',
                    'data' => [],
                );
            }
            return json($response);
        }else{
            $aid = session('admin_id');
            if(!$aid){
                $this->error('请先登录');
            }
            $admin = db('admin')->where('id', $aid)->find();
            $this->assign('admin', $admin);
            return $this->fetch();
        }
    }

   
}