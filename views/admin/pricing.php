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

<input type="hidden" class="username" value="<?php echo $username; ?>" />

<div class="x_panel">
    <div class="x_title">
        <h2>
            Pricing Upload
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <?php $form = ActiveForm::begin(['action' => ['/admin/upload'], 'options' => ['enctype' => 'multipart/form-data']]) ?>
        <?php
        foreach ($upload_options as $option):

            ?>
            <?= $form->field($model, $option['attribute'])->fileInput() ?>

            <?php
        endforeach;

        ?>

        <div>
            <button type="submit" class="btn btn-success btn-upload">Submit</button>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
<div class="x_panel">
    <div class="x_title">
        <h2>
            User Export
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <a class="btn btn-md btn-success btn-export" href="/admin/export">Export</a>
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <h2>
            Fixed Value
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            <button class="btn btn-success btn-add-fixed">Add New Fixed Value</button>
        </div>
        <table class="table dataTable table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>
                    Value Id
                </th>
                <th>
                    Value
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody class="tbody-fixed">
            <?php
            foreach ($dataProvider as $eachData):
                ?>
                <tr>
                    <td><input class="fixed-price" name="value_id" value="<?php echo $eachData['value_id']; ?>"
                               disabled></td>
                    <td><input class="fixed-price" name="value" value="<?php echo $eachData['value']; ?>"></td>
                    <td class="text-center">
                        <a class="margin-right-30 cursor-pointer btn-save-fixed"><i class="fa fa-save"></i></a>
                        <a class="cursor-pointer btn-remove-fixed"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
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

    $(document).ready(function(){
        var username = $('.username').val();
        $('.btn-upload').on('click', function() {
            var jsonLog = {
                'username': username,
                'action': 'Upload Pricing'
            };
            request('/admin/activity_log', jsonLog);
        });
        $('.btn-export').on('click', function() {
            var jsonLog = {
                'username': username,
                'action': 'Export User Answers'
            };
            request('/admin/activity_log', jsonLog);
        });
        $('.tbody-fixed').on('click', '.btn-save-fixed', function() {
            var value_id = $(this).parent().parent().find('td:first-child').find('input').val();
            var value = $(this).parent().parent().find('td:nth-child(2)').find('input').val();
            var jsonRequest = {
                'value_id': value_id,
                'value': value
            };
            if (value == '' || value_id == '') {
                alert('You need to input value!');
            } else {
                request('/data/save_fixed_value', jsonRequest);
                $(this).parent().find('a.btn-temp-remove').removeClass('btn-temp-remove').addClass('btn-remove-fixed');
                $(this).parent().parent().find('td:first-child').find('input').attr('disabled', 'disabled');
                var jsonLog = {
                    'username': username,
                    'action': 'Save Fixed Value'
                };
                request('/admin/activity_log', jsonLog);
            }
        });

        $('.tbody-fixed').on('click', '.btn-temp-remove', function() {
            $(this).parent().parent().remove();
            var jsonLog = {
                'username': username,
                'action': 'Cancel Fixed Value'
            };
            request('/admin/activity_log', jsonLog);
        });

        $('.tbody-fixed').on('click', '.btn-remove-fixed', function() {
            var value_id = $(this).parent().parent().find('td:first-child').find('input').val();
            var jsonRequest = {
                'value_id': value_id
            };
            request('/data/remove_fixed_value', jsonRequest);
            $(this).parent().parent().remove();
            var jsonLog = {
                'username': username,
                'action': 'Remove Fixed Value'
            };
            request('/admin/activity_log', jsonLog);
        });

        $('.btn-add-fixed').on('click', function() {
            $('.tbody-fixed').append("<tr>" +
                    "<td>" +
                    "<input autofocus name='value_id' class='fixed-price' value=''>" +
                    "</td>" +
                    "<td>" +
                    "<input name='value_id' class='fixed-price' value=''>" +
                    "</td>" +
                    "<td class='text-center'>" +
                    "<a class='margin-right-30 cursor-pointer btn-save-fixed'><i class='fa fa-save'></i></a>" +
                    "<a class='cursor-pointer btn-temp-remove'><i class='fa fa-trash'></i></a>" +
                    "</td>" +
                    "</tr>"
            );
            var jsonLog = {
                'username': username,
                'action': 'Add Fixed Value'
            };
            request('/admin/activity_log', jsonLog);
        });

    });
</script>