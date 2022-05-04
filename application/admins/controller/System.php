<?php
namespace app\admins\controller;

class System extends Common
{
    public function website()
    {
        $path = __DIR__ . '/../../../config/mysystem.php';
		$siteinfo = include $path;

        if(request()->isPost()){
            $config['site_name'] = request()->param('site_name');
            $config['site_keywords'] = request()->param('site_keywords');
            $config['site_description'] = request()->param('site_description');
            $config['allow_register'] = request()->param('allow_register');
            $config['allow_login'] = request()->param('allow_login');
            $config['allow_comment'] = request()->param('allow_comment');
            $config['show_commentlist'] = request()->param('show_commentlist');
            $config['site_copyright'] = request()->param('site_copyright');
            $config['site_beian'] = request()->param('site_beian');
            $config['site_version'] = request()->param('site_version');
            
            $data = "<?php\r\nreturn " . var_export($config, true) . ";\r\n?>";

            if (file_put_contents($path, $data)) {
                
				$response = array('status'=>200, 'data'=>'', 'msg'=>'修改成功');
			} else {
				$response = array('status'=>400, 'data'=>'', 'msg'=>'修改失败');
			}

            return json($response);
        }else{
            
            $this->assign('siteinfo', $siteinfo);
            return $this->fetch();
        }
    }
}
?>