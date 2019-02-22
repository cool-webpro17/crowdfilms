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

class DataController extends Controller
{
    public $layout = 'questions';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'save' => ['post'],
                    'calculate_crowdrental_pricing' => ['post'],
                    'calculate' => ['post'],
                    'email' => ['post', 'get'],
                    'save_fixed_value' => ['post'],
                    'remove_fixed_value' => ['post'],
                    'formula_prices' => ['post'],
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

    public function actionAuth()
    {
        $clientId = '960432c85b09d5c20ede10bd8e765e8a';
        $clientSecret = 'faf5db970b0cf6a67f79f44e42edfc39';
        /**
         * Where to redirect to after the OAuth 2 flow was completed.
         * Make sure this matches the information of your integration settings on the marketplace build page.
         */
        $redirectUri = 'https://crowdfilms.be';

        $url = "https://app.teamleader.eu/oauth2/authorize";

//        $curl = curl_init();
//// Set some options - we are passing in a useragent too here
//        curl_setopt_array($curl, array(
//            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_URL => 'https://app.teamleader.eu/oauth2/authorize?client_id=960432c85b09d5c20ede10bd8e765e8a&response_type=code&redirect_uri=https://crowdfilms.be',
//            CURLOPT_USERAGENT => 'Codular Sample cURL Request'
//        ));
//// Send the request & save response to $resp
//        $resp = curl_exec($curl);
//// Close request to clear up some resources
//        curl_close($curl);
//        echo $resp;
        $query = [
            'client_id' => $clientId,
            'response_type' => 'code',
            'redirect_uri' => $redirectUri,
        ];
        header('Location: https://app.teamleader.eu/oauth2/authorize?' . http_build_query($query));
        exit;

    }

    public function actionSave()
    {
        $cookies = Yii::$app->request->cookies;
        $requestData = Yii::$app->api->handleRequest();

        $success = true;
        $errors = [];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($requestData as $key => $data) {
                date_default_timezone_set('Europe/Berlin');

                if (!$data['value'] || !$data['value_id'])
                    continue;

                $model = new UserAnswers();
                $model->attributes = $data;
                $model->created_at = date('Y-m-d H:i:s');
                $model->user_id = $cookies['user_id']->value;

                $eventType = EventType::find()->where(['user_id' => $cookies['user_id']->value])->one();
                if ($eventType == null) {
                    if ($data['value_id'] == 'eMail' && UserAnswers::find()->where(['value' => $data['value']])->one() != null) {
                        $eventType = EventType::find()->where(['user_id' => UserAnswers::find()->where(['value' => $data['value']])->one()->user_id])->one();
                        $eventType->event_status = 'Updated';
                        $eventType->created_at = date('Y-m-d H:i:s');
                        $eventType->save();
                    } else {
                        $eventType = new EventType();
                        $eventType->user_id = $cookies['user_id']->value;
                        $eventType->event_status = 'Incomplete';
                        $eventType->created_at = date('Y-m-d H:i:s');
                        $eventType->save();
                    }
                } else {
                    if ($data['value_id'] == 'eMail' && UserAnswers::find()->where(['value' => $data['value']])->one() != null) {
                        $eventType = EventType::find()->where(['user_id' => UserAnswers::find()->where(['value' => $data['value']])->one()->user_id])->one();
                        $eventType->event_status = 'Updated';
                        $eventType->created_at = date('Y-m-d H:i:s');
                        $eventType->save();
                    } else {
                        if ($data['value_id'] == 'eMail') {
                            $eventType->event_status = 'New';
                            $eventType->created_at = date('Y-m-d H:i:s');
                            $eventType->save();
                        } else if ($eventType->event_status != 'Incomplete' && $data['value_id'] != 'eMail') {
                            $eventType->event_status = 'Updated';
                            $eventType->created_at = date('Y-m-d H:i:s');
                            $eventType->save();
                        }
                    }
                }

                $success = $success && $model->save();
                $errors[] = $model->getErrors();
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            $errors[] = $e->getMessage();
        }

        return Yii::$app->api->_sendResponse(200, $errors, $success);
    }

