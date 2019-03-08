<?php

namespace app\models;

use Yii;

class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'project_status', 'live_status', 'created_at'], 'required'],
            [['user_id'], 'integer'],
            [['project_status',
                'contact_id',
                'project_title',
                'live_status',
                'contact_name',
                'contact_email',
                'contact_phone',
                'contact_comment',
                'project_description',
                'project_status',
                'total_price',
                'price_description',
                'payment_due',
                'already_paid'], 'string'],
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
            'project_status' => 'Project Status',
            'created_at' => 'Timestamp'
        ];
    }

    public function scopes() {
        return array(
            'created_at' => array('order' => 'status DESC'),
        );
    }
}
