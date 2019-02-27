<?php

namespace app\models;

use Yii;


class CustomerType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value', 'text'], 'string'],
        ];
    }

    public static function primaryKey()
    {
        return ['value'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'value' => 'Value',
            'text' => 'Text'
        ];
    }

    public function scopes() {
        return array(
            'value' => array('order' => 'status DESC'),
        );
    }
}
