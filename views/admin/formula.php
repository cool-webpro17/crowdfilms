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
            Event Type
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            <button class="btn btn-success btn-event-add" onclick="addNewType('event')">Add New Event Type</button>
        </div>
        <table class="table dataTable table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>
                    Event Type Label
                </th>
                <th>
                    Event Type Value
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody class="tbody-fixed">
            <?php
            foreach ($eventTypes as $eachEventType):
                ?>
                <tr>
                    <td><?php echo $eachEventType['text']; ?></td>
                    <td><?php echo $eachEventType['value']; ?></td>
                    <td class="text-center">
                        <a class="margin-right-30 cursor-pointer btn-event-edit" onclick="editType('event', '<?php echo $eachEventType->value; ?>', '<?php echo $eachEventType->text;  ?>')"><i class="fa fa-pencil"></i></a>
                        <a class="cursor-pointer btn-event-remove" onclick="removeType('event', '<?php echo $eachEventType->value; ?>')"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <h2>
            Film Type
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            <button class="btn btn-success btn-film-add" onclick="addNewType('film')">Add New Film Type</button>
        </div>
        <table class="table dataTable table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>
                    Film Type Label
                </th>
                <th>
                    Film Type Value
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody class="tbody-fixed">
            <?php
            foreach ($filmTypes as $eachFilmType):
                ?>
                <tr>
                    <td><?php echo $eachFilmType['text']; ?></td>
                    <td><?php echo $eachFilmType['value']; ?></td>
                    <td class="text-center">
                        <a class="margin-right-30 cursor-pointer btn-film-edit" onclick="editType('film', '<?php echo $eachFilmType->value; ?>', '<?php echo $eachFilmType->text; ?>')"><i class="fa fa-pencil"></i></a>
                        <a class="cursor-pointer btn-film-remove" onclick="removeType('film', '<?php echo $eachFilmType->value; ?>')"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <h2>
            Customer Type
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            <button class="btn btn-success btn-customer-add" onclick="addNewType('customer')">Add New Customer Type</button>
        </div>
        <table class="table dataTable table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>
                    Customer Type Label
                </th>
                <th>
                    Customer Type Value
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody class="tbody-fixed">
            <?php
            foreach ($customerTypes as $eachCustomerType):
                ?>
                <tr>
                    <td><?php echo $eachCustomerType['text']; ?></td>
                    <td><?php echo $eachCustomerType['value']; ?></td>
                    <td class="text-center">
                        <a class="margin-right-30 cursor-pointer btn-customer-edit" onclick="editType('customer', '<?php echo $eachCustomerType->value; ?>', '<?php echo $eachCustomerType->text; ?>')"><i class="fa fa-pencil"></i></a>
                        <a class="cursor-pointer btn-customer-remove" onclick="removeType('customer', '<?php echo $eachCustomerType->value; ?>')"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="type-create" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Create new type</h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <label>Value: </label>
                    <input name="value"/>
                </div>
                <div class="text-center">
                    <label>Label: </label>
                    <input name="text"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-create-event" onclick="saveEvent()">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

    var type = null;

    function addNewType(key) {
        type = key;
//        console.log('key', key);
        $("input[name='value'").val('');
        $("input[name='text'").val('');
        $("input[name='value'").prop("disabled", false);
        $('.modal-title').text('Create new ' + key + ' type');
        $('#type-create').modal('show');
    }

    function editType(key, value, text) {
        type = key;
        console.log('key', key + ' ' + text + ' ' + value);
        $("input[name='value'").val(value);
        $("input[name='value'").attr('disabled', 'disabled');
        $("input[name='text'").val(text);
        $('.modal-title').text('Edit new ' + key + ' type');
        $('#type-create').modal('show');
    }

    function saveEvent() {
        var jsonRequest = {
            'type': type,
            'value': $("input[name='value'").val(),
            'text': $("input[name='text'").val()
        };
        request('/admin/save_type', jsonRequest, function() {
            $('#type-create').modal('hide');
        });
    }

    function removeType(type, value) {
        var jsonRequest = {
            'type': type,
            'value': value
        };

        request('/admin/remove_type', jsonRequest);
    }

    $(document).ready(function(){

    });
</script>