<?php
namespace app\index\controller;

class Notice extends Common
{
    public function detail()
    {
        $id = request()->param('id');
        if(!$id){
            $this->error('信息错误');
        }
        $notice = db('notice')->where(['id'=>$id, 'is_del'=>0])->find();

        if(!$notice){
            $this->error('信息错误');
        }

        // 上一篇
        $pre = db('notice')->where(['is_del'=>0])->where('id','<',$id)->order('id', 'desc')->limit(1)->find();

        // 下一篇
        $next = db('notice')->where(['is_del'=>0])->where('id','>',$id)->order('id', 'asc')->limit(1)->find();

        // 点击排行
        $rank = db('news')->where(['is_del'=>0])->order('views', 'desc')->limit(8)->select();
        $this->assign('rank', $rank);

        $this->assign('notice', $notice);
        $this->assign('pre', $pre);
        $this->assign('next', $next);
        $this->assign('rank', $rank);
        return $this->fetch();
    }
}