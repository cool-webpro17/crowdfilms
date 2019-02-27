<?php

namespace app\models;

use Yii;


class FormulaRule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'formula_rule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eventType', 'filmType', 'customerType'], 'required'],
            [['eventType', 'filmType', 'customerType', 'F1ID', 'F2ID', 'F3ID', 'F4ID'], 'string'],
        ];
    }

    public static function primaryKey()
    {
        return ['eventType', 'filmType', 'customerType'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'eventType' => 'Event Type',
            'filmType' => 'Film Type',
            'customerType' => 'Customer Type',
            'F1ID' => 'F1 ID',
            'F2ID' => 'F2 ID',
            'F3ID' => 'F3 ID',
            'F4ID' => 'F4 ID',
        ];
    }

}
