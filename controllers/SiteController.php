<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use app\models\ContactForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\Question;
use app\models\FixedValues;
use app\models\UserAnswers;
use app\models\CrowdfundingMovieRentalValue;
use app\models\UploadForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    public $layout = 'empty';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        Yii::$app->session->set('admin', 'adminLocked');
        Yii::$app->session->set('username', null);
        $model = new UploadForm;
        $vars = ['cookies' => Yii::$app->request->cookies, 'model' => $model, 'action' => 'adminLocked'];

        $vars['model'] = $model;

        return $this->render('../admin/adminLocked', $vars);
    }

    /**
     * Displays questions page.
     *
     * @return string
     */
    public function actionAanvraag()
    {
        $cookies = Yii::$app->utils->handleCookies();
        
        $fixedValues = Yii::$app->utils->mapArray(FixedValues::find()->all());

        $previousAnswers = UserAnswers::find()->where(['user_id' => $cookies['user_id']->value])->all();
        $answers         = Yii::$app->utils->mapArray($previousAnswers);

        return $this->render('aanvraag', [
            'cookies'       => $cookies,
            'fixedValues'   => $fixedValues,
            'answers'       => $answers,
            'grandTotal'    => $answers['grandTotal'] ? $answers['grandTotal'] : '',
            'crowdFundRevenue'  => $answers['crowdFundRevenue'] ? $answers['crowdFundRevenue'] : ($fixedValues['crowdIndiPriceDefault'] * $fixedValues['crowdFundersDefault'] * $fixedValues['CFfee']),
            'discountTotal' => $answers['discountTotal'] ? $answers['discountTotal'] : '',
        ]);
    }
	
	public function actionFaq()
    {
		return $this->render('faq');
	}
	
	public function actionFilm()
    {
		return $this->render('film');
	}
	
	public function actionProject()
    {
		return $this->render('project');
	}
	
	public function actionKleinkunstatthemovies()
    {
		return $this->render('kleinkunstatthemovies');
	}
	
	public function actionVivalasvegas()
    {
		return $this->render('vivalasvegas');
	}

    public function actionPrivacy()
    {
        return $this->render('privacy');
    }

    public function actionAlgemenevoorwaarden()
    {
        return $this->render('algemenevoorwaarden');
    }
}
