<?php
namespace app\admins\controller;

class Category extends Common
{
    public function index()
    {
        $query = [];
        $where = array('is_del'=>0);
        $category = db('news_category')->where($where)->order('sort', 'desc')->order('id', 'asc')->paginate(10, false, $query); 
        $this->assign('category', $category);
        return $this->fetch();
    }

    public function add()
    {
        if(request()->isAjax()){
            $cate_name = request()->param('cate_name');
            $introduce = request()->param('introduce');
            $sort = request()->param('sort');
            $pic = request()->param('thumb_pic');
            $data = ['cate_name'=>$cate_name, 'introduce'=>$introduce, 'pic' => $pic, 'sort'=>$sort, 'created_at'=>time()];
            $nId = db('news_category')->insertGetId($data);
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
        if(request()->isAjax()){
            $cid = request()->param('cid');
            $cate_name = request()->param('cate_name');
            $introduce = request()->param('introduce');
            $sort = request()->param('sort');
            $pic = request()->param('thumb_pic');
            $data = ['cate_name'=>$cate_name, 'introduce'=>$introduce, 'pic' => $pic, 'sort'=>$sort, 'id'=>$cid, 'updated_at'=>time()];
            $nid = db('news_category')->update($data);
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
            $category = db('news_category')->where('id', $id)->find();
            $this->assign('category', $category);
            return $this->fetch();
        }
    }

    public function del()
    {
        if(request()->isAjax()){
            $cate_id = request()->param('cate_id');
            $nid = db('news_category')->where('id', $cate_id)->data(['is_del'=>1])->update();
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
            $cate_ids = request()->param('cate_ids');
            $cate_ids = $cate_ids ? array_values($cate_ids) : 0;
         
            $nid = db('news_category')
            ->where('id','in',$cate_ids)
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