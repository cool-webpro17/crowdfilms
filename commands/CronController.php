<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Token;

/**
 * Cron controller
 */
class CronController extends Controller {

    public function actionIndex() {
        $clientId = '960432c85b09d5c20ede10bd8e765e8a';
        $clientSecret = 'faf5db970b0cf6a67f79f44e42edfc39';

        $token = Token::find()->one();
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

        $token->access_token = $refreshData['access_token'];
        $token->refresh_token = $refreshData['refresh_token'];

        $token->save();
    }
}