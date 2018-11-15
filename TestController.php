<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class TestController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionAdd()
    {
        return $this->render('add');
    }

    public function actionDoadd()
    {
        $post = Yii::$app->request->post();

        $sql = "insert into test values (null,'$post[title]','$post[a]','$post[b]','$post[true]')";
        $res = Yii::$app->db->createCommand($sql)->execute();

        if ($res){
            return $this->redirect(array('test/add'));
        }
    }
}