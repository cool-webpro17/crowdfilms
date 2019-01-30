<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "UserAnswers".
 *
 * @property string $user_answer_id
 * @property string $user_id
 * @property string $value
 * @property string $value_id
 *
 * @property User $user
 */
class UserAnswers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'UserAnswers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'value', 'value_id'], 'required'],
            [['user_answer_id', 'user_id'], 'integer'],
            [['value', 'value_id'], 'string'],
            [['user_answer_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'user_id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_answer_id' => 'User Answer ID',
            'user_id' => 'User ID',
            'value' => 'Value',
            'value_id' => 'Value ID',
            'created_at' => 'Timestamp',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    public static function findUserPricingValues($user_id)
    {
        $ids = [
            'crowdFundRevenue',
            'rentalRevenue',
            'filmType',
            'eventType',
            'oneDayChoice',
            'conDaysChoice',
            'conDays',
            'sepDays',
            'hours',
            'filmFormula'
        ];
        
        $rows = static::find()->where(['in','value_id', $ids])->andWhere(['user_id' => $user_id])->all();

        return Yii::$app->utils->mapArray($rows);
    }

    public static function insertRows($user_id, $data)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        try{
        
            foreach ($data as $value_id => $value) {
                $errors[] = $value_id;
                $model = new UserAnswers();
                $model->user_id = $user_id;
                $model->value_id = (string)$value_id;
                $model->value = (string)$value;
                $model->created_at = date('Y-m-d H:i:s');

                if(!$model->save())
                {
                    $errors[] = $model->getErrors();
                }
            }

            $transaction->commit();
        }
        catch(Exception $e){
            $transaction->rollBack();
            $errors[] = $e->getMessage();
        }

        return $errors;
    }
}
