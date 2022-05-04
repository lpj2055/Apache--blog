<?php
namespace app\index\controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;


class Index extends Common
{
    public function index()
    {
        // 首页轮播图
        $where['is_del'] = 0;
        $where['is_show'] = 1;
        $where['category'] = '首页轮播图';
        $banners = db('banner')->where($where)->order('id', 'desc')->limit(4)->select();


        // 首页banner副图
        $headline = db('banner')->where(['is_del'=>0, 'is_show'=>1, 'category'=>'首页banner副图'])->order('id', 'desc')->limit(2)->select();

        // 分类文章
        $news_category = db('news_category')->where(['is_del'=>0])->order('sort', 'desc')->order('id', 'asc')->select();
        if($news_category){
            // tuijian-news
            foreach($news_category as $key => $val){
                $recommend = db('news')->where(['is_del'=>0, 'cid'=>$val['id'], 'is_recommend'=>1])->where('pic', '<>', '')->order('id', 'desc')->limit(2)->select();
                $news_category[$key]['news_recommend'] = $recommend;

                $news = db('news')->where(['is_del'=>0, 'cid'=>$val['id']])->order('id', 'desc')->limit(5)->select();
                $news_category[$key]['news_list'] = $news;
            }
        }

        // 最新博文
        $blog_new = db('news')->alias('a')->field('a.*, b.cate_name, c.nickname, c.photo')
        ->leftJoin('news_category b', 'a.cid=b.id')
        ->leftJoin('admin c', 'a.aid=c.id')
        ->where(['a.is_del'=>0])->order('a.is_top', 'desc')->order('a.id', 'desc')->limit(10)->select();

        // 网站公告
        $notice = db('notice')->where(['is_del'=>0])->order('id', 'desc')->limit(4)->select();

        // 点击排行
        $rank = db('news')->where(['is_del'=>0])->order('views', 'desc')->limit(8)->select();
        $this->assign('rank', $rank);

        // 站长推荐
        $recommend = db('news')->where(['is_del'=>0, 'is_recommend'=>1])->where('pic', '<>', '')->order('id', 'desc')->limit(7)->select();

        // 友情链接
        $links = db('links')->where(['is_del'=>0, 'is_show'=>1])->order('sort', 'desc')->order('id', 'desc')->select();

        $this->assign('banner', $banners);
        $this->assign('headline', $headline);
        $this->assign('news_category', $news_category);
        $this->assign('blog_new', $blog_new);
        $this->assign('links', $links);
        $this->assign('notice', $notice);
        $this->assign('rank', $rank);
        $this->assign('recommend', $recommend);
        return $this->fetch();
    }

    public function daohang()
    {
        return $this->fetch();
    }

    public function about()
    {
        $history = db('news')->where(['is_del'=>0])->where('pic', '<>', '')->orderRaw('rand()')->limit(8)->select();
        $this->assign('history', $history);
        return $this->fetch();
    }

    public function time()
    {
        // 往期文章
        $rank = db('news')->where(['is_del'=>0])->order('id', 'desc')->limit(200)->select();
        $this->assign('rank', $rank);
        return $this->fetch();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }


    public function export()
    {
        $data = [
            ['title1' => '111', 'title2' => '222'],
            ['title1' => '111', 'title2' => '222'],
            ['title1' => '111', 'title2' => '222']
        ];
        $title = ['第一行标题', '第二行标题'];

     
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // 方法一，使用 setCellValueByColumnAndRow
        //表头
        //设置单元格内容
        foreach ($title as $key => $value) {
            // 单元格内容写入
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $value);
        }
        $row = 2; // 从第二行开始
        foreach ($data as $item) {
            $column = 1;
            foreach ($item as $value) {
                // 单元格内容写入
                $sheet->setCellValueByColumnAndRow($column, $row, $value);
                $column++;
            }
            $row++;
        }

        // 方法二，使用 setCellValue
        //表头
        //设置单元格内容
        $titCol = 'A';
        foreach ($title as $key => $value) {
            // 单元格内容写入
            $sheet->setCellValue($titCol . '1', $value);
            $titCol++;
        }
        $row = 2; // 从第二行开始
        foreach ($data as $item) {
            $dataCol = 'A';
            foreach ($item as $value) {
                // 单元格内容写入
                $sheet->setCellValue($dataCol . $row, $value);
                $dataCol++;
            }
            $row++;
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;

        
    }
}
