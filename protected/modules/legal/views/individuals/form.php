<?php
/**
 * @var IndividualsController   $this
 * @var Individuals             $model
 * @var TbActiveForm            $form
 */
?>

<h2><?= ($model->primaryKey ? 'Редактирование' : 'Добавление') ?> физического лица</h2>

	<?php $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
		'id' => 'form-individual',
		'type' => 'horizontal',
        'enableAjaxValidation' => true,
        'enableClientValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => true,
        ),
	))?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Сохранить')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'link',
		'label' => 'Отмена',
		'url' => ($model->getprimaryKey()
		    ? $this->createUrl('view', array('id' => $model->getprimaryKey()))
			: $this->createUrl('index')
        )
	)); ?>

<?php
    if ($model->hasErrors()) {
        echo '<br/><br/>'. $form->errorSummary($model);
    }
?>

<fieldset>
    <?php
    $jui_date_options = array(
        'language' => 'ru',
        'options'=>array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true,
            'showOn' => 'button',
            'constrainInput' => 'true',
            'yearRange' => '1953:2013',
        ),
        'htmlOptions'=>array(
            'style' => 'height:20px;'
        )
    );

    echo $form->textFieldRow($model, 'family', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'name', array('class' => 'span6'));
    echo $form->textFieldRow($model, 'parent_name', array('class' => 'span6'));
    echo $form->dropDownListRow($model, 'citizenship', Countries::getValues());
?>
    <div class="control-group">
        <?= $form->labelEx($model, 'birth_date', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model'     => $model,
                    'attribute' => 'birth_date'
                ), $jui_date_options
            ));
            echo $form->error($model, 'birth_date');
        ?>
        </div>
    </div>
    <?= $form->textFieldRow($model, 'birth_place', array('class' => 'span6')); ?>
    <?= $form->textAreaRow($model, 'phone', array('class' => 'span6')); ?>
    <?= $form->textFieldRow($model, 'email', array('class' => 'span6')); ?>
    <?= $form->textAreaRow($model, 'adres'); ?>
    <?= $form->textFieldRow($model, 'ser_nom_pass', array('class' => 'span6')); ?>
    <?php /* date_pass */ ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'date_pass', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model'     => $model,
                    'attribute' => 'date_pass'
                ), $jui_date_options
            ));
            echo $form->error($model, 'date_pass');
            ?>
        </div>
    </div>

    <?= $form->textFieldRow(    $model, 'organ_pass',   array('class' => 'span6')); ?>
    <?php /* date_exp_pass */?>
    <div class="control-group">
        <?= $form->labelEx($model, 'date_exp_pass', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model'     => $model,
                    'attribute' => 'date_exp_pass'
                ), $jui_date_options
            ));
            echo $form->error($model, 'date_exp_pass');
            ?>
        </div>
    </div>
</fieldset>

<?php $this->endWidget(); ?>