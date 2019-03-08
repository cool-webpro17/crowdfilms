<?php

namespace app\controllers;

use app\models\User;
use cookies;
use Symfony\Component\EventDispatcher\Event;
use Yii;
use yii\debug\models\UserSwitch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;

use app\models\UserAnswers;
use app\models\EventStatus;
use app\models\PricingFormula;
use app\models\FixedValues;
use app\models\Token;
use app\models\Project;
use yii\debug\controllers\UserController;

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
        /////////////////////////////////////
        $code = rawurldecode("R%2Bl78XmO89J7%2FrBPSuGhLKakwCSrFtnqi%2FRznQjlpuvmH8TcRt1UUFi%2FjC3oDNkFWGDNT61kWi8TuHPrW%2FA4P15RFguLDJlbm1dO9lYFQeOzEnZnNbFTQIPTrkziHsrhbarHjZ9gSM3zPpdIribod%2Br7ELBRdz3fXHDCrd2iSjg%2Fu%2Bwapp7Q1vb2t2oEWRyyUNJNRQNu529hcY4z8x2BJrBvpgPPakI%2F3CMHzSpdA0gc2S2MjIovGPon%2BFM4sBU%2BTBlVsx%2FuUQMyiR46eSXRX9Ewb9%2F%2Bcnya%2B930Z%2B2PEwXAUxZrpYIilchbnEThOhS5enwic3ue%2BcvJ0RbEmmiOeRlw0IoBv7qGM4tb1ru%2BiypZsnvP1n5F1B40WuoAphBvTVKwuA%2F0djQyXXLgOatpfaCzFbzFwXiarIWoVeTHtEvy22gp0ZgfEeUh%2BHndF45V95MDn1FNPK1fmM6lVX0u7ZU1cxl80335MVSMJD4ARNfgH%2BwF4mN9n9UIRXny%2FIppW9Kdzeu4Wpe5rb0bHHJC6oi8aLiJXEUx3Vz7kMNM1kAC8gZ6AWOpZmCjwhG%2BLOCOMUYYDS8y9uFTZYLhdmidT3tjrYZVi8Yqkg1cAOyQVKdlcLdOy0esuptdmDN1zG%2F18CIzi73WKgcodoiR2cpu2Un3%2FTPLw8QLHDZAONk5RMA6najHSISIWubzLxgp4m9wvwA9dXAIWCmVghOOr46zOxjk9bEqcN834K1vVFiGjsD19F%2FN5L9eHLFzTuPUS%2FIyB0YWM2XYl31aY2UhY%2B0SECgAB%2Fw%2BD63xvzf9W1HhPW0O%2F2HH3yHsONO5GTAPYc1eLAzmz1M%2FOUWFIMFXO3yXcw%3D%3D");
        /**
         * Request an access token based on the received authorization code.
         */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://app.teamleader.eu/oauth2/access_token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
        ]);
        $response = curl_exec($ch);
        $data = json_decode($response, true);
        $accessToken = $data['access_token'];
        echo $response;
        exit;
        /**
         * Get the user identity information using the access token.
         */

//        $accessToken = Token::find()->one()->access_token;
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/users.me');
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
//        $response = curl_exec($ch);
//        $data = json_decode($response, true);
//        echo $response;
//        exit;

        /////////////////////////////////
