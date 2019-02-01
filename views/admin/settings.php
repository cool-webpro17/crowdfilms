<?php

use yii\helpers\Url as Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Crowdfilms - Admin';
$upload_options =  Yii::$app->params['adminTools']['upload'];
$export_options =  Yii::$app->params['adminTools']['export'];
?>
<div class="x_panel">
    <div class="x_title">
        <h2>
            Admin Users
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            <a class="btn btn-success btn-add-admin">Add Admin User</a>
        </div>
        <table class="table dataTable table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>
                    Username
                </th>
                <th>
                    Password
                </th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody class="tbody-admin">
            <?php
            foreach ($dataProvider as $eachData):
                ?>
                <tr>
                    <td data-name="username" style="width:40%; vertical-align: middle;"><?php echo $eachData['username']; ?></td>
                    <td data-name="password" style="width:40%; vertical-align: middle;"><?php echo base64_decode($eachData['password']); ?></td>
                    <td class="text-center">
                        <a class="btn cursor-pointer btn-edit-admin"><i class="fa fa-pencil"></i></a>
                        <a class="btn cursor-pointer btn-remove-admin"><i class="fa fa-trash"></i></a>
                    </td>
                    <td class="text-center">
                        <a class="btn btn-success btn-resend">Resend Password</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
<div id="admin-create" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Create new admin user</h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <label>Username: </label>
                    <input name="username" />
                </div>
                <div class="text-center">
                    <label>Password: </label>
                    <input name="password" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-create-admin">Create</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="admin-edit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Edit admin user</h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <label>Username: </label>
                    <input name="username" />
                </div>
                <div class="text-center">
                    <label>Password: </label>
                    <input name="password" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-save-admin">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="resend-confirm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    Do you want to send the email with reset password?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-save-confirm">Yes</button>
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

    $(document).ready(function(){
        var resendRequest = {
            'username': '',
            'password': ''
        };
        $('.btn-add-admin').on('click', function(){
            $('#admin-create').modal('show');
        });
        $('.btn-create-admin').on('click', function() {
            var username = $('#admin-create').find("input[name='username']").val();
            var password = $('#admin-create').find("input[name='password']").val();
            var jsonRequest = {
                'username': username,
                'password': password
            };
            request('/admin/save_admin', jsonRequest, function () {
                $('#admin-create').modal('hide');
            });
        });
        $('.btn-edit-admin').on('click', function() {
            var username = $(this).parent().parent().find('td:first-child').html();
            var password = $(this).parent().parent().find('td:nth-child(2)').html();
            $('#admin-edit').modal('show');
            $('#admin-edit').find("input[name='username']").val(username);
            $('#admin-edit').find("input[name='password']").val(password);
        });
        $('.btn-save-admin').on('click', function() {
            var username = $('#admin-edit').find("input[name='username']").val();
            var password = $('#admin-edit').find("input[name='password']").val();
            var jsonRequest = {
                'username': username,
                'password': password
            };

            request('/admin/save_admin', jsonRequest, function () {
                $('#admin-create').modal('hide');
            });
        });
        $('.btn-remove-admin').on('click', function() {
            var username = $(this).parent().parent().find('td:first-child').html();

            var jsonRequest = {
                'username': username
            };

            console.log('jsonRequest', jsonRequest);

            request('/admin/remove_admin', jsonRequest);
        });
        $('.btn-resend').on('click', function() {
            resendRequest.username = $(this).parent().parent().find('td:first-child').html();
            resendRequest.password = $(this).parent().parent().find('td:nth-child(2)').html();

            $('#resend-confirm').modal('show');
        });
        $('.btn-save-confirm').on('click', function() {
            request('/admin/resend_email', resendRequest, function() {
                $('#resend-confirm').modal('hide');
            });
        });
    });
</script>

<style>
    input {
        border: 1px solid;
    }
</style>