<?php

use yii\helpers\Url as Url;
use yii\widgets\ActiveForm;

// use yiister\gentelella\assets\Asset;

// Asset::register($this);

/* @var $this yii\web\View */

$this->title = 'Crowdfilms - Admin';
?>

<div class="x_panel">
    <div class="x_title">
        <h2>
            Project Details
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <br>
        <div class="form-horizontal form-label-left">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Project Title</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" name="project_title" value="<?php echo $project['project_title'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Project Page Live Status</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control" name="live_status">
                        <option value="Yes" <?php if ($project['live_status'] == 'Yes'):?>selected <?php endif; ?>>Yes</option>
                        <option value="No" <?php if ($project['live_status'] == 'No'):?>selected <?php endif; ?>>No</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Project Description</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" name="project_description" value="<?php echo $project['project_description'] ?>">
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Project Status</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control" name="project_status">
                        <?php
                        foreach ($projectStatus as $eachStatus):
                        ?>
                        <option value="<?php echo $eachStatus['status']?>" <?php if ($eachStatus['status'] == $project['project_status']): ?> selected <?php endif; ?>><?php echo $eachStatus['status'] ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Price</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" name="total_price" value="<?php echo $project['total_price'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Price Description</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" name="price_description" value="<?php echo $project['price_description'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Payment Due Now</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" name="payment_due" value="<?php echo $project['payment_due'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Already Paid Amount</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" name="already_paid" value="<?php echo $project['already_paid'] ?>">
                </div>
            </div>

            <div class="form-group">
                <h4 class="control-label col-md-3 col-sm-3"><b>Contact Info</b></h4>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" name="contact_name" value="<?php echo $project['contact_name'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" name="contact_email" value="<?php echo $project['contact_email'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Phone Number</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" name="contact_phone" value="<?php echo $project['contact_phone'] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Comment</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" name="contact_comment" value="<?php echo $project['contact_comment'] ?>">
                </div>
            </div>


            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-2 col-sm-3 col-xs-12 col-md-offset-10">
                    <a type="submit" class="btn btn-success" onclick="saveProject()">Save</a>
                    <a type="button" class="btn btn-primary" href="/admin/project">Cancel</a>
                </div>
            </div>
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

    function saveProject() {
        var jsonRequest = {
            'user_id': <?php echo $user_id; ?>,
            'project_title': $("input[name='project_title']").val(),
            'live_status': $("select[name='live_status']").val(),
            'contact_name': $("input[name='contact_name']").val(),
            'contact_email': $("input[name='contact_email']").val(),
            'contact_phone': $("input[name='contact_phone']").val(),
            'contact_comment': $("input[name='contact_comment']").val(),
            'project_description': $("input[name='project_description']").val(),
            'project_status': $("select[name='project_status']").val(),
            'total_price': $("input[name='total_price']").val(),
            'price_description': $("input[name='price_description']").val(),
            'payment_due': $("input[name='payment_due']").val(),
            'already_paid': $("input[name='already_paid']").val(),
        };

        request("/admin/save_project", jsonRequest);

        console.log('jsonRequest', jsonRequest);
    }

    $(document).ready(function () {

    });

</script>