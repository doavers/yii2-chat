<?php

/**
 * @link https://github.com/doavers/yii2-chat
 * @copyright Copyright (c) 2015 Doavers Light <doavers@gmail.com>
 * @license MIT
 */

namespace doavers\chat;

use Yii;
use yii\base\Widget;
use doavers\chat\models\Chat;

/**
 * @author Doavers <doavers@gmail.com>
 */
class ChatRoomWidget extends Widget 
{
    public $sourcePath = '@vendor/doavers/yii2-chat/assets';
    public $css = [
    ];
    public $js = [ // Configured conditionally (source/minified) during init()
        'js/chat.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $models;
    public $url;
    public $userModel;
    public $userField;
    public $model;
    public $loadingImage;

    public function init() 
    {
        $this->model = new Chat();
        if ($this->userModel === NULL) 
        {
            $this->userModel = Yii::$app->getUser()->identityClass;
        }

        $this->model->userModel = $this->userModel;

        if ($this->userField === NULL) 
        {
            $this->userField = 'avatar';
        }

        $this->model->userField = $this->userField;
        Yii::$app->assetManager->publish("@vendor/doavers/yii2-chat/assets/img/loadingAnimation.gif");
        $this->loadingImage = Yii::$app->assetManager->getPublishedUrl("@vendor/doavers/yii2-chat/assets/img/loadingAnimation.gif");

        parent::init();
    }

    public function run() 
    {
        parent::init();
        ChatJs::register($this->view);
        $model = new Chat();
        $model->userModel = $this->userModel;
        $model->userField = $this->userField;
        $data = $model->data();
        return $this->render('index', [
                    'data' => $data,
                    'url' => $this->url,
                    'userModel' => $this->userModel,
                    'userField' => $this->userField,
                    'loading' => $this->loadingImage
        ]);
    }

    public static function sendChat($post) 
    {
        if (isset($post['message']))
            $message = $post['message'];
        if (isset($post['userfield']))
            $userField = $post['userfield'];
        if (isset($post['model']))
            $userModel = $post['model'];
        else
            $userModel = Yii::$app->getUser()->identityClass;

        $model = new \doavers\chat\models\Chat;
        $model->userModel = $userModel;
        if ($userField)
            $model->userField = $userField;

        if ($message) {
            $model->message = $message;
            $model->user_id = Yii::$app->user->id;

            if ($model->save()) {
                echo $model->data();
            } else {
                print_r($model->getErrors());
                exit(0);
            }
        } else {
            echo $model->data();
        }
    }

}
