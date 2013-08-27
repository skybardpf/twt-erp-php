<?php
/**
 * @var $this Ledocument_typeController
 * @var $model LEDocumentType
 * @var $form TbActiveForm
 * @var $error string
 */
Yii::app()->clientScript->registerScriptFile($this->module->assets.'/js/ledocument_type/form.js');

Country::getValues();
if ($error) echo CHtml::openTag('div', array('class' => 'alert alert-error')).$error.CHtml::closeTag('div'); ?>

<div class="form">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'    => 'model-form-form',
		'type'  => 'horizontal',
		'enableAjaxValidation' => false,
	))?>

	<?=$form->errorSummary($model)?>

	<fieldset>
		<?= $form->textFieldRow(    $model, 'name_of_doc',      array('class' => 'span6')); ?>
		<div class="control-group">
			<?=CHtml::label('Страны юрисдикции <span class="required">*</span>','', array('class' => 'control-label'))?>
			<div class="controls" id="list_of_countries">
				<?php if ($countries = $model->list_of_countries) { foreach($countries as $k => $c) :?>
					<div>
						<?=CHtml::dropDownList('LEDocumentType[new_countries]['.$k.'][country]', $c['country'], array('' => 'Не выбрана') + Country::$values, array('data-country_select' => 1) + ($c['from_user'] ? array() : array('disabled' => 'disabled')))?>
						<?=CHtml::textField('LEDocumentType[new_countries]['.$k.'][name_in_country]', $c['name_in_country'], $c['from_user'] ? array() : array('disabled' => 'disabled'))?>
					</div>
				<?php endforeach; }?>
				<div>
					<?=CHtml::dropDownList('', '', array('' => 'Не выбрана') + Country::$values, array('data-name' => 'LEDocumentType[new_countries][iteration][country]', 'data-country_select' => 1, 'data-new' => '1'))?>
					<?=CHtml::textField('', '', array('data-name' => 'LEDocumentType[new_countries][iteration][name_in_country]'))?>
				</div>
			</div>
		</div>


	</fieldset>
	<div class="control-group ">
		<div class="controls">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label'=> (!$model->getprimaryKey() ? 'Добавить' : 'Сохранить'))
			);
			echo '&nbsp;';
			$this->widget('bootstrap.widgets.TbButton', array(
					'url' => $this->createUrl('index'),
					'buttonType' => '',
					'type' => '',
					'label'=> 'Отмена')
			);?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div>