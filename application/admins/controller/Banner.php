<?php
namespace app\admins\controller;


class Banner extends Common
{
    public function index()
    {
        $query = [];
        $title = '';
        $where = array('is_del'=>0);
        if( request()->param('title')){
            $where['title'] = $title = request()->param('title');
            $query['query'] = ['title' => $title];
        }
        $banner = db('banner')->where($where)->order('id', 'desc')->paginate(10, false, $query); 
        $this->assign('banner', $banner);
        $this->assign('title', $title);
        return $this->fetch();
    }

    public function chgStatus()
    {
        if(request()->isAjax()){
            $id = request()->param('id');
            $admin = db('banner')->where('id', $id)->find();
            $is_show = $admin['is_show'] ? 0 : 1;
            $nid = db('banner')->update(['is_show'=>$is_show, 'id'=>$id]);
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

    public function add()
    {
        if(request()->isPost()){
            $title = request()->param('title');
            $category = request()->param('category');
            $pic = request()->param('thumb_pic');
            $is_show = request()->param('is_show');
            $url = request()->param('url');

            $data = [
                'pic' => $pic,
                'name' => $pic,
                'title' => $title,
                'url' => $url,
                'category' => $category,
                'created_at' => time(),
                'is_show' => $is_show
            ];
            $bId = db('banner')->insertGetId($data);
            if($bId){
                $response = array('status'=>200, 'data'=>'', 'msg'=>'添加成功');
            }else{
                $response = array('status'=>400, 'data'=>'', 'msg'=>'添加失败');
            }

            return json($response);
        }else{
            return $this->fetch();
        }
    }

    public function edit(){
        if(request()->isAjax()){
            $bid = request()->param('bid');
            $title = request()->param('title');
            $category = request()->param('category');
            $pic = request()->param('thumb_pic');
            $is_show = request()->param('is_show');
            $url = request()->param('url');

            $data = [
                'id' => $bid,
                'name' => $pic,
                'title' => $title,
                'url' => $url,
                'category' => $category,
                'pic' => $pic,
                
                'is_show' => $is_show
            ];
            $nid = db('banner')->update($data);
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
            $banner = db('banner')->where('id', $id)->find();
            $this->assign('banner', $banner);
       
            return $this->fetch();
        }
    }

    public function del()
    {
        if(request()->isAjax()){
            $admin_id = request()->param('admin_id');
            $banner = db('banner')->where('id', $admin_id)->find();
            if($banner){
                @unlink('uploads/' . $banner['pic']);
            }else{
                $response = array(
                    'status' => 400,
                    'msg' => '删除失败',
                    'data' => [],
                );
                return json($response);
            }

            $nid = db('banner')->where('id', $admin_id)->data(['is_del'=>1])->update();
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
         
            $banners = db('banner')->where('id','in',$admin_ids)->select();
            if($banners){
                foreach($banners as $k => $v){
                    @unlink('uploads/' . $v['pic']);
                }
            }else{
                $response = array(
                    'status' => 400,
                    'msg' => '删除失败',
                    'data' => [],
                );
                return json($response);
            }
            $nid = db('banner')
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

    /* public function upload()
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





