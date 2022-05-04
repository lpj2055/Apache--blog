<?php
namespace app\index\controller;


class Search extends Common
{
    public function index()
    {
        $query = [];
        $search = '';
        $where[] = ['a.is_del', '=', 0];
        if(request()->isPost() && request()->param('keyboard')){
            $search = request()->param('keyboard');
            $where[] = ['a.title', 'like', '%'.$search.'%'];
            $query['query'] = ['title' => $search];
        }
        $news = db('news')
            ->alias('a')
            ->field('a.*, b.cate_name')
            ->leftJoin('news_category b', 'a.cid=b.id')
            ->where($where)->order('a.id', 'desc')->paginate(10, false, $query); 
        
        // 点击排行
        $rank = db('news')->where(['is_del'=>0])->order('views', 'desc')->limit(8)->select();
        $this->assign('rank', $rank);

        // 本栏推荐
        $recommend = db('news')->where(['is_del'=>0, 'is_recommend'=>1])->where('pic', '<>', '')->order('id', 'desc')->limit(7)->select();

        $this->assign('search', $search);
        $this->assign('news', $news);
        $this->assign('rank', $rank);
        $this->assign('recommend', $recommend);
        return $this->fetch();
    }
}

?>