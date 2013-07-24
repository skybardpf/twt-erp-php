<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php
    if ($code == 500 && stripos($message, 'SoapClient::SoapClient') !== false){
        echo '<h3>SOAP сервис не доступен.</h3>';
        echo '<p>Повторите запрос позже.</p>';
    } else {
        echo CHtml::encode($message);
    }
?>
</div>