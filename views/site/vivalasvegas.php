<!DOCTYPE html>
<!--  This site was created in Webflow. http://www.webflow.com  -->
<!--  Last Published: Mon Jan 07 2019 13:16:09 GMT+0000 (UTC)  -->
<html data-wf-page="5c2ce05d7c68060513c6665b" data-wf-site="5be559b2511c0c4439ab8b99" lang="nl">
<head>
  <meta charset="utf-8">
  <title>film</title>
  <meta content="Geef ons meer info over je event en wij zoeken het perfecte team dat jouw event kan filmen." name="description">
  <meta content="film" property="og:title">
  <meta content="Geef ons meer info over je event en wij zoeken het perfecte team dat jouw event kan filmen." property="og:description">
  <meta content="summary" name="twitter:card">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta content="Webflow" name="generator">
  <link href="css/normalize.css" rel="stylesheet" type="text/css">
  <link href="css/webflow.css" rel="stylesheet" type="text/css">
  <link href="css/crowdfilms.webflow.css" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js" type="text/javascript"></script>
  <script type="text/javascript">WebFont.load({  google: {    families: ["Montserrat:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic","Droid Sans:400,700"]  }});</script>
  <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
  <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
  <link href="images/favicon-32x32.png" rel="shortcut icon" type="image/x-icon">
  <link href="images/favicon-256x256.png" rel="apple-touch-icon">
  <script type="text/javascript">(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create', 'UA-113962725-1', 'auto');ga('send', 'pageview');</script>
  <script type="text/javascript">!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.agent='plwebflow';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '294707321140261');fbq('track', 'PageView');</script>
  <style>
.hide-section{ display: none; }
	/*Visited button*/
  .button.visited {
  background-color: rgba(0, 0, 0, .4);
  color: #fff;
}
  /*sliders*/
  .slidecontainer {
    width: 100%; /* Width of the outside container */
}
/* The slider itself */
.slider {
    -webkit-appearance: none;  /* Override default CSS styles */
    appearance: none;
    width: 50%; /* Full-width */
    height: 3px; /* Specified height */
	border-radius: 5px; 
    background: #c33; /* red background */
    outline: none; /* Remove outline */
    opacity: 0.7; /* Set transparency (for mouse-over effects on hover) */
    -webkit-transition: .2s; /* 0.2 seconds transition on hover */
    transition: opacity .2s;
}
/* Mouse-over effects */
.slider:hover {
    opacity: 1; /* Fully shown on mouse-over */
}
/* The slider handle (use -webkit- (Chrome, Opera, Safari, Edge) and -moz- (Firefox) to override default look) */ 
.slider::-webkit-slider-thumb {
    -webkit-appearance: none; /* Override default look */
    appearance: none;
    width: 25px; /* Set a specific slider handle width */
    height: 25px; /* Slider handle height */
 	border-radius: 50%; 
	background: #cccccc;
    cursor: pointer; /* Cursor on hover */
}
.slider::-moz-range-thumb {
    width: 25px; /* Set a specific slider handle width */
    height: 25px; /* Slider handle height */
  	border-radius: 50%; 
	background: #cccccc;
    cursor: pointer; /* Cursor on hover */
}
</style>
  <script type="text/javascript">
  /* Slider function*/
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
</script>
  <script type="text/javascript">
  /*link to DB*/
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
    request('data/save', [data]);
}
function saveFormValue(value_id, element)
{
    saveValue(value_id, $(element).closest('form').serializeArray()[0].value);
}
function saveForm(element, callback)
{
    formData = [];
    $.each($(element).closest('form').find('input'), function( index, input ) {
        var data = 
        {
            value_id: $(input).data('id'),
            value: $(input).val()
        };
        if(data.value_id && data.value)
        {
            formData.push(data);
        }
    });
    request('data/save', formData, callback);
}
function calculatePricing()
{
    var updatePrices = function(data)
    {
        console.log(data);
        $( "input[name='total']" ).val(data.grandTotal);
        $( "input[name='rental-revenue']" ).val(data.rentalRevenue);
        $( "input[name='crowd-revenue']" ).val(data.crowdRevenue);
        $( "input[name='discount-total']" ).val(data.discountTotal);
    };
    request('data/calculate', [], updatePrices);
}
function updateCrowdRentalPricing(type)
{
    var inputIds = getInputIds(type);
    var data = {
        expected_value: $( "input[data-id='" + inputIds.expectedId + "']" ).val(),
        price_value:    $( "input[data-id='" + inputIds.priceId + "']" ).val(),
        type: type
    };
    var updatePrice = function(data)
    {
        $( "input[data-id='" + inputIds.revenueId + "']" ).val(data.result);
    };
    request('data/calculate_crowdrental_pricing', data, updatePrice);
}
function getInputIds(pricingType)
{
    var ids = { type: pricingType };
    ids.expectedId = pricingType == 'crowdfunding' ? 'crowdFunders' : 'rentalFunders';
    ids.priceId = pricingType == 'crowdfunding' ? 'crowdIndiPrice' : 'rentalIndiPrice';
    ids.revenueId = pricingType == 'crowdfunding' ? 'crowdFundRevenue' : 'rentalRevenue';
    return ids;
}
$(document).ready(function(){
    $( "input[data-id='crowdFunders']" ).change(function() {
      updateCrowdRentalPricing('crowdfunding');
    });
    $( "input[data-id='crowdIndiPrice']" ).change(function() {
      updateCrowdRentalPricing('crowdfunding');
    });
    $( "input[data-id='rentalFunders']" ).change(function() {
      updateCrowdRentalPricing('rental');
    });
    $( "input[data-id='rentalIndiPrice']" ).change(function() {
      updateCrowdRentalPricing('rental');
    });
});
</script>
  <script type="text/javascript">
  /*Scroll funciton*/
  function nextQuestion(element, nextId)
{
	var $container = $(element).closest('.question-container');
	if($container.is('#one-day') || $container.is('#con-days-choice'))
	{
		$container.nextAll('.question-container:not(#' + nextId + ')').fadeOut(1500, function(){
			doScrolling(element, nextId);
			return;
		});
	}
	doScrolling(element, nextId);
}
function doScrolling(element, nextId)
{
	var $next = $('#' + nextId);
	$next.fadeIn(1000, function(){
		$("html, body").animate({ 
			scrollTop: $next.offset().top 
		}, 2000);
	});
}
</script>
  <!--  Facebook Pixel Code  -->
  <script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '294707321140261');
  fbq('track', 'PageView');
