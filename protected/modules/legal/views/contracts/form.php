<?php
/**
 * Форма редактирования договора.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var ContractsController $this
 * @var Contract            $model
 * @var Organizations       $organization
 */
?>

<?php
    echo '<h2>'.($model->primaryKey ? 'Редактирование' : 'Создание').' договора</h2>';

    /* @var $form MTbActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
        'id' => 'form-my-events',
        'type' => 'horizontal',
        'enableAjaxValidation' => false,
    ));

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'submit',
        'type'      => 'primary',
        'label'     => 'Сохранить'
    ));
    echo '&nbsp;';
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'label'      => 'Отмена',
        'url' => $model->primaryKey
            ? $this->createUrl('view', array('id' => $model->primaryKey))
            : $this->createUrl('list', array('org_id' => $organization->primaryKey)))
    );

    if ($model->hasErrors()) {
        echo '<br/><br/>'. $form->errorSummary($model);
    }
?>

<fieldset>
<?php
    // Опции для JUI селектора даты
    $jui_date_options = array(
        'options'=>array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
        ),
        'htmlOptions'=>array(
            'style' => 'height:20px;'
        )
    );
?>
</fieldset>

<?php $this->endWidget(); ?>