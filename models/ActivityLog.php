<?php

namespace app\models;

use Yii;


class ActivityLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'action', 'created_at'], 'required'],
            [['username', 'action', 'created_at'], 'string'],
        ];
    }

    public static function primaryKey()
    {
        return ['username'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'UserName',
            'action' => 'Action',
            'created_at' => 'Timestamp',
        ];
    }
}
