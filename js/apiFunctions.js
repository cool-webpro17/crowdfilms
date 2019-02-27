function request(url, requestData, callback)
{
   $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        contentType: 'application/json',
        success: function (data) {
            if(callback)
            {
                callback(data.data);
            }
        },
        data: JSON.stringify(requestData)
    }); 
}

function sendEmail()
{
    request('data/email', []);
}

function saveValue(value_id, value)
{
	var data = {
        value_id: value_id,
        value: value
    };

    request('data/save', [data], function() {
        if (value_id == 'customerType') {
            request('/admin/film_formula', [], function(data) {
                var htmlResult = '';
                for (var i = 0; i < data.data.length; i++) {
                    htmlResult += data.data[i];
                }
                $(".formulles").html(htmlResult);
                var colWidth = 12 / data.data.length;
                var columns = $(".formulles").find(".w-col");
                columns.className = '';
                columns.addClass('w-col').addClass('column-' + colWidth).addClass('w-col-' + colWidth);
            });
        }
    });

    updateAnswerSelection(value_id, value);
}

function saveForm(element, callback)
{
    formData = [];
    anyInput = false;
    
    $.each($(element).closest('.question-container').find('input'), function( index, input ) {
        var data = 
        {
            value_id: $(input).data('id'),
            value: $(input).val()
        };

        if(data.value_id && data.value)
        {
            formData.push(data);
            anyInput = true;
        }
    });

    if(anyInput)
    {
        request('data/save', formData, callback);
        return true;
    }

    return false;
}

function calculateGrand()
{
    var updatePrices = function(data)
    {
        console.log('data', data);
        // $( "input[data-id='grandTotal']" ).val(data.grandTotal);
        var formulaArray = [
            'formulaOne', 'formulaTwo', 'formulaThree', 'formulaFour'
        ];
        $("#calculate").fadeOut();
        nextQuestion($("#calculate"), $("#calculate").data('next'));
        for (var i = 0; i < formulaArray.length; i++) {
            $("#" + formulaArray[i]).html('Berekende prijs:<br/>' + '<b>' + data[formulaArray[i]] + '</b>€')
            $("#" + formulaArray[i]).css('font-size', '20px');
        }
        // updateCrowdRentalPricing();
    };

    request('data/calculate', [], updatePrices);   
}

function updateCrowdRentalPricing()
{
    var data = {
        // Multiple fields with the same data-id : take last 
        expected_value: $( "input[data-id='crowdFunders']" ).last().val(),
        price_value:    $( "input[data-id='crowdIndiPrice']" ).val(),
        type: 'crowdfunding'
    };

    var updatePrice = function(data)
    {
        $( "input[data-id='discountTotal']" ).val(data.result);
    };

    request('data/calculate_crowdrental_pricing', data, updatePrice);
}

function updateQuestionsVisibility()
{
    request('data/previous_answers', [], markPreviousAnswers);
}

function markPreviousAnswers(data)
{
    $.each(data, function(key, val){
        $container = $("#" + key);
        $container.removeClass('hide-section');
        updateAnswerSelection(key, val);
    });
}

function updateAnswerSelection(id, answer)
{
    var $container = $("#" + id);
    $container.find('.answer-button, .next-button').each(function(){
        $(this).toggleClass('visited', $(this).data('answer') == answer || $(this).hasClass('next-button'));
    });
    $("a[data-next='calculate']").removeClass('visited');
    if (id == 'eMail') {

        request('data/formula_prices', null, function(data) {
            console.log('data', data);
            var formulaArray = [
                'formulaOne', 'formulaTwo', 'formulaThree', 'formulaFour'
            ];
            for (var i = 0; i < formulaArray.length; i++) {
                $("#" + formulaArray[i]).html('Berekende prijs:<br/>' + '<b>' + data[formulaArray[i]] + '</b>€')
                $("#" + formulaArray[i]).css('font-size', '20px');
            }
        });
        $('#eMail').css('display', 'none');
    } else {
        $container.find('input[data-id=' + id + ']').val(answer);
    }
}

function resetAnswers()
{
    request('data/remove_answers');
    location.reload();
}

$(document).ready(function(){
    $( "input[data-id='crowdFunders']" ).change(function() {
        // On input value change - change slider value as well
        $(document).find("input[data-field-id='crowdFunders']").val($(this).val());
        $(document).find("span#crowdFundersVal").html($(this).val());
        updateCrowdRentalPricing();
    });

    $( "input[data-id='crowdIndiPrice']" ).change(function() {
        // On input value change - change slider value as well
        $(document).find("input[data-field-id='crowdIndiPrice']").val($(this).val());
        updateCrowdRentalPricing();
    });

    $(".reset" ).click(function() {
        console.log('test');
        resetAnswers();
        updateQuestionsVisibility();
    });

    updateQuestionsVisibility();

    $(".inputfield").keyup(function(event) {
        if (($(this).data('id') != 'eventStartDate')
            && ($(this).data('id') != 'eventLocation')
            && ($(this).attr('id') != 'tel')
            && ($(this).data('id') != 'email')) {
            if (event.keyCode == '13') {
                console.log('enter Key pressed', event);
                $(this).parent().parent().parent().find(".next-button").trigger('click');
            }
        }
    });

    $(".firstformcontainer").on("click", ".answer-button, .next-button", function() {
    // $(".answer-button, .next-button").click(function(){

        var $button = $(this);
        var next = $button.data("next");
        if($button.hasClass("next-button"))
        {
            if(saveForm(this))
            {
                if(next == 'calculate')
                {
                    $('#eMail').css("display", "none");
                    calculateGrand();
                    $(document).find("input[data-id='email']").val($(document).find("input[data-id='eMail']").val());
                } else if (next == 'crowdFunders') {
                    var hours = $(document).find("input[data-id='hours']");
                    hours.css('background-color', '#ffffff');
                    nextQuestion($button, next);
                } else if (next == 'hoursdays') {
                    var conDays = $(document).find("input[data-id='conDays']");
                    conDays.css('background-color', '#ffffff');
                    var sepDays = $(document).find("input[data-id='sepDays']");
                    sepDays.css('background-color', '#ffffff');
                    nextQuestion($button, next);
                } else {
                    nextQuestion($button, next);
                }
            } else {
                if (next == 'crowdFunders') {
                    var hours = $(document).find("input[data-id='hours']");
                    hours.css('background-color', '#ffd8d8');
                } else if (next == 'hoursdays') {
                    var conDays = $(document).find("input[data-id='conDays']");
                    conDays.css('background-color', '#ffd8d8');
                    var sepDays = $(document).find("input[data-id='sepDays']");
                    sepDays.css('background-color', '#ffd8d8');
                } else {
                    nextQuestion($button, next);
                }
            }
        }
        else
        {
            saveValue($button.data("id"), $button.data("answer"));
            nextQuestion($button, next);
        }

    });
});
