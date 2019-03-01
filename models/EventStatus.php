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
class EventStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'event_status', 'created_at'], 'required'],
            [['user_id'], 'integer'],
            [['event_status', 'contact_id'], 'string'],
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
            'created_at' => 'Timestamp'
        ];
    }

    public function scopes() {
        return array(
            'created_at' => array('order' => 'status DESC'),
        );
    }
}
