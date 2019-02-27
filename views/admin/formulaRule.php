<?php

use yii\helpers\Url as Url;
use yii\widgets\ActiveForm;

// use yiister\gentelella\assets\Asset;

// Asset::register($this);

/* @var $this yii\web\View */

$this->title = 'Crowdfilms - Admin';
?>

<input type="hidden" class="username" value="<?php echo $username; ?>" />

<div class="x_panel">
    <div class="x_title">
        <h2>
            Formula Rule
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div>
            <button class="btn btn-success btn-customer-add" onclick="addNewFormula()">Add New Formula Rule</button>
        </div>
        <table class="table dataTable table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>
                    Event Type
                </th>
                <th>
                    Film Type
                </th>
                <th>
                    Customer Type
                </th>
                <th>
                    F1 ID
                </th>
                <th>
                    F2 ID
                </th>
                <th>
                    F3 ID
                </th>
                <th>
                    F4 ID
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody class="tbody-fixed">
            <?php
            foreach ($formulaRules as $eachFormulaRule):
                ?>
                <tr>
                    <td><?php echo $eachFormulaRule['eventType']; ?></td>
                    <td><?php echo $eachFormulaRule['filmType']; ?></td>
                    <td><?php echo $eachFormulaRule['customerType']; ?></td>
                    <td><?php echo $eachFormulaRule['F1ID']; ?></td>
                    <td><?php echo $eachFormulaRule['F2ID']; ?></td>
                    <td><?php echo $eachFormulaRule['F3ID']; ?></td>
                    <td><?php echo $eachFormulaRule['F4ID']; ?></td>
                    <td class="text-center">
                        <a class="margin-right-30 cursor-pointer btn-customer-edit" onclick="editFormula('<?php echo $eachFormulaRule->eventType; ?>',
                                '<?php echo $eachFormulaRule->filmType; ?>',
                                '<?php echo $eachFormulaRule->customerType; ?>',
                                '<?php echo $eachFormulaRule->F1ID; ?>',
                                '<?php echo $eachFormulaRule->F2ID; ?>',
                                '<?php echo $eachFormulaRule->F3ID; ?>',
                                '<?php echo $eachFormulaRule->F4ID; ?>',
                                )"><i class="fa fa-pencil"></i></a>
                        <a class="cursor-pointer btn-customer-remove" onclick="removeFormula('<?php echo $eachFormulaRule->eventType; ?>',
                                '<?php echo $eachFormulaRule->filmType; ?>',
                                '<?php echo $eachFormulaRule->customerType; ?>',)"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<div id="formula-create" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Create new formula rule</h4>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Event Type: </label>
                    <div class="col-md-8">
                        <select name="eventType" class="form-control">
                            <option value=""></option>
                            <?php
                            foreach ($eventTypes as $eachType):
                                ?>
                                <option value="<?php echo $eachType['value']; ?>"><?php echo $eachType['text']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Film Type: </label>
                    <div class="col-md-8">
                        <select name="filmType" class="form-control">
                            <option value=""></option>
                            <?php
                            foreach ($filmTypes as $eachType):
                                ?>
                                <option value="<?php echo $eachType['value']; ?>"><?php echo $eachType['text']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Customer Type: </label>
                    <div class="col-md-8">
                        <select name="customerType" class="form-control">
                            <option value=""></option>
                            <?php
                            foreach ($customerTypes as $eachType):
                                ?>
                                <option value="<?php echo $eachType['value']; ?>"><?php echo $eachType['text']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Formula 1 ID: </label>
                    <div class="col-md-8">
                        <select name="F1ID" class="form-control">
                            <option value=""></option>
                            <?php
                            foreach ($formulas as $eachType):
                                ?>
                                <option value="<?php echo $eachType; ?>"><?php echo $eachType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Formula 2 ID: </label>
                    <div class="col-md-8">
                        <select name="F2ID" class="form-control">
                            <option value=""></option>
                            <?php
                            foreach ($formulas as $eachType):
                                ?>
                                <option value="<?php echo $eachType; ?>"><?php echo $eachType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Formula 3 ID: </label>
                    <div class="col-md-8">
                        <select name="F3ID" class="form-control">
                            <option value=""></option>
                            <?php
                            foreach ($formulas as $eachType):
                                ?>
                                <option value="<?php echo $eachType; ?>"><?php echo $eachType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Formula 4 ID: </label>
                    <div class="col-md-8">
                        <select name="F4ID" class="form-control">
                            <option value=""></option>
                            <?php
                            foreach ($formulas as $eachType):
                                ?>
                                <option value="<?php echo $eachType; ?>"><?php echo $eachType; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-create-event" onclick="saveFormula()">Save</button>
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

    function addNewFormula() {
        $("select[name='eventType']").prop('disabled', false).val('');
        $("select[name='filmType']").prop('disabled', false).val('');
        $("select[name='customerType']").prop('disabled', false).val('');
        $("select[name='F1ID']").val('');
        $("select[name='F2ID']").val('');
        $("select[name='F3ID']").val('');
        $("select[name='F4ID']").val('');
        $('#formula-create').modal('show');
    }

    function saveFormula() {
        var jsonRequest = {
            'eventType': $("select[name='eventType']").val(),
            'filmType': $("select[name='filmType']").val(),
            'customerType': $("select[name='customerType']").val(),
            'F1ID': $("select[name='F1ID']").val(),
            'F2ID': $("select[name='F2ID']").val(),
            'F3ID': $("select[name='F3ID']").val(),
            'F4ID': $("select[name='F4ID']").val()
        };

        if (jsonRequest.eventType == "" || jsonRequest.filmType == "" || jsonRequest.customerType == "") {
            alert('You need to specify all of Event Type, Film Type and Customer Type!');
        } else {
            if (jsonRequest.F1ID == "") {
                jsonRequest.F1ID = null;
            }
            if (jsonRequest.F2ID == "") {
                jsonRequest.F2ID = null;
            }
            if (jsonRequest.F3ID == "") {
                jsonRequest.F3ID = null;
            }
            if (jsonRequest.F4ID == "") {
                jsonRequest.F4ID = null;
            }
//            console.log('jsonRequest', jsonRequest);
            request('/admin/save_formula_rule', jsonRequest, function() {
                $('#formula-create').modal('hide');
            });
        }
    }

    function editFormula(eventType, filmType, customerType, F1ID, F2ID, F3ID, F4ID) {
        $("select[name='eventType']").val(eventType).attr('disabled', 'disabled');
        $("select[name='filmType']").val(filmType).attr('disabled', 'disabled');
        $("select[name='customerType']").val(customerType).attr('disabled', 'disabled');
        $("select[name='F1ID']").val(F1ID);
        $("select[name='F2ID']").val(F2ID);
        $("select[name='F3ID']").val(F3ID);
        $("select[name='F4ID']").val(F4ID);
        $("#formula-create").modal('show');
    }

    function removeFormula(eventType, filmType, customerType) {
        var jsonRequest = {
            'eventType': eventType,
            'filmType': filmType,
            'customerType': customerType
        };
        request('/admin/remove_formula_rule', jsonRequest);
    }
</script>
