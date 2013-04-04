<?php
/**
 * @var $this Interested_personsController
 * @var $form TbActiveForm
 * @var $model InterestedPerson
 */

$this->breadcrumbs=array(
	$this->controller_title => array('/legal/pegroup'),
	'Добавление',
);
?>
<h2>Добавление заинтересованного лица</h2>
<?php $this->renderPartial('form', array('model' => $model, 'error' => $error))?>