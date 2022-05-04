<?php
namespace app\admins\controller;

use think\Controller;

class Common extends controller
{

	protected function initialize()
    {
    	if(!session('admin_id')){
			$this->redirect('login/login');
    	}
        
    }
	
    public function _empty($name)
    {
        //return $name;
    }	

    // 后台layui上传图片的处理方法
    public function upload()
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
    }
}

?>
