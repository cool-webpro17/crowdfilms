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
            User Log Details
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="x_title">
            <h4>Event Type</h4>
        </div>
        <div class="x_title">
        <select class="form-control" name="eventType">
            <option value="New"<?php if($eventType->event_status == 'New'): ?> selected="selected"<?php endif; ?>>New</option>
            <option value="Incomplete"<?php if($eventType->event_status == 'Incomplete'): ?> selected="selected"<?php endif; ?>>Incomplete</option>
            <option value="Updated"<?php if($eventType->event_status == 'Updated'): ?> selected="selected"<?php endif; ?>>Updated</option>
            <option value="In Progress"<?php if($eventType->event_status == 'In Progress'): ?> selected="selected"<?php endif; ?>>In Progress</option>
            <option value="Dealt with"<?php if($eventType->event_status == 'Dealt with'): ?> selected="selected"<?php endif; ?>>Dealt with</option>
        </select>
            <a class="btn-save-event btn btn-success">Save</a>
        <div class="x_title">
            <h4>Details</h4>
        </div>
        <div class="form-group">
            <table class="table dataTable table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>
                        User ID
                    </th>
                    <th>
                        Timestamp
                    </th>
                    <th>
                        Value
                    </th>
                    <th>
                        Value ID
                    </th>
                </tr>
                </thead>
                <tbody class="tbody-fixed">
                <?php
                foreach ($userAnswer as $eachRow):
                    ?>
                    <tr>
                        <td style="vertical-align: middle;">
                            <?php echo $eachRow['user_id']; ?>
                        </td>
                        <td style="vertical-align: middle;">
                            <?php echo $eachRow['created_at']; ?>
                        </td>
                        <td style="vertical-align: middle;">
                            <?php echo $eachRow['value']; ?>
                        </td>
                        <td style="vertical-align: middle;">
                            <?php echo $eachRow['value_id']; ?>
                        </td>
                    </tr>
                    <?php
                endforeach;

                ?>
                </tbody>
            </table>
        </div>

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

    $(document).ready(function() {
        $('.btn-save-event').on('click', function() {
            var eventType = $("select[name='eventType']").val();
            var userId = <?php echo $key; ?>;

            console.log('event', event + ' ' + userId);

            var jsonRequest = {
                'user_id': userId,
                'eventType': eventType
            };
            request('/admin/save_event_type', jsonRequest);
        });
    });

</script>