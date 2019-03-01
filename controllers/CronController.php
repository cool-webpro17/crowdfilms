<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\VarDumper;
use app\models\UserAnswers;
use app\models\EventStatus;
use app\models\PricingFormula;
use app\models\FixedValues;
use app\models\Token;

class CronController extends Controller
{
    public $layout = 'questions';


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'refresh_token' => ['get'],
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

    public function actionIndex() {
        $token = new TOken();
        $token->access_token = 'test';
        $token->refresh_token = 'test';
        $token->save();
    }

    public function actionRefresh_token() {
        $clientId = '960432c85b09d5c20ede10bd8e765e8a';
        $clientSecret = 'faf5db970b0cf6a67f79f44e42edfc39';

        $token = Token::find()->one();
        $accessToken = $token->access_token;
        $refreshToken = $token->refresh_token;

        $refreshCh = curl_init();
        curl_setopt($refreshCh, CURLOPT_URL, 'https://app.teamleader.eu/oauth2/access_token');
        curl_setopt($refreshCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($refreshCh, CURLOPT_POST, true);
        curl_setopt($refreshCh, CURLOPT_POSTFIELDS, [
            "client_id" => $clientId,
            "client_secret" => $clientSecret,
            "refresh_token" => $refreshToken,
            "grant_type" => "refresh_token",
        ]);
        $refreshResponse = curl_exec($refreshCh);
        $refreshData = json_decode($refreshResponse, true);

        $token->access_token = $refreshResponse['access_token'];
        $token->refresh_token = $refreshResponse['refresh_token'];

        $token->save();
    }
}
