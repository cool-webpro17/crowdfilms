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
class StatusType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_name', 'status_description'], 'required'],
            [['status_name', 'status_description'], 'string'],
        ];
    }

    public static function primaryKey()
    {
        return ['status_name'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'status_name' => 'Status Name',
            'status_description' => 'Status Description',
        ];
    }

    public function scopes() {
        return array(
            'status_name' => array('order' => 'status DESC'),
        );
    }
}
