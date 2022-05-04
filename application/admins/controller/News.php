<?php
namespace app\admins\controller;


class News extends Common
{
    public function index()
    {
        $query = [];
        $search = '';
        $where[] = ['a.is_del', '=', 0];
        if( request()->param('search')){
            $search = request()->param('search');
            $where[] = ['a.title', 'like', '%'.$search.'%'];
            $query['query'] = ['title' => $search];
        }
        $news = db('news')
            ->alias('a')
            ->field('a.*, b.cate_name, c.nickname')
            ->leftJoin('news_category b', 'a.cid=b.id')
            ->leftJoin('admin c', 'a.aid=c.id')
            ->where($where)->order('a.id', 'desc')->paginate(10, false, $query); 
        
        $this->assign('search', $search);
        $this->assign('news', $news);

        return $this->fetch();
    }

    public function add()
    {
        if(request()->isPost()){
            $title = request()->param('title');
            $cid = request()->param('cid');
            $read_level = request()->param('read_level');
            $summary = request()->param('summary');
            $pic = request()->param('thumb_pic');
            $views = request()->param('views');
            $is_recommend = request()->param('is_recommend');
            $is_top = request()->param('is_top');
            $content = request()->param('content');
            $aid = session('admin_id');

            $data = [
                'title' => $title,
                'cid' => $cid,
                'read_level' => $read_level,
                'summary' => $summary,
                'pic' => $pic,
                'views' => $views,
                'is_recommend' => $is_recommend,
                'is_top' => $is_top,
                'aid' => $aid,
                'content' => $content,
                'create_time' => time()
            ];

            $nId = db('news')->insertGetId($data);
            if($nId){
                $response = array('status'=>200, 'data'=>'', 'msg'=>'添加成功');
            }else{
                $response = array('status'=>400, 'data'=>'', 'msg'=>'添加失败');
            }

            return json($response);
        }else{
            $category = db('news_category')->where('is_del', 0)->order('sort', 'desc')->order('id', 'desc')->select();

            $this->assign('category', $category);
            return $this->fetch();
        }
    }

    public function edit()
    {
        if(request()->isPost()){
            $nid = request()->param('nid');
            $title = request()->param('title');
            $cid = request()->param('cid');
            $read_level = request()->param('read_level');
            $summary = request()->param('summary');
            $pic = request()->param('thumb_pic');
            $views = request()->param('views');
            $is_recommend = request()->param('is_recommend');
            $is_top = request()->param('is_top');
            $content = request()->param('content');

            $data = [
                'id' => $nid,
                'title' => $title,
                'cid' => $cid,
                'read_level' => $read_level,
                'summary' => $summary,
                'pic' => $pic,
                'views' => $views,
                'is_recommend' => $is_recommend,
                'is_top' => $is_top,
                'content' => $content,
                'update_time' => time()
            ];
            $nid = db('news')->update($data);
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
            $news = db('news')->where('id', $id)->find();
            $category = db('news_category')->where('is_del', 0)->order('sort', 'desc')->order('id', 'desc')->select();

            $this->assign('category', $category);
            $this->assign('news', $news);
            return $this->fetch();
        }
    }

    public function del()
    {
        if(request()->isAjax()){
            $news_id = request()->param('news_id');
            $nid = db('news')->where('id', $news_id)->data(['is_del'=>1])->update();
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
            $admin_ids = request()->param('admin_ids');
            $admin_ids = $admin_ids ? array_values($admin_ids) : 0;
         
            $nid = db('news')
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

/*     public function upload()
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $upload_path = config("app.upload_path");
        $info = $file->move($upload_path);
        if($info){
            $response = array(
                'status' => 1,
                'save_name' => $info->getSaveName(),
                'url' => $info->getSaveName(),
                'msg' => 'success'
            );
            return json($response);
        }else{
            // 上传失败获取错误信息
            $response = array(
                'status' => 0,
                'msg' => $file->getError()
            );
            return json($response);
        }
    } */
}
?>