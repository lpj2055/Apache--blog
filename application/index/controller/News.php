<?php
namespace app\index\controller;

use think\captcha\Captcha;

class News extends Common
{
    // 新闻分类列表页
    public function cate(){
        $id = request()->param('id');
        if(!$id){
            $this->error('信息错误');
        }
        $category = db('news_category')->where(['is_del'=>0, 'id'=>$id])->find();

        $query['id'] = $id;
        $news = db('news')->alias('a')->field('a.*, c.nickname, c.photo')->leftJoin('admin c', 'a.aid=c.id')
        ->where(['a.is_del'=>0, 'a.cid'=>$id])->order('a.is_recommend', 'desc')->order('a.id', 'desc')->paginate(10, false, $query); 
        // 获取分页显示
        $page = $news->render();

        // 点击排行
        $rank = db('news')->where(['is_del'=>0])->order('views', 'desc')->limit(8)->select();
        $this->assign('rank', $rank);

        // 本栏推荐
        $recommend = db('news')->where(['is_del'=>0, 'is_recommend'=>1, 'cid'=>$id])->where('pic', '<>', '')->order('id', 'desc')->limit(7)->select();

        $this->assign('page', $page);   
        $this->assign('category', $category);
        $this->assign('news', $news);
        $this->assign('rank', $rank);
        $this->assign('recommend', $recommend);
        
        return $this->fetch();
    }

    // 新闻详情页
    public function detail()
    {
        $id = request()->param('id');
        if(!$id){
            $this->error('信息错误');
        }
        $news = db('news')->alias('a')
            ->field('a.*, b.cate_name, c.nickname, c.photo')
            ->leftJoin('admin c', 'a.aid=c.id')
            ->leftJoin('news_category b', 'a.cid=b.id')->where(['a.id'=>$id, 'a.is_del'=>0])->find();

        if(!$news){
            $this->error('信息错误');
        }

        // 更新阅读数
        db('news')->where('id', $id)->inc('views')->update();

        $commentList = db('comment')->alias('a')->field('a.*, b.user_name, b.nickname')->join('user b', 'a.user_id=b.id')->where(['a.is_del'=>0, 'a.is_show'=>1, 'a.news_id'=>$id])->select();

        // 上一篇
        $pre = db('news')->where(['is_del'=>0])->where('id','<',$news['id'])->order('id', 'desc')->limit(1)->find();

        // 下一篇
        $next = db('news')->where(['is_del'=>0])->where('id','>',$news['id'])->order('id', 'asc')->limit(1)->find();

        // 相关文章
        $relevant = db('news')->where(['is_del'=>0, 'cid'=>$news['cid']])->order('id', 'desc')->limit(10)->select();

        // 点击排行
        $rank = db('news')->where(['is_del'=>0])->order('views', 'desc')->limit(8)->select();
        $this->assign('rank', $rank);

        // 本栏推荐
        $recommend = db('news')->where(['is_del'=>0, 'is_recommend'=>1, 'cid'=>$news['cid']])->where('pic', '<>', '')->order('id', 'desc')->limit(7)->select();

        $this->assign('news', $news);
        $this->assign('commentList', $commentList);
        $this->assign('pre', $pre);
        $this->assign('next', $next);
        $this->assign('relevant', $relevant);
        $this->assign('rank', $rank);
        $this->assign('recommend', $recommend);
        return $this->fetch();
    }

    public function comment()
    {
        if(request()->isAjax()){
            $nid = request()->post('nid');
            $content = request()->post('content');
            $verify = request()->param('verify');
            $user_id = session("uid");

            $captcha = new Captcha();
            if( !$captcha->check($verify))
            {
                $response = array('status'=>400, 'data'=>'', 'msg'=>'验证失败');
                return json($response);
            }

            if(!$user_id){
                $response = array('status'=>400, 'data'=>'', 'msg'=>'您还没有登录');
                return json($response);
            }

            if(!$nid || !$content){
                $response = array('status'=>400, 'data'=>'', 'msg'=>'请输入评论内容');
                return json($response);
            }

            if(cookie('comment_time')){
                $response = array('status'=>400, 'data'=>'', 'msg'=>'您的评论太频繁了，请稍后再试');
                return json($response);
            }

            $data = [
                'content' => $content,
                'news_id' => $nid,
                'user_id' => $user_id,
                'create_time' => time(),
                'is_show' => 0
            ];
            $insId = db('comment')->insertGetId($data);

            // 设置
            cookie('comment_time', 'value', 60);

            if($insId){
                $response = array('status'=>200, 'data'=>'', 'msg'=>'发布成功，等待审核');
            }else{
                $response = array('status'=>400, 'data'=>'', 'msg'=>'发布失败');
            }
            return json($response);

        }else{
            return '';
        }
    }

    // 边栏
    public function getAside()
    {
        // 站长推荐
        $recommend = db('news')->where(['is_del'=>0, 'is_recommend'=>1])->where('pic', "<>", '')->order('id', 'desc')->limit(6)->select();
        $this->assign('recommend', $recommend);

        // 点击排行
        $rank = db('news')->where(['is_del'=>0])->order('views', 'desc')->limit(8)->select();
        $this->assign('rank', $rank);
    }
}

?>