    public function actionCalculate_crowdrental_pricing()
    {
        $data = Yii::$app->api->handleRequest();
        $cookies = Yii::$app->request->cookies;
        $user_id = $cookies['user_id']->value;

        $expected_value = intval($data['expected_value']);
        $price_value = intval($data['price_value']);
        $type = $data['type'];

        $fixedValue = FixedValues::find()->where(['value_id' => 'CFfee'])->one();
        $calculation = ($fixedValue ? $fixedValue->value : 0.92) * $expected_value * $price_value;
        $grandTotal = UserAnswers::find()->where(['user_id' => $user_id, 'value_id' => 'grandTotal'])->orderBy(['user_answer_id' => SORT_DESC])->one()->value;

        $crowdRevenue = UserAnswers::find()->where(['user_id' => $user_id, 'value_id' => 'crowdRevenue'])->orderBy(['user_answer_id' => SORT_DESC])->one();

        if (!$crowdRevenue) {
            $crowdRevenue = new UserAnswers;
        }

        $crowdRevenue->attributes = ['user_id' => $user_id, 'value' => (string)$calculation, 'value_id' => 'crowdRevenue'];
        // VarDumper::dump($calculation);
        // exit;
        $crowdRevenue->created_at = date('Y-m-d H:i:s');

        if ($crowdRevenue->save()) {
            return Yii::$app->api->_sendResponse(200, ['result' => $grandTotal - $calculation], true);
        } else {
            return Yii::$app->api->_sendResponse(200, ['result' => $crowdRevenue->getErrors()], true);
        }
    }

    public function actionCalculate()
    {
        $cookies = Yii::$app->request->cookies;
        $user_id = $cookies['user_id']->value;

        $userAnswers = UserAnswers::findUserPricingValues($user_id);

//        $eventType = EventType::find()->where(['user_id' => $user_id])->one();
//        if ($eventType == null) {
//            $eventType = new EventType();
//        }

        $totalPrice = PricingFormula::calculateTotalPrice($userAnswers);
        $formulaPrices = PricingFormula::calculateFormulaPrices($userAnswers);

        $data = [
            'grandTotal' => $totalPrice,
            'formulaOne' => $formulaPrices[0],
            'formulaTwo' => $formulaPrices[1],
            'formulaThree' => $formulaPrices[2],
            'formulaFour' => $formulaPrices[3],
        ];

        $errors = UserAnswers::insertRows($user_id, $data);

        return Yii::$app->api->_sendResponse(200, $data, true);
    }

    public function actionFormula_prices()
    {
        $cookies = Yii::$app->request->cookies;
        $user_id = $cookies['user_id']->value;

        $userAnswers = UserAnswers::findUserPricingValues($user_id);

        $formulaPrices = PricingFormula::calculateFormulaPrices($userAnswers);
        $data = [
            'formulaOne' => $formulaPrices[0],
            'formulaTwo' => $formulaPrices[1],
            'formulaThree' => $formulaPrices[2],
            'formulaFour' => $formulaPrices[3],
        ];
        return Yii::$app->api->_sendResponse(200, $data, true);
    }

    public function actionEmail()
    {
        $cookies = Yii::$app->utils->handleCookies();

        if (!isset($cookies['user_id'])) {
            return Yii::$app->api->_sendResponse(200, ["error" => "!! No user_id cookie found!"], false);
        }
        $previousAnswers = UserAnswers::find()->where(['user_id' => $cookies['user_id']->value])->andWhere(['in', 'value_id', ['filmType', 'crowdRevenue', 'discountTotal', 'email', 'grandTotal']])->all();

        $answers = Yii::$app->utils->mapArray($previousAnswers);

        $vars = [
            'filmType' => $answers['filmType'],
            'grandTotal' => $answers['grandTotal'],
            'crowdFundRevenue' => $answers['crowdRevenue'],
            'discountTotal' => $answers['grandTotal'] - $answers['crowdRevenue'],
        ];

        Yii::$app->mailer->compose('email_confirm', ['vars' => $vars])
            ->setFrom('info@antwerpporttours.com')
            ->setTo('aaron.rodier84@gmail.com')
            ->setReplyTo('info@antwerpporttours.com')
            ->setSubject('Jouw online prijsberekening bij Crowdfilms.be')
            ->send();

//        $success = Yii::$app->mailer
//            ->compose('email_confirm')
//            ->setGlobalMergeVars($vars)
//            ->setTo(array($answers['eMail'], 'team@crowdfilms.be'))
//            ->setFrom('team@crowdfilms.be')
//            ->setReplyTo('team@crowdfilms.be')
//            ->send();

    }

    public function actionPrevious_answers()
    {
        $cookies = Yii::$app->request->cookies;

        $previousAnswers = UserAnswers::find()->where(['user_id' => $cookies['user_id']->value])->all();
        $answers = Yii::$app->utils->mapArray($previousAnswers);

        return Yii::$app->api->_sendResponse(200, $answers, true);
    }

    public function actionRemove_answers()
    {
        $cookies = Yii::$app->request->cookies;
        UserAnswers::deleteAll(['user_id' => $cookies['user_id']->value]);
        // Yii::app()->request->cookies->clear();
    }

    public function actionSave_fixed_value()
    {
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

    public function actionRemove_fixed_value()
    {
        $data = Yii::$app->api->handleRequest();

        $fixedValue = FixedValues::find()->where(['value_id' => $data['value_id']])->one();
        $fixedValue->delete();

        return Yii::$app->api->_sendResponse(200, $fixedValue, true);
    }
}