</script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=294707321140261&ev=PageView&noscript=1"></noscript>
  <!--  End Facebook Pixel Code  -->
</head>
<body class="body">
  <div class="background-photo"></div>
  <header id="Navigation-panel" class="menubg"></header>
  <div class="w-container">
    <div data-collapse="medium" data-animation="default" data-duration="400" class="navbar w-nav">
      <div class="submenucontainer w-container"><a href="#" class="brand w-nav-brand"><img src="images/CrowdfilmsLogo_trans.png" width="240" srcset="images/CrowdfilmsLogo_trans-p-500.png 500w, images/CrowdfilmsLogo_trans-p-800.png 800w, images/CrowdfilmsLogo_trans-p-1080.png 1080w, images/CrowdfilmsLogo_trans-p-1600.png 1600w, images/CrowdfilmsLogo_trans-p-2000.png 2000w, images/CrowdfilmsLogo_trans.png 2206w" sizes="240px" alt="" class="brandlogo"></a>
        <nav role="navigation" class="nav-menu w-nav-menu"><a href="/" target="_blank" class="nav-link w-nav-link">Home</a><a href="https://crowdfilms.be#watis" target="_blank" class="nav-link w-nav-link">Wat is Crowdfilms?</a><a href="mailto:team@crowdfilms.be?subject=Hello%20Crowdfilms!" class="nav-link w-nav-link">team@crowdfilms.be</a><a href="tel:+3238082124" class="nav-link end-link w-nav-link">03 808 21 24</a></nav>
        <div class="menu-button w-nav-button">
          <div class="w-icon-nav-menu"></div>
        </div>
      </div>
    </div>
  </div>
  <div data-w-id="0e05ce6b-e3ca-9e8a-cec7-01daa2805a91" class="topcontainer w-container">
    <h1 class="topcontainer-title2">Bekijk een Crowdfilms-opname</h1>
    <div class="topcontainertext"><br>De onderstaande film werd geproduceerd dankzij een aanvraag via Crowdfilms en de eigenaar ervan wil deze graag met jou delen. <br><br>Ben jij de (mede-)organisator van dit evenement of heb je eraan bijgedragen? Dan willen wij bij Crowdfilms je feliciteren met jullie fantastische prestatie. <br><br>Veel kijkplezier! <br>Vragen? Zoals: &quot;wat is Crowdfilms&quot;?<br>Antwoorden vind je op onze <a href="https://crowdfilms.be" target="_blank">homepage</a>. </div>
    <div class="recordedvideo w-embed w-iframe w-script">
      <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/303737275?title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe></div>
      <script src="https://player.vimeo.com/api/player.js"></script>
    </div>
    <div class="div-block-4"></div>
    <div class="w-embed w-iframe w-script">
      <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/303741036?title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe></div>
      <script src="https://player.vimeo.com/api/player.js"></script>
    </div><img src="images/HappilyEverAfter.png" width="300" srcset="images/HappilyEverAfter-p-500.png 500w, images/HappilyEverAfter.png 588w" sizes="(max-width: 479px) 95vw, 300px" alt="" class="bottomimage"></div>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="js/webflow.js" type="text/javascript"></script>
  <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
  <script src="js/apiFunctions.js" type="text/javascript"></script>
  <script src="js/scroll.js" type="text/javascript"></script>
  <script src="js/slider.js" type="text/javascript"></script>
  <script>
  window.intercomSettings = {
    app_id: "wj7620rc"
  };
</script>
  <script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/wj7620rc';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
</body>
</html>