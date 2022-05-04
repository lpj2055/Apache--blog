<?php
namespace app\admins\controller;


class Notice extends Common
{
    public function index()
    {
        $query = [];
        $search = '';
        $where[] = ['is_del', '=', 0];
        if( request()->param('search')){
            $search = request()->param('search');
            $where[] = ['title', 'like', '%'.$search.'%'];
            $query['query'] = ['title' => $search];
        }

        $notice = db('notice')->where($where)->order('id', 'desc')->paginate(10, false, $query); 
        $this->assign('notice', $notice);
        $this->assign('search', $search);
        return $this->fetch();
    }


    public function add()
    {
        if(request()->isPost()){
            $title = request()->param('title');
            $summary = request()->param('summary');
            $url = request()->param('url');
            $content = request()->param('content');

            $data = [
                'title' => $title,
                'summary' => $summary,
                'url' => $url,
                'content' => $content,
                'create_time' => time()
            ];
            $nId = db('notice')->insertGetId($data);
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


    public function edit()
    {
        if(request()->isPost()){
            $nid = request()->param('nid');
            $title = request()->param('title');
            $summary = request()->param('summary');
            $url = request()->param('url');
            $content = request()->param('content');

            $data = [
                'id' => $nid,
                'title' => $title,
                'summary' => $summary,
                'url' => $url,
                'content' => $content,
                'update_time' => time()
            ];

            $nid = db('notice')->update($data);
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
            $notice = db('notice')->where('id', $id)->find();

            $this->assign('notice', $notice);
            return $this->fetch();
        }
    }


    public function del()
    {
        if(request()->isAjax()){
            $id = request()->param('nid');
            $nid = db('notice')->where('id', $id)->data(['is_del'=>1])->update();
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
         
            $nid = db('notice')
            ->where('id','in',$ids)
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


}


?>