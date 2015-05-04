<?php

namespace doavers\chat\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $message
 * @property string $updated_date
 */
class Chat extends \yii\db\ActiveRecord
{
    public $userModel;
    public $userField;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'message'], 'required'],
            [['user_id'], 'integer'],
            [['message'], 'string'],
            [['message','updated_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'message' => 'Message',
            'updated_date' => 'Updated Date',
        ];
    }

    public function getUser() 
    {
        if (isset($this->userModel))
            return $this->hasOne($this->userModel, ['id' => 'user_id']);
        else
            return $this->hasOne(Yii::$app->getUser()->identityClass, ['id' => 'user_id']);
    }

    public static function records() 
    {
        return static::find()->orderBy('id desc')->limit(10)->all();
    }

    public function data() 
    {
        $userField = $this->userField;
        $output = '';
        $models = Chat::records();
        if ($models)
            foreach ($models as $model) 
            {
                if (isset($model->user->$userField)) 
                {
                    $avatar = $model->user->$userField;
                } 
                else
                {
                    $avatar = Yii::getAlias('@web')."/uploads/avatar/default-avatar.gif";
                }
                    
                $output .= '<div class="item">
                <img class="online" alt="user image" src="' . $avatar . '">
                <p class="message">
                    <a class="name" href="#">
                        <small class="text-muted pull-right" style="color:green"><i class="fa fa-clock-o"></i> ' . \kartik\helpers\Enum::timeElapsed($model->updated_date) . '</small>
                        ' . $model->user->username . '
                    </a>
                   ' . $model->message .'
                </p>
            </div>';
            }

        return $output;
    }
}
