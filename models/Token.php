<?php

namespace app\models;

use Yii;

class Token extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['access_token', 'refresh_token'], 'required'],
            [['access_token', 'refresh_token'], 'string'],
        ];
    }

    public static function primaryKey()
    {
        return ['refresh_token'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'access_token' => 'Access Token',
            'refresh_token' => 'Refresh Token'
        ];
    }

}
