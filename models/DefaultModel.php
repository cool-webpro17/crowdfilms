<?php

namespace app\models;
use ReflectionClass;

use \yii\helpers\Url as Url;
use \yii\helpers\Json as Json;
use \yii\base\Exception as Exception;
use \app\components\exceptions\ModelException as ModelException;
use app\models\Question as Question;
use app\models\PricingFormula as PricingFormula;
use app\models\CrowdfundingMovieRentalValue as CrowdfundingMovieRentalValue;
use app\models\FixedValues as FixedValues;

use Yii;

/**
 * This is the parent model class all api models
 */
class DefaultModel extends \yii\db\ActiveRecord
{
	public static $fileModels = [
        'fixed_values_file' => 'app\models\FixedValues',
        'pricings_file' => 'app\models\PricingFormula',
    ];

	public function trySave(){

        if($this->validate() && $this->save()){
            return true;
            
        } else {
           throw new ModelException("Model save error", $this->getErrors());
           return false;
        }
    }

    public function upload($attribute)
    {
        $csv_path = Yii::getAlias('@CSVPath');
        if ($this->validate()) {
            $this->{$attribute}->saveAs($csv_path . $this->{$attribute}->baseName . '.' . $this->{$attribute}->extension);
            $model = static::$fileModels[$attribute];
           
            return $model::updateContentFromFile($this->{$attribute}->baseName . '.' . $this->{$attribute}->extension);
        } else {
            return false;
        }
    }

    public static function updateContentFromFile($filename)
    {
        $csvContent = Yii::$app->utils->parseCSV($filename, static::tableName());

        $transaction = Yii::$app->db->beginTransaction();

        try{
            
            static::DeleteAll();

            static::insertRecordsCSV($csvContent);

            static::updateRecordsCSV($csvContent);

            $transaction->commit();

            return true;

        } catch(ModelException $e){

            $transaction->rollBack();

            $this->addErrors($e->getMessage());

            return false;
        }

    }

    public static function insertRecordsCSV($csvContent)
    {
        for($i = 0; $i < count($csvContent); $i++)
        {
            $record = $csvContent[$i];
            $modelName = static::className();
            $model = new $modelName;
            $model->setAttributesCSV($record);

            if(!$model->save())
            {
                var_dump($record);
                var_dump($model->attributes);
                die(var_dump($model->getErrors()));
            }
        }
    }
    
    public static function updateRecordsCSV($csvContent) {}

    public function setAttributesCSV($attributes)
    {
        foreach ($attributes as $key => $value) {

            if(!$key)
                continue;
            
            $type = $this->getTableSchema()->columns[$key]->type;
            
            switch($type)
            {
                case 'bigint':
                    $this->{$key} = intval($value);
                    break;
                case 'double':
                case 'float':
                    $this->{$key} = floatval($value);
                    break;
                case 'string':
                default:
                    $this->{$key} = (string)$value;
                    break;

            }
        }
    }
	
	public static function getPk()
    {
        return null;
    }

}