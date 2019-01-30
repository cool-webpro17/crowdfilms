<?php

namespace app\models;
use app\models\PricingFormula;

use Yii;

/**
 * This is the model class for table "FixedValues".
 *
 * @property string $value_id
 * @property string $value
 */
class FixedValues extends DefaultModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'FixedValues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value_id', 'value'], 'required'],
            [['value_id', 'value'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'value_id' => 'Value ID',
            'value' => 'Value',
        ];
    }

    public static function primaryKey()
    {
        return ['value_id'];
    }

    public static function GetEmailTextVar($answers)
    {

    }
}
