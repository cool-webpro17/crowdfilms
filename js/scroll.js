function nextQuestion(element, nextId)
{
	$(element).addClass('visited');
	
	var $container = $(element).closest('.question-container');

	if($container.is('#oneDayChoice') || $container.is('#conDaysChoice'))
	{
		console.log('here');
		$container.nextAll('.question-container:not(#' + nextId + ')').fadeOut(1500, function(){
			doScrolling(nextId);
			return;
		});
	}

	doScrolling(nextId);
	
}

function doScrolling(nextId)
{
	var $next = $('#' + nextId);
	$next.fadeIn(100, function(){
		$("html, body").animate({ 
			scrollTop: $next.offset().top 
		}, 500);
	});
}

$(window).bind("mousewheel", function() {
    $("html, body").stop();
});