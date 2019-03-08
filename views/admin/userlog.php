<?php

use yii\helpers\Url as Url;
use yii\widgets\ActiveForm;

// use yiister\gentelella\assets\Asset;

// Asset::register($this);

/* @var $this yii\web\View */

$this->title = 'Crowdfilms - Admin';
$upload_options = Yii::$app->params['adminTools']['upload'];
$export_options = Yii::$app->params['adminTools']['export'];
?>
<input type="hidden" class="username" value="<?php echo $username; ?>"/>

<div class="x_panel">
    <div class="x_title">
        <h2>
            User Log
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <table class="table dataTable table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>
                    Event ID
                </th>
                <th>
                    Event Status
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody class="tbody-fixed">
            <?php foreach ($eventTypes as $eachEvent):
                ?>

                <tr>
                    <td style="vertical-align: middle;">
                        <?php
                        $existFlag = false;
                        foreach ($userAnswers[$eachEvent->user_id] as $row):
                            ?>

                            <?php if ($row['value_id'] == 'eMail') {
                            echo $row['value'];
                            $existFlag = true;
                            if ($userAnswers[$eachEvent->user_id]['exist']) {
                                echo ' (Returning Customer)';
                            }
                            break;
                        }?>

                            <?php
                        endforeach;

                        ?>
                        <?php
                        if ($existFlag == false):
                            echo $userAnswers[$eachEvent->user_id][count($userAnswers[$eachEvent->user_id]) - 2]['created_at'];
                        endif;
                        ?>
                    </td>
                    <td style="vertical-align: middle;">
                        <?php
                        echo $eachEvent->event_status;
                        ?>
                    </td>
                    <td>
                        <a class="btn btn-success" onclick="eventDetails(<?php echo($eachEvent->user_id); ?>)">Details</a>
                    </td>
                </tr>
                <?php
            endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function request(url, requestData, callback) {
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            contentType: 'application/json',
            success: function (data) {
                if (callback) {
                    callback(data.data);
                }
            },
            data: JSON.stringify(requestData)
        });
    }
    function eventDetails(key) {
        console.log('key', key);
        console.log('log ', <?php echo json_encode($userAnswers) ?>);
        var userAnswers = <?php echo json_encode($userAnswers) ?>;
        console.log('log', userAnswers[key]);
        var jsonRequest = {
            'key': key,
            'userAnswer': userAnswers[key]
        };
        request('/admin/user_log_details', jsonRequest);
    }
</script>