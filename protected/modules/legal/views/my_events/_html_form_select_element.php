<?php
    /**
     * Выбор организации, прикрепленной к событию (мероприятию).
     *
     * @author Skibardin A.A. <skybardpf@artektiv.ru>
     *
     * @var My_eventsController $this
     * @var array               $data
     * @var string              $type
     * @var TbActiveForm        $form
     */

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'form-select-element',
        'enableAjaxValidation' => false,
        'type' => 'horizontal',
        'htmlOptions' => array(
            'data-type' => $type
        )
    ));
    echo CHtml::dropDownList('select-element', '', $data);
    $this->endWidget();