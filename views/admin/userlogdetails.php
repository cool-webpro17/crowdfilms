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
                <?php foreach ($statusType as $eachStatus): ?>
                    <option value="<?php echo $eachStatus['status_name']; ?>"<?php if ($eventType->event_status == $eachStatus['status_name']): ?> selected="selected"<?php endif; ?>><?php echo $eachStatus['status_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="x_title">
            <h4>Convert to Project</h4>
        </div>
        <div class="x_title">
            <a class="btn btn-success btn-convert">
                Convert
            </a>
        </div>
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
                        Value ID
                    </th>
                    <th>
                        Value
                    </th>
                </tr>
                </thead>
                <tbody class="tbody-fixed">
                <?php
                foreach ($userAnswer as $eachRow):
                    if ($eachRow['answer_type'] == 'Event'):
                        ?>

                        <tr>
                            <td style="vertical-align: middle; width: 20%;">
                                <?php echo $eachRow['user_id']; ?>
                            </td>
                            <td style="vertical-align: middle; width: 20%;">
                                <?php echo $eachRow['created_at']; ?>
                            </td>
                            <td style="vertical-align: middle; width: 20%;">
                                <?php echo $eachRow['value_id']; ?>
                            </td>
                            <td style="vertical-align: middle; width: 40%;">
                                <?php echo $eachRow['value']; ?>
                            </td>
                        </tr>
                        <?php
                    endif;
                endforeach;

                ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<div id="confirm-convert" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Convert to project</h4>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-12 col-form-label"><h5>Are you sure you want to upgrade this event to a project?</h5></div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-create-event" onclick="confirmConvert()">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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

    function confirmConvert() {
        var user_id = <?php echo $key; ?>;

        var jsonRequest = {
            "user_id": user_id
        };
        request("/admin/convert_project", jsonRequest, function() {
            $("#confirm-convert").modal('hide');
        });
    }

    $(document).ready(function () {
        $("select[name='eventType']").on('change', function () {
            var eventType = $("select[name='eventType']").val();
            var userId = <?php echo $key; ?>;

            console.log('event', event + ' ' + userId);

            var jsonRequest = {
                'user_id': userId,
                'eventType': eventType
            };
            request('/admin/save_event_type', jsonRequest);
        });
        $(".btn-convert").on('click', function() {
            $('#confirm-convert').modal('show');
        });
    });

</script>