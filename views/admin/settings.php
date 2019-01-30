<?php

use yii\helpers\Url as Url;
use yii\widgets\ActiveForm;
// use yiister\gentelella\assets\Asset;

// Asset::register($this);

/* @var $this yii\web\View */

$this->title = 'Crowdfilms - Admin';
$upload_options =  Yii::$app->params['adminTools']['upload'];
$export_options =  Yii::$app->params['adminTools']['export'];
?>
<div>
Settings
</div>
<!-- 
<div class="site-index">

    <div class="jumbotron">
        <h1>Crowdfilms - Admin tools</h1>

        <div>
	    	<h2> Cookies </h2>
		    <p>
		        User ID: <?=$cookies['user_id']?>
		    </p>
		    <p>
		        Session ID: <?=$cookies['session_id']?>
		    </p>
		</div>
		<br>
        <h2 class="lead">Upload CSVs</h2>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
        	<?php
				foreach($upload_options as $option):
        	
			?>
				<div class="row text-center">
	            	<?= $form->field($model, $option['attribute'])->fileInput() ?>
	        	</div>
        		
			<?php 
			    endforeach;

			?>
			
			<div class="row">
	            <p><button class="btn btn-lg btn-success">Submit</button></p>
        	</div>
		<?php ActiveForm::end() ?>


    </div>

    <div class="body-content">
    	<div class="jumbotron">
	        <p class="lead">Export Data</p>
	        <?php
					foreach($export_options as $option):
				?>
				<div class="row">
	            	<p><a class="btn btn-lg btn-success" href="<?=$option['url']?>"><?=$option['label']?></a></p>
	        	</div>
	        		
			<?php 
			    endforeach;
			?>
		</div>
    </div>
</div>
 -->