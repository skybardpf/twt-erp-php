<?php
/**
 * Выбор организации, прикрепленной к событию (мероприятию).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var Power_attorney_organizationController $this
 * @var array $data
 *
 */

/**
 * @var TbActiveForm $form
 */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'form-select-element',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
));
echo CHtml::dropDownList('select-element', '', $data, array('prompt' => 'Выберите'));
$this->endWidget();