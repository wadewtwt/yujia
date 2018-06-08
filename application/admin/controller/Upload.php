<?php
namespace app\admin\controller;

use think\Controller;
use gmars\qiniu\Qiniu;
use think\Config;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\Loader;
/**
 * 管理员
 */
class Upload extends Controller {
	//图片上传-单张
	public function upload(){
        // 图片的本地路径
        $file = $_FILES['myfile']['tmp_name'];
        // 制作文件名
        $pathInfo = pathinfo($file);
        $ext = $pathInfo['extension'];
        $key = date('Y').'/'.date('m').'/'.date('YmdHis').mt_rand(0,9999).'.'.$ext;
        // 读取配置
        $qiniuConfig = Config::get('qiniu');
        Vendor('gmars.tp5-qiniu.qiniu_driver.autoload');
        // 初始化签权对象
        $auth = new Auth($qiniuConfig['accesskey'], $qiniuConfig['secretkey']);
        // 生成上传Token
        $token = $auth->uploadToken($qiniuConfig['bucket']);
        $uploadMgr = new UploadManager();
        // 1.token 2.加密后的文件名 3.文件所在地方
        list($ret,$err) = $uploadMgr->putFile($token,$key,$file);
        $imgUrl = $qiniuConfig['domain'].'/'.$ret['key'];
        echo $imgUrl;
	}

    // 小程序获取uptoken
    public function uptoken(){
        // 读取配置
        $qiniuConfig = Config::get('qiniu');
        Vendor('gmars.tp5-qiniu.qiniu_driver.autoload');
        // 初始化签权对象
        $auth = new Auth($qiniuConfig['accesskey'], $qiniuConfig['secretkey']);
        // 生成上传Token
        $token = $auth->uploadToken($qiniuConfig['bucket']);
        $info = [
            'uptoken' => $token
        ];
        return json_encode($info);
    }

	 //多图上传(没有)
    public function uploadinfo(){
       $img=getUpload("scommodity");
       if ($img) {
           echo json_encode(array('code'=>0,'msg'=>'上传成功！','data'=>$img));
       }
    }
    //图片删除(没有)
    public function imgdel(){
    	$id=$_GET['imgid'];
    	$rs=M("img")->where("id=$id")->delete();
    	 if ($rs) {
            exit(json_encode(array("err_code" => 200,'err_msg'=>"删除成功")));
        }else{
            exit(json_encode(array("err_code" =>-201,'err_msg'=>"删除失败")));
        }
    }

    public function location(){

        if(isset($_FILES["myfile"])){
            $ret = array();
            $dir = PACH."/Upload/sclass/";//文件目录 自己定义

            file_exists($dir) || (mkdir($dir,0777,true) && chmod($dir,0777));
            if(!is_array($_FILES["myfile"]["name"])) //single file
            {

                $fileName = time().uniqid().'.'.pathinfo($_FILES["myfile"]["name"])['extension'];
//                echo "++".$dir.$fileName."++";die;
                move_uploaded_file($_FILES["myfile"]["tmp_name"],$dir.$fileName);

                $ret['file'] = "/Upload/sclass/".$fileName;//获取的文件路径
            }

            echo json_encode(array('err_code'=>200,'success'=>'success','data'=>$ret['file']));
            // exit;
        }
    }
}