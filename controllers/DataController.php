<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\VarDumper;
use app\models\UserAnswers;
use app\models\EventType;
use app\models\PricingFormula;
use app\models\FixedValues;
use app\models\CrowdfundingMovieRentalValue;

class DataController extends Controller
{
    public $layout = 'questions';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'save'  => ['post'],
                    'calculate_crowdrental_pricing'  => ['post'],
                    'calculate'  => ['post'],
                    'email'  => ['post', 'get'],
                    'save_fixed_value' => ['post'],
                    'remove_fixed_value' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionSave()
    {
        $cookies     = Yii::$app->request->cookies;
        $requestData = Yii::$app->api->handleRequest();

        $success = true;
        $errors  = [];

        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            foreach ($requestData as $key => $data) {

                if(!$data['value'] || !$data['value_id'])
                    continue;

                $model              = new UserAnswers();
                $model->attributes  = $data;
                $model->created_at  = date('Y-m-d H:i:s');
                $model->user_id     = $cookies['user_id']->value;

                $eventType = EventType::find()->where(['user_id' => $cookies['user_id']->value])->one();
                if ($eventType == null) {
                    if ($data['value_id'] == 'eMail' && UserAnswers::find()->where(['value' => $data['value']])->one() != null) {
                        $eventType = EventType::find()->where(['user_id' => UserAnswers::find()->where(['value' => $data['value']])->one()->user_id])->one();
                        $eventType->event_status = 'Updated';
                        $eventType->created_at  = date('Y-m-d H:i:s');
                        $eventType->save();
                    } else {
                        $eventType = new EventType();
                        $eventType->user_id = $cookies['user_id']->value;
                        $eventType->event_status = 'Incomplete';
                        $eventType->created_at  = date('Y-m-d H:i:s');
                        $eventType->save();
                    }
                } else {
                    if ($data['value_id'] == 'eMail' && UserAnswers::find()->where(['value' => $data['value']])->one() != null) {
                        $eventType = EventType::find()->where(['user_id' => UserAnswers::find()->where(['value' => $data['value']])->one()->user_id])->one();
                        $eventType->event_status = 'Updated';
                        $eventType->created_at  = date('Y-m-d H:i:s');
                        $eventType->save();
                    } else {
                        if ($data['value_id'] == 'eMail') {
                            $eventType->event_status = 'New';
                            $eventType->created_at  = date('Y-m-d H:i:s');
                            $eventType->save();
                        } else if ($eventType->event_status != 'Incomplete' && $data['value_id'] != 'eMail') {
                            $eventType->event_status = 'Updated';
                            $eventType->created_at  = date('Y-m-d H:i:s');
                            $eventType->save();
                        }
                    }
                }

                $success  = $success && $model->save();
                $errors[] = $model->getErrors();
            }

            $transaction->commit();
        }
        catch(Exception $e){
            $transaction->rollBack();
            $errors[] = $e->getMessage();
        }
        
