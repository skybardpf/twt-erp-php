<?php
/**
 * Выбор организации, прикрепленной к событию (мероприятию).
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var $this       My_eventsController
 * @var $data       array
 * @var $form       TbActiveForm
 */

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'menu-form',
        'enableAjaxValidation'=>false,
        'type' => 'horizontal',
    ));

    echo CHtml::dropDownList('select_organizations', '', $data);

    $this->endWidget();