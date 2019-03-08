<?php

namespace app\models;

use Yii;

class ProjectStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status'], 'string'],
        ];
    }

    public static function primaryKey()
    {
        return ['status'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'status' => 'Status',
        ];
    }
}
