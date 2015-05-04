<?php

/**
 * @link https://github.com/doavers/yii2-chat
 * @copyright Copyright (c) 2015 Doavers
 * @license MIT
 */

namespace doavers\chat;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Doavers <doavers@gmail.com>
 */
class ChatJs extends AssetBundle {

    public $sourcePath = '@vendor/doavers/yii2-chat/assets';
    public $js = [
        'js/chat.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
