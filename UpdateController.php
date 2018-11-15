<?php
/**
 * @Author: Marte
 * @Date:   2018-10-25 14:43:33
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-10-26 09:10:12
 */
namespace app\controllers;
use Yii;
use yii\web\Controller;
class UpdateController extends Controller{
    public $enableCsrfValidation = false;
    public function actionIndex(){
        return $this->render("upload");
    }
    public function actionUploadfile(){
        $img=$_FILES['img'];
        //取到是什么类型的图片
        $info=explode("/",$img['type']);
        $name="file.".$info['1'];
        //创建image文件在web文件中 在linux下赋权限777
        move_uploaded_file($img['tmp_name'],"image/".$name);
        $token=$this->gettoken();
        $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$token['access_token']."&type=image";

        $data=curl::_post($url,array(),array('media'=>"/phpstudy/www/basic/web/image/$name"));

        $data=json_decode($data,true);
        //print_r($data);die;
        // $data=array(
        //     'media_id'=>$data['media_id'],
        //     'addtime'=>time(),
        // );
        $addtime=time();
        $media_id=$data['media_id'];
        Yii::$app->db->createCommand()->insert("media",['media_id'=>$media_id,'addtime'=>$addtime])->execute();

    }
     public function gettoken(){
        $token=@file_get_contents('token.txt');
        $token=json_decode($token,true);
        if(!isset($token['time']) || time() > $token['time']+7200){
            $data=Yii::$app->params['wechat'];
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$data['appid']."&secret=".$data['appsecret'];
            $data=Curl::_get($url);
           // var_dump($data);die;
            $json_token=json_decode($data,true);
            $json_token['time']=time();
        //var_dump($token);die;
            file_put_contents('token.txt',json_encode($json_token));
            $token=$json_token;
       }
        return $token;
    }
}