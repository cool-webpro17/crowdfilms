<?php

namespace app\components;
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use app\models\PricingFormula;
use app\models\Question;
use app\models\User;
use app\models\CrowdfundingMovieRentalValue as CrowdfundingMovieRentalValue;
use yii\helpers\Json;
 
class Utils extends Component
{
    public function parseCSV($name, $model = '')
    {
        ini_set('auto_detect_line_endings',TRUE);

        $csv_path = Yii::getAlias('@CSVPath');

        $output = [];
        
        if (($handle = fopen($csv_path . $name, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row = $this->parseCSVRowModel($data, $model);
                $output[] = $row;
            }
            fclose($handle);
        }
        array_splice($output, 0, 1);
        return $output;
    }

    public function parseCSVRow($row)
    {
        $result = [];
        $num = count($row);
        for ($i=0; $i < $num; $i++) {
            $result[] = $row[$i];
        }

        return $result;
    }

    public function parseCSVRowModel($row, $model)
    {
        $model = 'app\models\\' . $model;
        $attributes = $model::Instance()->getAttributes();
        unset($attributes[$model::getPk()]);
        $fields = array_keys($attributes);

        $result = [];
        $num = count($row);
        for ($i = 0; $i < $num; $i++) {
            $result[$fields[$i]] = $row[$i];
        }

        return $result;
    }

    public function getUploadCSVAttributes()
    {
        $upload_options =  Yii::$app->params['adminTools']['upload'];

        foreach($upload_options as $option)
        {
            $result[] = $option['attribute'];
        }

        return $result;
    }

    public function setCookie($name, $value)
    {
        $cookies = Yii::$app->response->cookies;

        // add a new cookie to the response to be sent
        $cookies->add(new \yii\web\Cookie([
            'name' => $name,
            'value' => $value,
            'httpOnly' => false
        ]));

        return $cookies;
    }

    public function handleCookies()
    {
        $cookies = Yii::$app->request->cookies;

        $user_cookie = isset($cookies['user_id']) ? $cookies['user_id'] : null;
        $session_cookie = isset($cookies['session_id']) ? $cookies['session_id'] : null;

        $cookies = ['user_id' => $user_cookie, 'session_id' => $session_cookie];

        if(!$user_cookie)
        {
            $user = new User();
            $user->save();

            Yii::$app->utils->setCookie('user_id', $user->getPrimaryKey());
            $cookies['user_id'] = $user->getPrimaryKey();
        }

        if(!$session_cookie)
        {
            Yii::$app->utils->setCookie('session_id', Yii::$app->session->getId());
            $cookies['session_id'] = Yii::$app->session->getId();
        }

        return $cookies;
    }

    public function mapArray($array)
    {
		$result = [];
        foreach ($array as $key => $value) {
            $result[$value->value_id] = $value->value;
        }
        
        return $result;
    }

    public function loadMailchimpClasses(){
        $mailchimp = Yii::getAlias("@vendor/Mailchimp/Mailchimp.php");
     
        require_once($mailchimp);
    }    

}

