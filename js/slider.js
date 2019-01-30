var value = -1;

$(document).ready(function(){
	$( "input[type='range']" ).each(function() {
	  	this.oninput = function() {
	  		
			$outputElement = $(document).find('input[data-id=' + $(this).data('field-id') + ']');
			$outputElement.val(this.value);

			setTimeout(function(){ if(this.value == value) { $outputElement.trigger('change'); }  }, 500);

			value = this.value;
		}
	});
});