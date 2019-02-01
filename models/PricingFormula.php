<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "PricingFormula".
 *
 * @property string $pricing_formula_id
 * @property string $kind
 * @property string $type
 * @property int $hours
 * @property double $1DayF1
 * @property double $2DayF1
 * @property double $3DayF1
 * @property double $4DayF1
 * @property double $5DayF1
 * @property double $6DayF1
 * @property double $7DayF1
 * @property double $8DayF1
 * @property double $9DayF1
 * @property double $10DayF1
 * @property double $1DayF2
 * @property double $2DayF2
 * @property double $3DayF2
 * @property double $4DayF2
 * @property double $5DayF2
 * @property double $6DayF2
 * @property double $7DayF2
 * @property double $8DayF2
 * @property double $9DayF2
 * @property double $10DayF2
 * @property double $1DayF3
 * @property double $2DayF3
 * @property double $3DayF3
 * @property double $4DayF3
 * @property double $5DayF3
 * @property double $6DayF3
 * @property double $7DayF3
 * @property double $8DayF3
 * @property double $9DayF3
 * @property double $10DayF3
 * @property double $1DayF4
 * @property double $2DayF4
 * @property double $3DayF4
 * @property double $4DayF4
 * @property double $5DayF4
 * @property double $6DayF4
 * @property double $7DayF4
 * @property double $8DayF4
 * @property double $9DayF4
 * @property double $10DayF4
 * @property double $SepDayCo
 */
class PricingFormula extends DefaultModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'PricingFormula';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kind', 'type', 'hours', '1DayF1', '2DayF1', '3DayF1', '4DayF1', '5DayF1', '6DayF1', '7DayF1', '8DayF1', '9DayF1', '10DayF1', '1DayF2', '2DayF2', '3DayF2', '4DayF2', '5DayF2', '6DayF2', '7DayF2', '8DayF2', '9DayF2', '10DayF2', '1DayF3', '2DayF3', '3DayF3', '4DayF3', '5DayF3', '6DayF3', '7DayF3', '8DayF3', '9DayF3', '10DayF3', '1DayF4', '2DayF4', '3DayF4', '4DayF4', '5DayF4', '6DayF4', '7DayF4', '8DayF4', '9DayF4', '10DayF4', 'SepDayCo'], 'required'],
            [['pricing_formula_id', 'hours'], 'integer'],
            [['1DayF1', '2DayF1', '3DayF1', '4DayF1', '5DayF1', '6DayF1', '7DayF1', '8DayF1', '9DayF1', '10DayF1', '1DayF2', '2DayF2', '3DayF2', '4DayF2', '5DayF2', '6DayF2', '7DayF2', '8DayF2', '9DayF2', '10DayF2', '1DayF3', '2DayF3', '3DayF3', '4DayF3', '5DayF3', '6DayF3', '7DayF3', '8DayF3', '9DayF3', '10DayF3', '1DayF4', '2DayF4', '3DayF4', '4DayF4', '5DayF4', '6DayF4', '7DayF4', '8DayF4', '9DayF4', '10DayF4', 'SepDayCo'], 'number'],
            [['kind', 'type'], 'string', 'max' => 255],
            [['pricing_formula_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pricing_formula_id' => 'Pricing Formula ID',
            'kind' => 'Kind',
            'type' => 'Type',
            'hours' => 'Hours',
            '1DayF1' => '1 Day F1',
            '2DayF1' => '2 Day F1',
            '3DayF1' => '3 Day F1',
            '4DayF1' => '4 Day F1',
            '5DayF1' => '5 Day F1',
            '6DayF1' => '6 Day F1',
            '7DayF1' => '7 Day F1',
            '8DayF1' => '8 Day F1',
            '9DayF1' => '9 Day F1',
            '10DayF1' => '10 Day F1',
            '1DayF2' => '1 Day F2',
            '2DayF2' => '2 Day F2',
            '3DayF2' => '3 Day F2',
            '4DayF2' => '4 Day F2',
            '5DayF2' => '5 Day F2',
            '6DayF2' => '6 Day F2',
            '7DayF2' => '7 Day F2',
            '8DayF2' => '8 Day F2',
            '9DayF2' => '9 Day F2',
            '10DayF2' => '10 Day F2',
            '1DayF3' => '1 Day F3',
            '2DayF3' => '2 Day F3',
            '3DayF3' => '3 Day F3',
            '4DayF3' => '4 Day F3',
            '5DayF3' => '5 Day F3',
            '6DayF3' => '6 Day F3',
            '7DayF3' => '7 Day F3',
            '8DayF3' => '8 Day F3',
            '9DayF3' => '9 Day F3',
            '10DayF3' => '10 Day F3',
            '1DayF4' => '1 Day F4',
            '2DayF4' => '2 Day F4',
            '3DayF4' => '3 Day F4',
            '4DayF4' => '4 Day F4',
            '5DayF4' => '5 Day F4',
            '6DayF4' => '6 Day F4',
            '7DayF4' => '7 Day F4',
            '8DayF4' => '8 Day F4',
            '9DayF4' => '9 Day F4',
            '10DayF4' => '10 Day F4',
            'SepDayCo' => 'Sep Day Co',
        ];
    }

    public static function getPricingFormula($data)
    {
        return static::find()->where(['kind' => $data['filmType'], 'type' => $data['eventType'], 'hours' => $data['hours']])->one();
    }

    public static function calculateTotalPrice($data)
    {
        $formula = static::getPricingFormula($data);
        $filmFormula = $data['filmFormula'];
        $oneDayChoice = $data['oneDayChoice'];
        $conDaysChoice = $data['conDaysChoice'];
        $conDays = $data['conDays'];
        $sepDays = $data['sepDays'];
        $hours = $data['hours'];

        $price = 0;
        $attribute = ($oneDayChoice == 'Yes' ? '1' : ($conDaysChoice == 'Yes' ? $conDays : '1')) . 'Day' . $filmFormula;

        $total = $formula->{$attribute};
        
        if($oneDayChoice != 'Yes' && $conDaysChoice != 'Yes')
        {
            $total = intval($sepDays) * $formula->SepDayCo * $total;
        }

        return $total;
    }

    public static function getPk()
    {
        return 'pricing_formula_id';
    }
}
