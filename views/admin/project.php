<?php

use yii\helpers\Url as Url;
use yii\widgets\ActiveForm;

// use yiister\gentelella\assets\Asset;

// Asset::register($this);

/* @var $this yii\web\View */

$this->title = 'Crowdfilms - Admin';
?>
<input type="hidden" class="username" value="<?php echo $username; ?>"/>

<div class="x_panel">
    <div class="x_title">
        <h2>
            Project Status
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            <button class="btn btn-success btn-event-add" onclick="addProjectStatus()">Add New Project Status</button>
        </div>
        <table class="table dataTable table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>
                    Project Status
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody class="tbody-fixed">
            <?php
            foreach ($projectStatus as $eachStatus):
                ?>
                <tr>
                    <td><?php echo $eachStatus['status']; ?></td>
                    <td class="text-center">
                        <a class="margin-right-30 cursor-pointer btn-event-edit" onclick="editStatus('<?php echo $eachStatus['id']; ?>', '<?php echo $eachStatus['status']; ?>')"><i class="fa fa-pencil"></i></a>
                        <a class="cursor-pointer btn-event-remove" onclick="removeStatus('<?php echo $eachStatus['id']; ?>')"><i class="fa fa-trash"></i></a>
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
            Projects
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <table class="table dataTable table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>
                    Project ID
                </th>
                <th>
                    Project Status
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody class="tbody-fixed">

            <?php foreach ($projects as $eachProject):
                    ?>
                    <tr>
                        <td style="vertical-align: middle;">
                            <?php
                            $existFlag = false;
                            foreach ($userAnswers[$eachProject->user_id] as $row):
                                ?>
                                <?php if ($row['value_id'] == 'eMail'):
                                echo $row['value'];
                                $existFlag = true;
                                break;
                            endif; ?>
                                <?php
                            endforeach;

                            ?>
                            <?php
                            if ($existFlag == false):
                                echo $userAnswers[$eachProject->user_id][count($userAnswers[$eachProject->user_id]) - 1]['created_at'];
                            endif;
                            ?>

                        </td>
                        <td style="vertical-align: middle;">
                            <?php
                            echo $eachProject->project_status;
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-success" onclick="projectDetails(<?php echo ($eachProject->user_id); ?>)">Edit</a>
                        </td>
                    </tr>
                <?php
            endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="status-create" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Create new project status</h4>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Project Status: </label>
                    <div class="col-md-8">
                        <input name="project-status" class="form-control">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-create-event" onclick="saveStatus()">Save</button>
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

    var statusId = 0;

    function projectDetails(key) {
        console.log ('key', key);
        var jsonRequest = {
            'key': key
        };
        request('/admin/project_details', jsonRequest);
    }

    function addProjectStatus() {
        statusId = 0;
        $("input[name='project-status']").val("");
        $("#status-create").modal('show');
    }

    function editStatus(id, status) {
        statusId = id;
        $("input[name='project-status']").val(status);
        $("#status-create").modal('show');
    }

    function removeStatus(id) {
        var jsonRequest = {
            "statusId": id
        };
        request('/admin/remove_project_status', jsonRequest);
    }

    function saveStatus() {
        var jsonRequest = {
            "statusId": statusId,
            "status": $("input[name='project-status']").val()
        };
        request('/admin/add_project_status', jsonRequest, function() {
            $("#status-create").modal("hide");
        });
    }
</script>