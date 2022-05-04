<?php
namespace app\admins\controller;

class Links extends Common
{
    public function index()
    {
        $links = db('links')->where('is_del', 0)->order('sort', 'desc')->order('id', 'desc')->paginate(10);
		$this->assign('links', $links);
        return $this->fetch();

    }

    public function add()
    {
        if(request()->isPost()){
            $title = request()->param('title');
            $url = request()->param('url');
            $sort = request()->param('sort');
            $is_show = request()->param('is_show');

            $data = [
                'title' => $title,
                'url' => $url,
                'sort' => $sort,
                'is_show' => $is_show,
                'create_time' => time()
            ];
            $userId = db('links')->insertGetId($data);

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
        if(request()->isPost()){
            $id = request()->param('lid');
            $title = request()->param('title');
            $url = request()->param('url');
            $sort = request()->param('sort');
            $is_show = request()->param('is_show');

            $data = [
                'id' => $id,
                'title' => $title,
                'url' => $url,
                'sort' => $sort,
                'is_show' => $is_show,
                'update_time' => time()
            ];
            $nid = db('links')->update($data);
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
            $links = db('links')->where('id', $id)->find();
            $this->assign('links', $links);
       
            return $this->fetch();
        }
    }

    public function del()
    {
        if(request()->isAjax()){
            $id = request()->param('l_id');
            $nid = db('links')->where('id', $id)->data(['is_del'=>1, 'update_time'=>time()])->update();
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
            $links_ids = request()->param('ids');
            $links_ids = $links_ids ? array_values($links_ids) : 0;
         
            $nid = db('links')
            ->where('id','in',$links_ids)
            ->data(['is_del' => 1, 'update_time'=>time()])
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
}
?>