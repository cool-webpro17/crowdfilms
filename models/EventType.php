<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Admin_User".
 *
 * @property string $username
 * @property string $password
 *
 * @property UserSession[] $userSessions
 */
class EventType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'event_status'], 'required'],
            [['user_id'], 'integer'],
            [['event_status'], 'string'],
        ];
    }

    public static function primaryKey()
    {
        return ['user_id'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'event_status' => 'Event Status',
        ];
    }
}
