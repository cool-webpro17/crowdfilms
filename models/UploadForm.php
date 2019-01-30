<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\DefaultModel;

class UploadForm extends DefaultModel
{
    /**
     * @var UploadedFile
     */
    public $fixed_values_file;
    public $pricings_file;

    public function rules()
    {
        return [
            // [['fixed_values_file'], 'file', 'skipOnEmpty' => true],
            [['pricings_file'], 'file', 'skipOnEmpty' => true],
        ];
    }
    
    
}