        return Yii::$app->api->_sendResponse(200, $errors, $success);
    }

    public function actionCalculate_crowdrental_pricing()
    {
        $data = Yii::$app->api->handleRequest(); 
        $cookies    = Yii::$app->request->cookies;
        $user_id    = $cookies['user_id']->value;

        $expected_value = intval($data['expected_value']);
        $price_value    = intval($data['price_value']);
        $type           = $data['type'];

        $fixedValue = FixedValues::find()->where(['value_id' => 'CFfee'])->one();
        $calculation = ($fixedValue ? $fixedValue->value : 0.92) * $expected_value * $price_value;
        $grandTotal = UserAnswers::find()->where(['user_id' => $user_id, 'value_id' => 'grandTotal'])->orderBy(['user_answer_id' => SORT_DESC])->one()->value;

        $crowdRevenue = UserAnswers::find()->where(['user_id' => $user_id, 'value_id' => 'crowdRevenue'])->orderBy(['user_answer_id' => SORT_DESC])->one();

        if(!$crowdRevenue)
        {
            $crowdRevenue = new UserAnswers;
        }

        $crowdRevenue->attributes = ['user_id' => $user_id, 'value' => (string)$calculation, 'value_id' => 'crowdRevenue'];
        // VarDumper::dump($calculation);
        // exit;
        $crowdRevenue->created_at = date('Y-m-d H:i:s');

        if($crowdRevenue->save())
        {
            return Yii::$app->api->_sendResponse(200, ['result' => $grandTotal - $calculation], true);
        }
        else
        {
            return Yii::$app->api->_sendResponse(200, ['result' => $crowdRevenue->getErrors()], true);
        }
    }

    public function actionCalculate()
    {
        $cookies    = Yii::$app->request->cookies;
        $user_id    = $cookies['user_id']->value;

        $userAnswers = UserAnswers::findUserPricingValues($user_id);

//        $eventType = EventType::find()->where(['user_id' => $user_id])->one();
//        if ($eventType == null) {
//            $eventType = new EventType();
//        }

        $totalPrice     = PricingFormula::calculateTotalPrice($userAnswers);

        $data = [
            'grandTotal'    => $totalPrice,
        ];

        $errors = UserAnswers::insertRows($user_id, $data);

        return Yii::$app->api->_sendResponse(200, $data, true);
    }

    public function actionEmail()
    {
        $cookies = Yii::$app->utils->handleCookies();

        if(!isset($cookies['user_id']))
        {
            return Yii::$app->api->_sendResponse(200, ["error" => "!! No user_id cookie found!"], false);
        }
        $previousAnswers = UserAnswers::find()->where(['user_id' => $cookies['user_id']->value])->andWhere(['in', 'value_id', ['filmType', 'crowdRevenue', 'discountTotal', 'email', 'grandTotal']])->all();
        // $previousAnswers = UserAnswers::find()->where(['user_id' => $cookies['user_id']->value])->andWhere(['in', 'value_id', ['filmType', 'crowdRevenue', 'discountTotal', 'email']])->all();

        $answers = Yii::$app->utils->mapArray($previousAnswers);

        $vars = [
            'filmType' => $answers['filmType'], 
            'grandTotal' => $answers['grandTotal'],
            'crowdFundRevenue' => $answers['crowdRevenue'],
            'discountTotal' => $answers['grandTotal'] - $answers['crowdRevenue'],
        ];
        // VarDumper::dump($vars);
        // exit;
        $success = Yii::$app->mailer
            ->compose('email_confirm')
            ->setGlobalMergeVars($vars)
            ->setTo(array($answers['eMail'], 'team@crowdfilms.be'))
            ->setFrom('team@crowdfilms.be')
            ->setReplyTo('team@crowdfilms.be')
            ->send();

    }

    public function actionPrevious_answers()
    {
        $cookies    = Yii::$app->request->cookies;

        $previousAnswers = UserAnswers::find()->where(['user_id' => $cookies['user_id']->value])->all();
        $answers         = Yii::$app->utils->mapArray($previousAnswers);

        return Yii::$app->api->_sendResponse(200, $answers, true);
    }

    public function actionRemove_answers()
    {
        $cookies = Yii::$app->request->cookies;
        UserAnswers::deleteAll(['user_id' => $cookies['user_id']->value]);
        // Yii::app()->request->cookies->clear();
    }

    public function actionSave_fixed_value() {
        $data = Yii::$app->api->handleRequest();

        $fixedValue = FixedValues::find()->where(['value_id' => $data['value_id']])->one();

        if ($fixedValue) {
            $fixedValue->value = $data['value'];
            $fixedValue->save();
        } else {
            $fixedValue = new FixedValues();
            $fixedValue->value_id = $data['value_id'];
            $fixedValue->value = $data['value'];
            $fixedValue->save();
        }
        return Yii::$app->api->_sendResponse(200, $fixedValue, true);
    }

    public function actionRemove_fixed_value() {
        $data = Yii::$app->api->handleRequest();

        $fixedValue = FixedValues::find()->where(['value_id' => $data['value_id']])->one();
        $fixedValue->delete();

        return Yii::$app->api->_sendResponse(200, $fixedValue, true);
    }
}
