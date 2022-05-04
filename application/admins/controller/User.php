<?php

namespace app\admins\controller;

use think\Controller;
use think\Request;

class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $userList = db('user')->where(['is_del'=>0])->order('id', 'desc')->paginate(10, false);
        $this->assign('userList', $userList);
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function add()
    {
        if(request()->isPost()){
            $user_name = request()->param('user_name');
            $nickname = request()->param('nickname');
            $email = request()->param('email');
            $pass = request()->param('pass');
            $repass = request()->param('repass');
            $user_level = request()->param('user_level');
            $is_locked = request()->param('is_locked');

            if(!$pass){
                $response = array('status'=>400, 'data'=>'', 'msg'=>'密码不能为空');
                return json($response);
            }
            $user = db('user')->where('user_name', $user_name)->find();
            if($user){
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

            $data['user_name'] = $user_name;
            $data['email'] = $email;
            $data['nickname'] = $nickname;
            $data['user_level'] = $user_level;
            $data['is_locked'] = $is_locked;
            $data['password'] = md5($pass);
            $data['created_at'] = time();
            
            $nId = db('user')->insertGetId($data);
            if($nId){
                $response = array('status'=>200, 'data'=>'', 'msg'=>'添加成功');
            }else{
                $response = array('status'=>400, 'data'=>'', 'msg'=>'添加失败');
            }
            return json($response);
        }else{
            
            return $this->fetch();
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit()
    {
        if(request()->isPost()){
            $uid = request()->param('uid');
            $nickname = request()->param('nickname');
            $pass = request()->param('pass');
            $repass = request()->param('repass');
            $user_level = request()->param('user_level');
            $is_locked = request()->param('is_locked');

            $user = db('user')->where('nickname', $nickname)->where('id', '<>', $uid)->find();
            if($user){
                $response = array('status'=>400, 'data'=>'', 'msg'=>'昵称已被占用');
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

            $data['id'] = $uid;
            $data['nickname'] = $nickname;
            $data['user_level'] = $user_level;
            $data['is_locked'] = $is_locked;
            if($pass && $pass==$repass){
                $data['password'] = md5($pass);
            }
            $nid = db('user')->update($data);
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
            $user = db('user')->where(['is_del'=>0, 'id'=>$id])->find();

            $this->assign('user', $user);
            return $this->fetch();
        }
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id=0)
    {
        if(request()->isAjax()){
            $user_id = request()->param('user_id');
            $nid = db('user')->where('id', $user_id)->data(['is_del'=>1])->update();
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

    public function delAll()
    {
        if(request()->isAjax()){
            $uids = request()->param('uids');
            $uids = $uids ? array_values($uids) : 0;
         
            $nid = db('user')->where('id','in',$uids)->data(['is_del' => 1])->update();

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

}
