<?php
namespace app\admins\controller;

use think\Db;
use think\App;

class Index extends Common
{
    
    public function index()
    {
        $path = __DIR__ . '/../../../config/mysystem.php';
		$siteinfo = include $path;
        $this->assign('siteinfo', $siteinfo);
        return $this->fetch();
    }


    public function welcome()
    {
       
        $os = PHP_OS;
        $os = php_uname();
        $phpv = phpversion();
        $mysql = Db::query("select VERSION() as version");  // 查询mysql版本号
        $mysql = $mysql[0]['version']; 
        $mysql = empty($mysql)? '位置类型' : $mysql; 
        $tpv = App::VERSION; 
        if(ini_get('file_uploads')){
            $uploadSize = ini_get('upload_max_filesize');
        }else{
            $uploadSize = '禁止上传';
        }
        $phpmod = php_sapi_name();
        $free_space = disk_free_space('/');
        $execution_time = ini_get('max_execution_time');
        $sysos = $_SERVER["SERVER_SOFTWARE"];//获取php版本及运行环境

        $this->assign('version', 'V1.1.1');
        $this->assign('os', $os);
        $this->assign('phpv', $phpv);
        $this->assign('mysql', $mysql);
        $this->assign('tpv', $tpv);
        $this->assign('uploadSize', $uploadSize);
        $this->assign('phpmod', $phpmod);
        $this->assign('free_space', get_disk_total($free_space));
        $this->assign('execution_time', $execution_time);
        $this->assign('sysos', $sysos);
    	return $this->fetch();
    }


    public function export()
    {
       
    }

}


