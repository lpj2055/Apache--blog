<?php

namespace app\admins\controller;

use think\Controller;
use think\Request;

class Comment extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $commentList = db('comment')->where(['is_del'=>0])->order('id', 'desc')->paginate(10, false);
        $this->assign('commentList', $commentList);
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
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
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
            $cid = request()->param('cid');
            $nid = db('comment')->where('id', $cid)->data(['is_del'=>1])->update();
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
            $ids = request()->param('ids');
            $ids = $ids ? array_values($ids) : 0;
         
            $nid = db('comment')->where('id','in',$ids)->data(['is_del' => 1])->update();

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

    public function show()
    {
        if(request()->isAjax()){
            $cid = request()->param('cid');
            $show = request()->param('show');
            $is_show = $show ? 0 : 1;
            $nid = db('comment')->where('id', $cid)->data(['is_show'=>$is_show])->update();
            if($nid){
                $response = array(
                    'status' => 200,
                    'msg' => '操作成功',
                    'data' => [],
                );
            }else{
                $response = array(
                    'status' => 400,
                    'msg' => '操作失败',
                    'data' => [],
                );
            }
            return json($response);
        }else{
            $this->error('您访问的页面不存在');
        }
    }

    public function showAll()
    {
        if(request()->isAjax()){
            $ids = request()->param('ids');
            $ids = $ids ? array_values($ids) : 0;
         
            $nid = db('comment')->where('id','in',$ids)->data(['is_show' => 1])->update();

            if($nid !== false ){
                $response = array(
                    'status' => 200,
                    'msg' => '显示成功',
                    'data' => [],
                );
            }else{
                $response = array(
                    'status' => 400,
                    'msg' => '显示失败',
                    'data' => [],
                );
            }
            return json($response);
        }else{
            $this->error('您访问的页面不存在');
        }
    }
	
  
	
}
