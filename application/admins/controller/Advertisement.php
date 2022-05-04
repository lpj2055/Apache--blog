<?php
namespace app\admins\controller;


class Advertisement extends Common{
    public function index()
    {
        $query = [];
        $title = '';
        $where = array('is_del'=>0);
        if( request()->param('title')){
            $where['title'] = $title = request()->param('title');
            $query['query'] = ['title' => $title];
        }
        $advertisement = db('advertisement')->where($where)->order('id', 'asc')->paginate(10, false, $query); 
        $this->assign('advertisement', $advertisement);
        $this->assign('title', $title);
        return $this->fetch();
    }

    public function chgStatus()
    {
        if(request()->isAjax()){
            $id = request()->param('id');
            $admin = db('advertisement')->where('id', $id)->find();
            $is_show = $admin['is_show'] ? 0 : 1;
            $nid = db('advertisement')->update(['is_show'=>$is_show, 'id'=>$id]);
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
    
    public function edit(){
        if(request()->isAjax()){
            $bid = request()->param('bid');
            $title = request()->param('title');
            $position = request()->param('position');
            $pic = request()->param('thumb_pic');
            $is_show = request()->param('is_show');
            $url = request()->param('url');

            $data = [
                'id' => $bid,
                'title' => $title,
                'url' => $url,
                'position' => $position,
                'pic' => $pic,
                'is_show' => $is_show
            ];
            $nid = db('advertisement')->update($data);
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
            $advertisement = db('advertisement')->where('id', $id)->find();
            $this->assign('banner', $advertisement);
       
            return $this->fetch();
        }
    }    
}