//                $query = [
//            'client_id' => $clientId,
//            'response_type' => 'code',
//            'redirect_uri' => $redirectUri,
//        ];
//        header('Location: https://app.teamleader.eu/oauth2/authorize?' . http_build_query($query));
//        exit;

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
                $model->answer_type = "Event";
                $model->user_id = $cookies['user_id']->value;

                if (UserAnswers::find()->where(['user_id' => $cookies['user_id']->value])->one()) {
                    $model->group_id = UserAnswers::find()->where(['user_id' => $cookies['user_id']->value])->one()->group_id;
                } else {
                    $model->group_id = $cookies['user_id']->value;
                }



                $eventType = EventStatus::find()->where(['user_id' => $cookies['user_id']->value])->one();
                if ($eventType == null) {
                    if ($data['value_id'] == 'eMail' && UserAnswers::find()->where(['value' => $data['value'], 'answer_type' => 'Event'])->one() != null) {
                        $eventType = EventStatus::find()->where(['user_id' => UserAnswers::find()->where(['value' => $data['value']])->one()->user_id])->one();
                        $eventType->event_status = 'Updated';
                        $eventType->created_at = date('Y-m-d H:i:s');
                        if ($eventType->save()) {
                            $content = [
                                'user_id' => $eventType->user_id
                            ];
                            $this->updateContact($content);
                        }
                    } else {
                        $eventType = new EventStatus();
                        $eventType->user_id = $cookies['user_id']->value;
                        $eventType->event_status = 'Incomplete';
                        $eventType->created_at = date('Y-m-d H:i:s');
                        $eventType->save();
                    }
                } else {
                    if ($data['value_id'] == 'eMail' && UserAnswers::find()->where(['value_id' => 'eMail', 'value' => $data['value'], 'answer_type' => 'Event'])->one() != null) {
                        $oldEvent = EventStatus::find()->where(['user_id' => UserAnswers::find()->where(['value' => $data['value'], 'answer_type' => 'Event'])->one()->user_id])->one();
                        $oldEvent->event_status = 'Updated';
                        $oldEvent->created_at = date('Y-m-d H:i:s');
                        if ($oldEvent->save()) {
                            $content = [
                                'user_id' => $oldEvent->user_id
                            ];
                            $this->updateContact($content);
                        }

                        $model->group_id = $oldEvent->user_id;

                        $userAnswers = UserAnswers::find()->where(['user_id' => $cookies['user_id']->value])->all();
                        foreach ($userAnswers as $eachAnswer) {
                            $eachAnswer->group_id = $oldEvent->user_id;
                            $eachAnswer->save();
                        }
                        $eventType->delete();
                    } else if ($data['value_id'] == 'email') {
                        $project = new Project();
                        $project->user_id = $eventType->user_id;
                        $project->contact_id = $eventType->contact_id;
                        $project->live_status = "No";
                        $project->project_status = "New";
                        $project->contact_name = UserAnswers::find()->where(['group_id' => $eventType->user_id, 'value_id' => 'eMail'])->orderBy(['created_at' => SORT_DESC])->one()->value;
                        $project->contact_email = UserAnswers::find()->where(['group_id' => $eventType->user_id, 'value_id' => 'eMail'])->orderBy(['created_at' => SORT_DESC])->one()->value;
                        $project->contact_phone = UserAnswers::find()->where(['group_id' => $eventType->user_id, 'value_id' => 'tel'])->orderBy(['created_at' => SORT_DESC])->one()->value;
                        $project->contact_comment = UserAnswers::find()->where(['group_id' => $eventType->user_id, 'value_id' => 'comment'])->orderBy(['created_at' => SORT_DESC])->one()->value;
                        $project->total_price = UserAnswers::find()->where(['group_id' => $eventType->user_id, 'value_id' => 'grandTotal'])->orderBy(['created_at' => SORT_DESC])->one()->value;
                        $project->already_paid = "0";
                        $project->created_at = date('Y-m-d H:i:s');
                        $project->save();
                        $eventType->delete();

                        $model->answer_type = 'Project';
                        $model->group_id = UserAnswers::find()->where(['user_id' => $cookies['user_id']->value, 'value_id' => 'eMail'])->one()->group_id;

                        $userAnswers = [];
                        $projectAnswer = UserAnswers::find()->where(['user_id' => $project->user_id, 'value_id' => 'eMail'])->one();
                        $allAnswers = UserAnswers::find()->all();
                        foreach ($allAnswers as $eachAnswer) {
                            if ($eachAnswer->answer_type == 'Event') {
                                if (UserAnswers::find()->where(['user_id' => $eachAnswer->user_id, 'value_id' => 'eMail'])->one()->value == $projectAnswer->value) {
                                    $userAnswers[] = $eachAnswer;
                                }
                            }
                        }

                        foreach ($userAnswers as $eachAnswer) {
                            $eachAnswer->answer_type = 'Project';
                            $eachAnswer->save();
                        }

                        /*
                         * This is the code to set user_id cookie
                         * Start
                         */

                        $user = new User();
                        $user->save();

                        Yii::$app->utils->setCookie('user_id', $user->getPrimaryKey());

                        /*
                         * This is the code to set user_id cookie
                         * End
                         */
                    } else {
                        if ($data['value_id'] == 'eMail') {
                            $eventType->event_status = 'New';
                            $eventType->created_at = date('Y-m-d H:i:s');
                            if ($eventType->save()) {
                                $content = [
                                    'email' => $data['value'],
                                    'user_id' => $eventType->user_id
                                ];
                                $this->createNewContact($content);
                            }
                        } else if ($eventType->event_status != 'Incomplete' && $data['value_id'] != 'eMail') {
                            $eventType->event_status = 'Updated';
                            $eventType->created_at = date('Y-m-d H:i:s');
                            if ($eventType->save()) {
                                $content = [
                                    'user_id' => $eventType->user_id
                                ];
                                $this->updateContact($content);
                            }
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

    public function createNewContact($content)
    {
        $accessToken = Token::find()->one()->access_token;

        $userAnswers = UserAnswers::find()->all();
        $userAnswers = ArrayHelper::index($userAnswers, null, 'group_id');

//        foreach ($userAnswers as $key => &$userAnswer) {
//            forEach ($userAnswer as $row) {
//
//                if ($row->value_id == 'eMail' && $row->answer_type == 'Event') {
//                    forEach ($userAnswers as $compareKey => $compareAnswer) {
//                        forEach ($compareAnswer as $compareRow) {
//
//                            if ($compareRow->value_id == 'eMail' && $row->answer_type == 'Event') {
//
//                                if ($compareRow->value == $row->value && $compareKey != $key) {
//                                    $userAnswers[$key] = array_merge($userAnswers[$key], $userAnswers[$compareKey]);
//                                    unset($userAnswers[$compareKey]);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }
//
//        foreach ($userAnswers as $key => &$userAnswer) {
//            foreach ($userAnswer as $rowKey => $row) {
//                $userAnswers[$key][$rowKey] = $this->object_to_array($userAnswers[$key][$rowKey]);
//            }
//        }

        $valueDetails = $userAnswers[$content['user_id']];

        $requestData = null;

        foreach ($valueDetails as $eachValue) {
            $requestData = $requestData . $eachValue['value_id'] . ': ' . $eachValue['value'] . '<br/> ';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/contacts.add');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken, "Content-Type: application/json;charset=utf-8"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'last_name' => $content['email'],
            'emails' => [
                [
                    'type' => "primary",
                    'email' => $content['email']
                ]
            ],
            'custom_fields' => [
                [
                    'id' => 'c5f0285d-9aa2-0ada-a354-311c70640cee',
                    'value' => $requestData

                ]
            ]
        ]));

        $response = curl_exec($ch);
        $data = json_decode($response, true);

        $eventStatus = EventStatus::find()->where(['user_id' => $content['user_id']])->one();
        $eventStatus->contact_id = $data['data']['id'];
        $eventStatus->save();
    }

    public function updateContact($content)
    {
        $accessToken = Token::find()->one()->access_token;

        $userAnswers = UserAnswers::find()->all();
        $userAnswers = ArrayHelper::index($userAnswers, null, 'group_id');

        $eventStatus = EventStatus::find()->where(['user_id' => $content['user_id']])->one();

//        foreach ($userAnswers as $key => &$userAnswer) {
//            forEach ($userAnswer as $row) {
//
//                if ($row->value_id == 'eMail' && $row->answer_type == 'Event') {
//                    forEach ($userAnswers as $compareKey => $compareAnswer) {
//                        forEach ($compareAnswer as $compareRow) {
//
//                            if ($compareRow->value_id == 'eMail' && $row->answer_type == 'Event') {
//
//                                if ($compareRow->value == $row->value && $compareKey != $key) {
//                                    $userAnswers[$key] = array_merge($userAnswers[$key], $userAnswers[$compareKey]);
//                                    unset($userAnswers[$compareKey]);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }
//
//        foreach ($userAnswers as $key => &$userAnswer) {
//            foreach ($userAnswer as $rowKey => $row) {
//                $userAnswers[$key][$rowKey] = $this->object_to_array($userAnswers[$key][$rowKey]);
//            }
//        }

        $valueDetails = $userAnswers[$content['user_id']];

        $requestData = null;

        foreach ($valueDetails as $eachValue) {
            $requestData = $requestData . $eachValue['value_id'] . ': ' . $eachValue['value'] . '<br/> ';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.teamleader.eu/contacts.update');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken, "Content-Type: application/json;charset=utf-8"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'id' => $eventStatus->contact_id,
            'custom_fields' => [
                [
                    'id' => 'c5f0285d-9aa2-0ada-a354-311c70640cee',
                    'value' => $requestData

                ]
            ]
        ]));
        $response = curl_exec($ch);
        $data = json_decode($response, true);

    }

    public function object_to_array($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();

            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }

            return $result;
        }

        return $data;
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

