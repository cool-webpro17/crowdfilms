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

    public function actionIndex() {
        $token = new TOken();
        $token->access_token = 'test';
        $token->refresh_token = 'test';
        $token->save();
    }

    public function actionRefresh_token() {
        $token = new Token();
        $token->access_token = 'test_access_token';
        $token->refresh_token = 'test_refresh_token';
        $token->save();
    }
}
