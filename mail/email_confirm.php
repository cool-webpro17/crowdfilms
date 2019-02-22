<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <style>
        u + #body a {
            color: inherit;
            text-decoration: none;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            line-height: inherit;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body id="body">
<?php $this->beginBody() ?>
<?= $content ?>
<!--*|IF:MC_PREVIEW_TEXT|*-->
<!--[if !gte mso 9]><!----><span class="mcnPreviewText" style="display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;">*|MC_PREVIEW_TEXT|*</span><!--<![endif]-->
<!--*|END:IF|*-->
<div>
    <div style="text-align: center; background-color: #f7f7f7;">
        <img alt="" src="https://ci5.googleusercontent.com/proxy/x1ItzPy68d66NV21bEZCe60nmbadCrupircrJpDInIQqRp-wXpa96Ufi9ajDCeNq0Wbt_guuN1S57xLM1sfYhMATQZhHl5pddagrnQ_HQbvkkLMiOLPdinfvt_8UEFWwRrWiwXtI99lop3YehklHOa0cj5qcwtqt6-0=s0-d-e1-ft#https://gallery.mailchimp.com/6198c87ec3797bfa58c976fab/images/3ec048cc-9e8a-4994-a181-f76ead49a762.png" style="max-width:2478px;padding-bottom:10px; padding-top: 10px; display:inline!important;vertical-align:bottom" class="m_-8569376839397008832mcnImage CToWUd a6T" width="564" align="middle" tabindex="0">
    </div>
    <div style="">
        <div style="max-width: 564px; padding-top: 50px; padding-bottom: 70px; margin: 0 auto;">
            <div style="font-size: 40px; color: #222222; text-align: center;">
                <b>Thanks! Je projectaanvraag voor een <?= $vars['filmType'] ?> is binnen.</b>
            </div>
            <div style="font-size: 16px; color: #808080; margin-top: 20px;">
                Sit back en focus jij je op je event. Wij matchen je nu aan de perfecte videograaf voor jouw wensen en zorgen er zo voor dat deze fijne herinneringen vastgelegd worden.
                <br/><br/>
                De prijs voor jouw Volledige opname bedraagt in het totaal €<?= $vars['grandTotal'] ?>.
                <br/><br/>
                De reserveringskost bedraagt 30% van het totaalbedrag. Je krijgt snel een e-mail met een betaallink waarop je deze voorschot stort.<br/>
                <b>Waarom een voorschot?</b> Onze motors zijn al in werk geschoten om jouw match te vinden en ons werk is dus begonnen! Daarbij valt de kost dan ook voor jou beter mee. Tegen je event er aan komt, hoef je nog slechts 70% te betalen.
                <br/><br/>
                Zit je in de tussentijd nog met vragen? Wil je graag een Crowdfunding-pagina starten? Stuur ons dan zeker een mailtje op <a href="mailto:team@crowdfilms.be" target="_blank" style="color: #00add8;">team@crowdfilms.be</a> of bel ons op 03 808 21 24
            </div>
        </div>
    </div>
    <div style="background-color: #333333;">
        <div style="max-width: 564px; padding-top:50px; padding-bottom: 70px; margin: 0 auto; text-align: center;">
            <a href="https://www.facebook.com/Crowdfilms.be" target="_blank">
                <img src="https://ci6.googleusercontent.com/proxy/iZE-48sXvszGHh6MUoqCYHnlP8ohfGJI6V1fj23YRaJHEaKjOb2V7stez03tl97kcCY9ebD52HlFfqGKcTQbPlQaysAL26ZKjUSa5NGX7CU3WUodCbzb-vFMkIXxvIREY4PT879oIw=s0-d-e1-ft#https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-facebook-48.png" style="padding-left: 15px; padding-right: 15px;" width="24" height="24" class="CToWUd">
            </a>
            <a href="https://www.instagram.com/crowdfilms" target="_blank">
                <img src="https://ci5.googleusercontent.com/proxy/Ihh9hEwk_36d3lzL_tLmGaqmGhc-dLqZP-II9LpKgUDCh37Kvw1N4-DJsrxuyAA9V1NNx3975BQO5w7DNVWvFHpPM4gkDm8eMVCLYy_PtGWEZAxMuaULgOR-6W0K_1sgXOcwNMtgGVE=s0-d-e1-ft#https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-instagram-48.png" style="padding-left: 15px; padding-right: 15px;" width="24" height="24" class="CToWUd">
            </a>
            <a href="https://crowdfilms.be" target="_blank">
                <img src="https://ci6.googleusercontent.com/proxy/uZ0yuxmORppOSAVlAI9An9dTGgd5WLSQ0CBL7MLu_J4uk8Z1QO7RWFmdlkUYkmd_GLhwph5RoVCp9eKrXzEQnDQ91cNlGygasb_4p2fT-TnBvWoJAX8mqJXeyuG36Kto6QrY=s0-d-e1-ft#https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-link-48.png" style="padding-left: 15px; padding-right: 15px;" width="24" height="24" class="CToWUd">
            </a>
            <div style="margin-top: 40px; border-bottom: 2px solid #515151; width: 100%;">
            </div>
            <div style="margin-top: 20px; text-align:center; color: #ffffff; font-size: 12px;">
                <em>Deze prijssimulatie geldt enkel ter informatie en is vrijblijvend en niet bindend voor Crowdfilms (Mixle bvba). <br/><br/>Copyright © 2019 Crowdfilms, All rights reserved.</em>
                <div style="margin-top: 40px;">
                    <b>Our mailing address is:</b>
                    <br/>
                    <a style="color: #ffffff;" target="_blank" href="mailto:team@crowdfilms.be"><u>team@crowdfilms.be</u></a>
                    <br/>
                    <br/>
                    Want to change how you receive these emails?
                    <br/>
                    You can <u>update your preferences</u> or <u>unsubscribe</u> from this list.
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