//        $eventType = EventStatus::find()->where(['user_id' => $user_id])->one();
//        if ($eventType == null) {
//            $eventType = new EventStatus();
//        }

        $totalPrice = PricingFormula::calculateTotalPrice($userAnswers);
        $formulaPrices = PricingFormula::calculateFormulaPrices($userAnswers);

        $newUserAnswer = new UserAnswers();
        $newUserAnswer->user_id = $user_id;
        $newUserAnswer->value_id = 'grandTotal';
        $newUserAnswer->value = (string)$totalPrice;
        $newUserAnswer->group_id = UserAnswers::find()->where(['user_id' => $user_id])->one()->group_id;
        $newUserAnswer->answer_type = 'Event';
        $newUserAnswer->created_at = date('Y-m-d H:i:s');

        $newUserAnswer->save();

        $returnData = [
            'grandTotal' => $totalPrice,
            'formulaOne' => $formulaPrices[0],
            'formulaTwo' => $formulaPrices[1],
            'formulaThree' => $formulaPrices[2],
            'formulaFour' => $formulaPrices[3],
        ];

        return Yii::$app->api->_sendResponse(200, $returnData, true);
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

//        Yii::$app->mailer->compose('email_confirm', ['vars' => $vars])
//            ->setFrom('info@antwerpporttours.com')
//            ->setTo($answers['eMail'])
//            ->setReplyTo('info@antwerpporttours.com')
//            ->setSubject('Jouw online prijsberekening bij Crowdfilms.be')
//            ->send();

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
