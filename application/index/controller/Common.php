<?php
namespace app\index\controller;

use think\Controller;

class Common extends Controller
{
    protected function initialize()
    {
        // 导航的文章分类 
        $header_category = db('news_category')->where(['is_del'=>0])->order('sort', 'desc')->order('id', 'asc')->select();

        // 站点配置信息
        $siteconfig = config('mysystem.');

        // 站点广告信息
        $ad1 = db('advertisement')->where(['is_del'=>0, 'position'=>'首页上部广告位'])->find();
        $ad2 = db('advertisement')->where(['is_del'=>0, 'position'=>'首页右侧边栏广告位'])->find();
        $ad3 = db('advertisement')->where(['is_del'=>0, 'position'=>'文章详情页内左侧广告位'])->find();
        $ad4 = db('advertisement')->where(['is_del'=>0, 'position'=>'文章详情页内边栏广告位'])->find();

        $this->assign('siteconfig', $siteconfig);
        $this->assign('header_category', $header_category);
        $this->assign('ad1', $ad1);
        $this->assign('ad2', $ad2);
        $this->assign('ad3', $ad3);
        $this->assign('ad4', $ad4);
    }
	
	    public function _empty($name)
	    {
	        //return $name;
	    }
}

?>